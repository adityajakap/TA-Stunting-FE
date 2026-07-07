<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ApiClient;
use Illuminate\Http\Request;

class DetectionController extends Controller
{
    protected ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    public function create()
    {
        $childId = session('selected_child_id');
        if (!$childId) {
            return redirect()->route('orangtua.dashboard')->with('error', 'Silakan pilih atau tambahkan data anak terlebih dahulu.');
        }
        $response = $this->api->get("/children/{$childId}/detections");
        $semua = $response->successful() ? $response->json() : [];

        $kmsData = null;
        $kmsResponse = $this->api->get("/children/{$childId}/kms-data");
        if ($kmsResponse->successful()) {
            $kmsData = $kmsResponse->json();
        }

        return view('orangtua.detections.deteksi', compact('semua', 'kmsData'));
    }

    public function store(Request $request)
    {
        $childId = session('selected_child_id');
        if (!$childId) {
            return redirect()->route('orangtua.dashboard')->with('error', 'Silakan pilih atau tambahkan data anak terlebih dahulu.');
        }

        $request->validate([
            'berat_badan'   => 'required|numeric',
            'tinggi_badan'  => 'required|numeric',
        ]);

        $childRes = $this->api->get("/children/{$childId}");
        if ($childRes->successful()) {
            $child = $childRes->json();
            $tanggal_lahir = \Carbon\Carbon::parse($child['tanggal_lahir']);
            $umur = (int) $tanggal_lahir->diffInMonths(\Carbon\Carbon::now());
            $jenis_kelamin = $child['jenis_kelamin'] ?? 'L';
        } else {
            return back()->with('error', 'Gagal mengambil data anak.');
        }

        $payload = [
            'umur' => $umur,
            'jenis_kelamin' => $jenis_kelamin,
            'berat_badan' => (float) $request->input('berat_badan'),
            'tinggi_badan' => (float) $request->input('tinggi_badan'),
        ];

        $response = $this->api->post("/children/{$childId}/detections", $payload);

        if ($response->successful()) {
            $detectionData = $response->json();
            $status = $detectionData['status'] ?? 'Normal';
            
            // Fetch menus for recommendation
            $menuRes = $this->api->get('/nutrition');
            $rekomendasi = collect();
            
            if ($menuRes->successful()) {
                $allMenus = collect($menuRes->json());
                
                $isStunting = in_array(strtolower($status), ['stunting', 'sangat pendek', 'pendek']);
                $targetKategori = $isStunting ? 'Stunting' : 'Normal';
                
                $filtered = $allMenus->filter(function($menu) use ($targetKategori) {
                    return (isset($menu['kategori_stunting']) && strtolower($menu['kategori_stunting']) === strtolower($targetKategori));
                });
                
                if ($filtered->isEmpty()) {
                    // Fallback if no matching menus found
                    $rekomendasi = $allMenus->count() >= 3 ? $allMenus->random(3) : $allMenus;
                } else {
                    $rekomendasi = $filtered->count() >= 3 ? $filtered->random(3) : $filtered;
                }
            }

            return redirect()->route('orangtua.detections.create')
                ->with('success', 'Deteksi berhasil disimpan!')
                ->with('rekomendasi_menu', $rekomendasi)
                ->with('status_deteksi', $status);
        }

        $errorMessage = $response->json('message', 'Gagal menyimpan deteksi.');
        if ($response->json('errors')) {
            $errorMessage = collect($response->json('errors'))->flatten()->first() ?: $errorMessage;
        }

        return back()->with('error', $errorMessage);
    }

    public function destroy($id)
    {
        $childId = session('selected_child_id');
        $response = $this->api->delete("/children/{$childId}/detections/{$id}");

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Gagal menghapus data.');
    }

    // Admin
    public function adminIndex()
    {
        $response = $this->api->get('/admin/detections');
        $semua = $response->successful() ? collect($response->json())->map(function ($item) {
            $childRelation = $item['child'] ?? null;
            unset($item['child']);
            
            $detection = (new \App\Models\Detection)->forceFill((array)$item);
            if ($childRelation) {
                $childData = $childRelation;
                $userRelation = $childData['user'] ?? null;
                unset($childData['user']);
                $child = (new \App\Models\Child)->forceFill((array)$childData);
                if ($userRelation) {
                    $child->setRelation('user', (new \App\Models\User)->forceFill((array)$userRelation));
                }
                $detection->setRelation('child', $child);
            }
            return $detection;
        }) : collect();

        $indonesianMonths = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $availableMonths = $semua->map(function ($item) {
            return (int)\Carbon\Carbon::parse($item->created_at)->format('n');
        })->unique()->sort()->values()->map(function($m) use ($indonesianMonths) {
            return ['value' => $m, 'label' => $indonesianMonths[$m]];
        });

        $availableYears = $semua->map(function ($item) {
            return (int)\Carbon\Carbon::parse($item->created_at)->format('Y');
        })->unique()->sortDesc()->values();

        return view('admin.detections.index', compact('semua', 'availableMonths', 'availableYears'));
    }

    public function adminCreate()
    {
        $response = $this->api->get('/admin/children');
        $users = collect($response->successful() ? $response->json() : [])->map(function ($item) {
            $userRelation = $item['user'] ?? null;
            unset($item['user']);
            $child = (new \App\Models\Child)->forceFill((array)$item);
            if ($userRelation) {
                $child->setRelation('user', (new \App\Models\User)->forceFill((array)$userRelation));
            }
            return $child;
        })->filter(function ($child) {
            if (empty($child->tanggal_lahir)) return true;
            $umur = \Carbon\Carbon::parse($child->tanggal_lahir)->diffInMonths(\Carbon\Carbon::now());
            return $umur <= 60;
        });

        return view('admin.detections.create', compact('users'));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'child_id'      => 'required|integer',
            'berat_badan'   => 'required|numeric',
            'tinggi_badan'  => 'required|numeric',
        ]);

        $response = $this->api->post("/admin/detections", $request->only([
            'child_id', 'berat_badan', 'tinggi_badan',
        ]));

        if ($response->successful()) {
            $detectionData = $response->json();
            $status = $detectionData['status'] ?? 'Normal';
            
            $menuRes = $this->api->get('/nutrition');
            $rekomendasi = collect();
            
            if ($menuRes->successful()) {
                $allMenus = collect($menuRes->json());
                
                $isStunting = in_array(strtolower($status), ['stunting', 'sangat pendek', 'pendek']);
                $targetKategori = $isStunting ? 'Stunting' : 'Normal';
                
                $filtered = $allMenus->filter(function($menu) use ($targetKategori) {
                    return (isset($menu['kategori_stunting']) && strtolower($menu['kategori_stunting']) === strtolower($targetKategori));
                });
                
                if ($filtered->isEmpty()) {
                    $rekomendasi = $allMenus->count() >= 3 ? $allMenus->random(3) : $allMenus;
                } else {
                    $rekomendasi = $filtered->count() >= 3 ? $filtered->random(3) : $filtered;
                }
            }

            $kmsData = null;
            $kmsResponse = $this->api->get("/children/{$request->child_id}/kms-data");
            if ($kmsResponse->successful()) {
                $kmsData = $kmsResponse->json();
            }

            return redirect()->route('admin.detections.create')
                ->with('success', 'Deteksi berhasil disimpan!')
                ->with('rekomendasi_menu', $rekomendasi)
                ->with('kmsData', $kmsData)
                ->withInput();
        }

        return back()->with('error', collect($response->json('errors'))->flatten()->first() ?: $response->json('message', 'Gagal menyimpan deteksi.'));
    }

    public function exportPdf(Request $request)
    {
        $sValue = (int) $request->input('s_value', 0);
        
        // Fetch dashboard stats for K (total registered children)
        $dashboardResponse = $this->api->get('/admin/dashboard');
        $kValue = 0;
        if ($dashboardResponse->successful()) {
            $kValue = $dashboardResponse->json('total_anak') ?? 0;
        }

        $response = $this->api->get('/admin/detections');
        $allDetections = $response->successful() ? collect($response->json())->map(function ($item) {
            $childRelation = $item['child'] ?? null;
            unset($item['child']);
            
            $detection = (new \App\Models\Detection)->forceFill((array)$item);
            if ($childRelation) {
                $childData = $childRelation;
                $userRelation = $childData['user'] ?? null;
                unset($childData['user']);
                $child = (new \App\Models\Child)->forceFill((array)$childData);
                if ($userRelation) {
                    $child->setRelation('user', (new \App\Models\User)->forceFill((array)$userRelation));
                }
                $detection->setRelation('child', $child);
            }
            return $detection;
        }) : collect();

        $semua = $allDetections;

        // Apply month and year filtering if requested
        $selectedMonths = $request->input('months', []);
        $selectedYears = $request->input('years', []);
        
        if (!empty($selectedMonths) && !in_array('all', $selectedMonths)) {
            $semua = $semua->filter(function ($item) use ($selectedMonths) {
                return in_array((int)\Carbon\Carbon::parse($item->created_at)->format('n'), $selectedMonths) || 
                       in_array(\Carbon\Carbon::parse($item->created_at)->format('n'), $selectedMonths);
            });
        }
        
        if (!empty($selectedYears) && !in_array('all', $selectedYears)) {
            $semua = $semua->filter(function ($item) use ($selectedYears) {
                return in_array((int)\Carbon\Carbon::parse($item->created_at)->format('Y'), $selectedYears) ||
                       in_array(\Carbon\Carbon::parse($item->created_at)->format('Y'), $selectedYears);
            });
        }

        $selectedInputters = $request->input('inputters', []);
        
        if (!empty($selectedInputters) && !in_array('all', $selectedInputters)) {
            $semua = $semua->filter(function ($item) use ($selectedInputters) {
                return in_array(strtolower($item->added_by ?? 'orangtua'), $selectedInputters);
            });
        }

        // Group the filtered data by Month-Year and Inputter
        $groupedData = [];

        $groupedByMonthAndInputter = $semua->groupBy(function($item) {
            $monthYear = \Carbon\Carbon::parse($item->created_at)->translatedFormat('F Y');
            $inputter = ucfirst(strtolower($item->added_by ?? 'Orangtua'));
            return $monthYear . '|' . $inputter;
        });

        foreach ($groupedByMonthAndInputter as $key => $groupItems) {
            list($monthYear, $inputter) = explode('|', $key);
            
            $dValue = $groupItems->unique('child_id')->count();
            $nValue = 0;
            $tValue = 0;
            $bValue = 0;

            $uniqueWeighedChildren = $groupItems->unique('child_id');
            foreach ($uniqueWeighedChildren as $current) {
                $previous = $allDetections->where('child_id', $current->child_id)
                                          ->where('created_at', '<', $current->created_at)
                                          ->sortByDesc('created_at')
                                          ->first();
                                          
                if (!$previous) {
                    $bValue++;
                } else {
                    if ($current->berat_badan > $previous->berat_badan) {
                        $nValue++;
                    } else {
                        $tValue++;
                    }
                }
            }

            $oValue = max(0, $kValue - $dValue);

            $groupedData[] = [
                'monthYear' => $monthYear,
                'inputter' => $inputter,
                'items' => $groupItems,
                'dValue' => $dValue,
                'nValue' => $nValue,
                'tValue' => $tValue,
                'bValue' => $bValue,
                'oValue' => $oValue,
            ];
        }

        $html = view('admin.detections.pdf', compact(
            'groupedData', 'sValue', 'kValue'
        ))->render();
        
        $pdf = \PDF::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('Laporan_Data_Deteksi_Stunting_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
