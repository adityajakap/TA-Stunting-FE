<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Bmi;



use Illuminate\Http\Request;

class BMICalculatorController extends Controller
{
    protected \App\Services\ApiClient $api;

    public function __construct(\App\Services\ApiClient $api)
    {
        $this->api = $api;
    }

    public function showBmiData()
    {
        $childId = session('selected_child_id');
        if (!$childId) {
            return redirect()->route('orangtua.dashboard')->with('error', 'Pilih anak terlebih dahulu.');
        }

        $response = $this->api->get("/children/{$childId}/bmi");
        $data = $response->successful() ? $response->json() : [
            'bmi_records' => [],
            'last_bmi' => null,
            'estimated_calories' => null,
            'saran_kalori' => null,
        ];

        $bmiRecords = collect($data['bmi_records'] ?? [])->map(function ($item) {
            return (new Bmi)->forceFill((array)$item);
        });

        $lastBmi = null;
        if (!empty($data['last_bmi'])) {
            $lastBmi = (new Bmi)->forceFill((array)$data['last_bmi']);
        }

        return view('orangtua.bmi.bmi', [
            'bmiRecords' => $bmiRecords,
            'lastBmi' => $lastBmi,
            'estimatedCalories' => $data['estimated_calories'] ?? null,
            'saranKalori' => $data['saran_kalori'] ?? null
        ]);
    }

    public function calculate(Request $request)
    {
        $gender = strtolower($request->input('gender'));
        $rawTinggi = $request->input('tinggi');
        $tinggi = $request->input('tinggi') / 100;
        $berat = $request->input('berat');

        $bmi = ($tinggi > 0) ? ($berat / ($tinggi * $tinggi)) : 0;

        if ($gender == 'pria' || $gender == 'laki-laki') {
            $status = $this->statusBmiPria($bmi);
        } elseif ($gender == 'wanita' || $gender == 'perempuan') {
            $status = $this->statusBmiWanita($bmi);
        } else {
            $status = "Gender tidak valid";
        }

        session([
            'bmi'=> number_format($bmi, 2),
            'status'=> $status,
            'tinggi'=> $rawTinggi,
            'gender'=> $gender,
            'berat'=> $berat
        ]);

        return redirect()->route('bmi');
    }
    
    public function reset()
    {
        session()->forget(['bmi', 'status', 'tinggi', 'gender', 'berat', 'kalori', 'usia', 'activity_level', 'show_kalori_results']);
        return redirect()->route('bmi');
    }

    public function save(Request $request)
    {
        $childId = session('selected_child_id');
        if (!$childId) {
            return redirect()->route('orangtua.dashboard')->with('error', 'Pilih anak terlebih dahulu.');
        }

        // Fetch child birthdate to calculate age (usia)
        $childResponse = $this->api->get("/children/{$childId}");
        $usia = 0;
        if ($childResponse->successful()) {
            $childData = $childResponse->json();
            if (!empty($childData['tanggal_lahir'])) {
                $usia = \Carbon\Carbon::parse($childData['tanggal_lahir'])->age;
            }
        }

        // Merge calculated age and default activity level into request before validation
        $request->merge([
            'usia' => $usia,
            'activity_level' => 'sedentary'
        ]);

        $request->validate([
            'berat' => 'required|numeric',
            'tinggi' => 'required|numeric',
            'usia' => 'required|numeric',
            'gender' => 'required|in:pria,wanita',
            'activity_level' => 'required|string',
        ]);

        $response = $this->api->post("/children/{$childId}/bmi", $request->only([
            'berat', 'tinggi', 'usia', 'gender', 'activity_level'
        ]));

        if ($response->successful()) {
            return redirect()->route('bmi')->with('success', 'Data berhasil disimpan!');
        }

        return redirect()->back()->withErrors(['message' => 'Gagal menyimpan data BMI'])->withInput();
    }

    private function statusBmiPria($bmi)
    {
        if ($bmi < 18.5) return "Underweight";
        if ($bmi >= 18.5 && $bmi < 24.9) return "Normal";
        if ($bmi >= 25 && $bmi < 29.9) return "Overweight";
        return "Obese";
    }

    private function statusBmiWanita($bmi)
    {
        if ($bmi < 17.5) return "Underweight";
        if ($bmi >= 17.5 && $bmi < 23.9) return "Normal";
        if ($bmi >= 24 && $bmi < 28.9) return "Overweight";
        return "Obese";
    }

    public function deleteRow($id)
    {
        $childId = session('selected_child_id');
        if (!$childId) abort(403);

        $this->api->delete("/children/{$childId}/bmi/{$id}");
        return redirect()->route('bmi');
    }

    public function hitungKalori(Request $request)
    {
        $validated = $request->validate([
            'gender' => 'required|in:pria,wanita',
            'berat' => 'required|numeric|min:10',
            'tinggi' => 'required|numeric|min:50',
            'usia' => 'required|numeric|min:1',
            'activity_level' => 'required|in:sedentary,lightly_active,moderately_active,very_active,extra_active',
        ]);

        $gender = $validated['gender'];
        $berat = $validated['berat'];
        $tinggi = $validated['tinggi'];
        $usia = $validated['usia'];
        $activity_level = $validated['activity_level'];

        if ($gender == 'pria') {
            $bmr = 66 + (13.7 * $berat) + (5 * $tinggi) - (6.8 * $usia);
        } else {
            $bmr = 655 + (9.6 * $berat) + (1.8 * $tinggi) - (4.7 * $usia);
        }

        $activity_factors = [
            'sedentary' => 1.2,
            'lightly_active' => 1.375,
            'moderately_active' => 1.55,
            'very_active' => 1.725,
            'extra_active' => 1.9,
        ];

        $kalori = round($bmr * $activity_factors[$activity_level]);

        session([
            'kalori' => $kalori,
            'berat' => $berat,
            'tinggi' => $tinggi,
            'usia' => $usia,
            'gender' => $gender,
            'activity_level' => $activity_level,
            'show_kalori_results' => true,
        ]);

        return redirect()->back()->withInput();
    }

}