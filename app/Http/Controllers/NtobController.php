<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiClient;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class NtobController extends Controller
{
    protected ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    public function index()
    {
        $response = $this->api->get('/admin/detections');
        $allDetections = $response->successful() ? collect($response->json()) : collect();

        $allDetections = $allDetections->filter(function($d) {
            return strtolower($d['added_by'] ?? 'orangtua') === 'kader';
        })->values();

        $groupedData = [];
        $groups = $allDetections->groupBy(function($item) {
            return Carbon::parse($item['created_at'])->format('Y-m');
        })->sortKeysDesc();

        $no = 1;
        foreach ($groups as $monthYearKey => $items) {
            $dateObj = Carbon::parse($monthYearKey . '-01');
            $bulanNama = $dateObj->translatedFormat('F');
            $tahun = $dateObj->format('Y');
            
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

        return view('admin.ntob.index', compact('groupedData'));
    }

    public function show($month, $year)
    {
        $dashResponse = $this->api->get('/admin/dashboard');
        $kValue = $dashResponse->successful() ? (int)$dashResponse->json('total_anak') : 0;

        $detResponse = $this->api->get('/admin/detections');
        $allDetections = $detResponse->successful() ? collect($detResponse->json()) : collect();

        $allDetections = $allDetections->filter(function($d) {
            return strtolower($d['added_by'] ?? 'orangtua') === 'kader';
        })->values();

        $monthDetections = $allDetections->filter(function($d) use ($month, $year) {
            $dDate = Carbon::parse($d['created_at']);
            return $dDate->format('m') === str_pad($month, 2, '0', STR_PAD_LEFT) && $dDate->format('Y') === $year;
        });

        $dValue = $monthDetections->unique('child_id')->count();
        $nValue = 0;
        $tValue = 0;
        $bValue = 0;
        
        $uniqueWeighedChildren = $monthDetections->unique('child_id');
        foreach ($uniqueWeighedChildren as $current) {
            $previous = $allDetections->where('child_id', $current['child_id'])
                                      ->where('created_at', '<', $current['created_at'])
                                      ->sortByDesc('created_at')
                                      ->first();
                                      
            if (!$previous) {
                $bValue++;
            } else {
                if (isset($current['berat_badan']) && isset($previous['berat_badan'])) {
                    if ($current['berat_badan'] > $previous['berat_badan']) {
                        $nValue++;
                    } else {
                        $tValue++;
                    }
                }
            }
        }
        
        $oValue = max(0, $kValue - $dValue);

        return view('admin.ntob.show', compact('month', 'year', 'kValue', 'dValue', 'nValue', 'tValue', 'bValue', 'oValue', 'monthDetections'));
    }

    public function exportPdf($month, $year)
    {
        $dashResponse = $this->api->get('/admin/dashboard');
        $kValue = $dashResponse->successful() ? (int)$dashResponse->json('total_anak') : 0;

        $detResponse = $this->api->get('/admin/detections');
        $allDetections = $detResponse->successful() ? collect($detResponse->json()) : collect();

        $allDetections = $allDetections->filter(function($d) {
            return strtolower($d['added_by'] ?? 'orangtua') === 'kader';
        })->values();

        $monthDetections = $allDetections->filter(function($d) use ($month, $year) {
            $dDate = Carbon::parse($d['created_at']);
            return $dDate->format('m') === str_pad($month, 2, '0', STR_PAD_LEFT) && $dDate->format('Y') === $year;
        });

        $dValue = $monthDetections->unique('child_id')->count();
        $nValue = 0;
        $tValue = 0;
        $bValue = 0;
        
        $uniqueWeighedChildren = $monthDetections->unique('child_id');
        foreach ($uniqueWeighedChildren as $current) {
            $previous = $allDetections->where('child_id', $current['child_id'])
                                      ->where('created_at', '<', $current['created_at'])
                                      ->sortByDesc('created_at')
                                      ->first();
                                      
            if (!$previous) {
                $bValue++;
            } else {
                if (isset($current['berat_badan']) && isset($previous['berat_badan'])) {
                    if ($current['berat_badan'] > $previous['berat_badan']) {
                        $nValue++;
                    } else {
                        $tValue++;
                    }
                }
            }
        }
        
        $oValue = max(0, $kValue - $dValue);
        
        $dateObj = Carbon::parse($year . '-' . $month . '-01');
        $bulanNama = $dateObj->translatedFormat('F');

        $pdf = Pdf::loadView('admin.ntob.pdf', compact('month', 'year', 'bulanNama', 'kValue', 'dValue', 'nValue', 'tValue', 'bValue', 'oValue', 'monthDetections'))
            ->setPaper('a4', 'landscape');
            
        return $pdf->stream("Laporan_NTOB_{$bulanNama}_{$year}.pdf");
    }
}
