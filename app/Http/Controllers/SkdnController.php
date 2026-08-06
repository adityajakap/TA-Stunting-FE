<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiClient;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class SkdnController extends Controller
{
    protected ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    public function index()
    {
        // Ambil semua deteksi
        $response = $this->api->get('/admin/detections');
        $allDetections = $response->successful() ? collect($response->json()) : collect();

        // Filter hanya data dari Kader
        $allDetections = $allDetections->filter(function($d) {
            return strtolower($d['added_by'] ?? 'orangtua') === 'kader';
        })->values();

        // Ambil semua target untuk mendapatkan S
        $targetsAllRes = $this->api->get('/skdn-target');
        $targetsAllData = $targetsAllRes->successful() ? collect($targetsAllRes->json()) : collect();

        // Group berdasarkan bulan dan tahun
        $groupedData = [];
        $groups = $allDetections->groupBy(function($item) {
            return Carbon::parse($item['created_at'])->format('Y-m'); // e.g. "2026-06"
        })->sortKeysDesc();

        $no = 1;
        foreach ($groups as $monthYearKey => $items) {
            $dateObj = Carbon::parse($monthYearKey . '-01');
            $bulanNama = $dateObj->translatedFormat('F'); // "Juni"
            $tahun = $dateObj->format('Y'); // "2026"
            
            // Cari tanggal kegiatan terakhir di bulan ini
            $lastDate = $items->max('created_at');
            $tanggalKegiatan = Carbon::parse($lastDate)->translatedFormat('d F Y');

            $monthStr = $dateObj->format('m');
            $target = $targetsAllData->where('year', (string)$tahun)->firstWhere('month', $monthStr);
            $sValue = $target ? (int)$target['s_value'] : 0;

            $dValue = $items->unique('child_id')->count();

            $groupedData[] = [
                'no' => $no++,
                'posyandu' => 'Nusa Indah 1',
                'bulan_nama' => $bulanNama,
                'bulan' => $monthStr,
                'tahun' => $tahun,
                'tanggal_kegiatan' => $tanggalKegiatan,
                's_value' => $sValue,
                'd_value' => $dValue,
            ];
        }

        return view('admin.skdn.index', compact('groupedData'));
    }

    public function grafik()
    {
        $currentYear = Carbon::now()->format('Y');

        // Ambil semua deteksi
        $response = $this->api->get('/admin/detections');
        $allDetections = $response->successful() ? collect($response->json()) : collect();

        // Filter hanya data dari Kader
        $allDetections = $allDetections->filter(function($d) {
            return strtolower($d['added_by'] ?? 'orangtua') === 'kader';
        })->values();

        // Ambil semua target
        $targetsAllRes = $this->api->get('/skdn-target');
        $targetsAllData = $targetsAllRes->successful() ? collect($targetsAllRes->json()) : collect();

        // Filter deteksi untuk tahun berjalan
        $detectionsThisYear = $allDetections->filter(function($d) use ($currentYear) {
            return Carbon::parse($d['created_at'])->format('Y') === $currentYear;
        });

        $dashResponse = $this->api->get('/admin/dashboard');
        $kValueGlobal = $dashResponse->successful() ? (int)$dashResponse->json('total_anak') : 0;

        // Hitung K, D, N bulanan
        $yearlyChart = [];
        $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        foreach($months as $idx => $m) {
            $mNum = str_pad($idx + 1, 2, '0', STR_PAD_LEFT);
            $detsMonth = $detectionsThisYear->filter(function($d) use ($mNum) {
                return Carbon::parse($d['created_at'])->format('m') === $mNum;
            });
            
            $dValueChart = $detsMonth->unique('child_id')->count();
            $nValueChart = $detsMonth->filter(function($d) {
                return strtolower($d['status'] ?? '') === 'tinggi' || strtolower($d['status'] ?? '') === 'normal';
            })->count();

            // S dari Target
            $target = $targetsAllData->where('year', $currentYear)->firstWhere('month', $mNum);
            $sValueChart = $target ? (int)$target['s_value'] : 0;
            
            // K adalah total anak terdaftar
            $kValueChart = $kValueGlobal;
            
            $yearlyChart[] = [
                'month' => $m,
                'S' => $sValueChart,
                'K' => $kValueChart,
                'D' => $dValueChart,
                'N' => $nValueChart
            ];
        }

        return view('admin.skdn.grafik', compact('yearlyChart', 'currentYear'));
    }

    public function show($month, $year)
    {
        // Ambil sasaran
        $sasaranResponse = $this->api->get('/skdn-target', ['month' => str_pad($month, 2, '0', STR_PAD_LEFT), 'year' => $year]);
        $sValue = null;
        if ($sasaranResponse->successful() && $sasaranResponse->json()) {
            $sValue = $sasaranResponse->json('s_value');
        }

        // Ambil dashboard K (total balita)
        $dashResponse = $this->api->get('/admin/dashboard');
        $kValue = $dashResponse->successful() ? (int)$dashResponse->json('total_anak') : 0;

        // Ambil semua deteksi
        $detResponse = $this->api->get('/admin/detections');
        $allDetections = $detResponse->successful() ? collect($detResponse->json()) : collect();

        // Filter hanya data dari Kader
        $allDetections = $allDetections->filter(function($d) {
            return strtolower($d['added_by'] ?? 'orangtua') === 'kader';
        })->values();

        // Filter berdasarkan bulan dan tahun
        $monthDetections = $allDetections->filter(function($d) use ($month, $year) {
            $dDate = Carbon::parse($d['created_at']);
            return $dDate->format('m') === str_pad($month, 2, '0', STR_PAD_LEFT) && $dDate->format('Y') === $year;
        });

        // Hitung D dan N
        $dValue = $monthDetections->unique('child_id')->count();
        $nValue = 0;
        
        $uniqueWeighedChildren = $monthDetections->unique('child_id');
        foreach ($uniqueWeighedChildren as $current) {
            $previous = $allDetections->where('child_id', $current['child_id'])
                                      ->where('created_at', '<', $current['created_at'])
                                      ->sortByDesc('created_at')
                                      ->first();
                                      
            if ($previous && isset($current['berat_badan']) && isset($previous['berat_badan'])) {
                if ($current['berat_badan'] > $previous['berat_badan']) {
                    $nValue++;
                }
            }
        }

        return view('admin.skdn.show', compact('month', 'year', 'sValue', 'kValue', 'dValue', 'nValue', 'monthDetections'));
    }

    public function storeTarget(Request $request, $month, $year)
    {
        $request->validate([
            's_value' => 'required|numeric|min:0'
        ]);

        $this->api->post('/skdn-target', [
            'month' => str_pad($month, 2, '0', STR_PAD_LEFT),
            'year' => (string)$year,
            's_value' => $request->s_value
        ]);

        return redirect()->route('admin.skdn.show', ['month' => $month, 'year' => $year])
            ->with('success', 'Jumlah sasaran berhasil disimpan dan dikunci.');
    }

    public function exportPdf($month, $year)
    {
        // Logic mirip show, tapi di return PDF
        $sasaranResponse = $this->api->get('/skdn-target', ['month' => str_pad($month, 2, '0', STR_PAD_LEFT), 'year' => $year]);
        $sValue = 0;
        if ($sasaranResponse->successful() && $sasaranResponse->json()) {
            $sValue = (int)$sasaranResponse->json('s_value');
        }

        $dashResponse = $this->api->get('/admin/dashboard');
        $kValue = $dashResponse->successful() ? (int)$dashResponse->json('total_anak') : 0;

        $detResponse = $this->api->get('/admin/detections');
        $allDetections = $detResponse->successful() ? collect($detResponse->json()) : collect();

        // Filter hanya data dari Kader
        $allDetections = $allDetections->filter(function($d) {
            return strtolower($d['added_by'] ?? 'orangtua') === 'kader';
        })->values();

        $monthDetections = $allDetections->filter(function($d) use ($month, $year) {
            $dDate = Carbon::parse($d['created_at']);
            return $dDate->format('m') === str_pad($month, 2, '0', STR_PAD_LEFT) && $dDate->format('Y') === $year;
        });

        $dValue = $monthDetections->unique('child_id')->count();
        $nValue = 0;
        
        foreach ($monthDetections->unique('child_id') as $current) {
            $previous = $allDetections->where('child_id', $current['child_id'])
                                      ->where('created_at', '<', $current['created_at'])
                                      ->sortByDesc('created_at')
                                      ->first();
            if ($previous && isset($current['berat_badan']) && isset($previous['berat_badan']) && $current['berat_badan'] > $previous['berat_badan']) {
                $nValue++;
            }
        }
        
        $dateObj = Carbon::parse($year . '-' . $month . '-01');
        $bulanNama = $dateObj->translatedFormat('F');

        $pdf = Pdf::loadView('admin.skdn.pdf', compact('month', 'year', 'bulanNama', 'sValue', 'kValue', 'dValue', 'nValue', 'monthDetections'))
            ->setPaper('a4', 'landscape');
            
        return $pdf->stream("Laporan_SKDN_{$bulanNama}_{$year}.pdf");
    }
}
