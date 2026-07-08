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

            $groupedData[] = [
                'no' => $no++,
                'posyandu' => 'Nusa Indah 1',
                'bulan_nama' => $bulanNama,
                'bulan' => $dateObj->format('m'),
                'tahun' => $tahun,
                'tanggal_kegiatan' => $tanggalKegiatan,
            ];
        }

        $currentYear = Carbon::now()->format('Y');
        
        // Data for yearly chart
        $targetsRes = $this->api->get('/skdn-target', ['year' => $currentYear]);
        $targetsData = $targetsRes->successful() ? collect($targetsRes->json()) : collect();

        $dashResponse = $this->api->get('/admin/dashboard');
        $kValue = $dashResponse->successful() ? (int)$dashResponse->json('total_anak') : 0;
        
        $yearDetections = $allDetections->filter(function($d) use ($currentYear) {
            return Carbon::parse($d['created_at'])->format('Y') === $currentYear;
        });

        $yearlyChart = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthStr = str_pad($m, 2, '0', STR_PAD_LEFT);
            $target = $targetsData->firstWhere('month', $monthStr);
            $sValue = $target ? (int)$target['s_value'] : 0;

            $monthDets = $yearDetections->filter(function($d) use ($monthStr) {
                return Carbon::parse($d['created_at'])->format('m') === $monthStr;
            });

            $dValue = $monthDets->unique('child_id')->count();
            $nValue = 0;

            foreach ($monthDets->unique('child_id') as $current) {
                $previous = $allDetections->where('child_id', $current['child_id'])
                                          ->where('created_at', '<', $current['created_at'])
                                          ->sortByDesc('created_at')
                                          ->first();
                if ($previous && isset($current['berat_badan']) && isset($previous['berat_badan']) && $current['berat_badan'] > $previous['berat_badan']) {
                    $nValue++;
                }
            }

            $yearlyChart[] = [
                'month' => Carbon::create()->month($m)->translatedFormat('F'),
                'S' => $sValue,
                'K' => $kValue,
                'D' => $dValue,
                'N' => $nValue
            ];
        }

        return view('admin.skdn.index', compact('groupedData', 'yearlyChart', 'currentYear'));
    }

    public function show($month, $year)
    {
        // Ambil sasaran
        $sasaranResponse = $this->api->get('/skdn-target', ['month' => $month, 'year' => $year]);
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
        $sasaranResponse = $this->api->get('/skdn-target', ['month' => $month, 'year' => $year]);
        $sValue = 0;
        if ($sasaranResponse->successful() && $sasaranResponse->json()) {
            $sValue = (int)$sasaranResponse->json('s_value');
        }

        $dashResponse = $this->api->get('/admin/dashboard');
        $kValue = $dashResponse->successful() ? (int)$dashResponse->json('total_anak') : 0;

        $detResponse = $this->api->get('/admin/detections');
        $allDetections = $detResponse->successful() ? collect($detResponse->json()) : collect();

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
