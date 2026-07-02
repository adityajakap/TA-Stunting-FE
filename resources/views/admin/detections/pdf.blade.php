<?php
/**
 * @var \Illuminate\Support\Collection|\App\Models\Detection[] $semua
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Deteksi Stunting</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
            margin: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #005f77;
            padding-bottom: 12px;
        }

        .header h1 {
            color: #005f77;
            margin: 0 0 5px 0;
            font-size: 22px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header p {
            margin: 3px 0;
            font-size: 11px;
            color: #555;
        }

        /* Combined Stats Section */
        .stats-wrapper {
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
            background-color: #fff;
        }

        .stats-header {
            background-color: #005f77;
            color: #fff;
            padding: 8px 15px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Table Design */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
        }

        table.data-table th, table.data-table td {
            border: 1px solid #e5e7eb;
            padding: 8px 6px;
            vertical-align: middle;
            font-size: 10px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        table.data-table th {
            background-color: #005f77;
            color: #ffffff;
            font-weight: 600;
            text-align: center;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.5px;
        }

        table.data-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        table.data-table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        table.data-table tbody td {
            color: #374151;
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }

        /* Status Badges */
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success { background-color: #dcfce7; color: #166534; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }

        .footer {
            margin-top: 30px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            text-align: right;
            font-size: 9px;
            color: #9ca3af;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN DATA DETEKSI STUNTING</h1>
        <p>Sistem Monitoring Stunting Anak</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM YYYY') }} pukul {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('HH:mm') }} WIB</p>
    </div>

    {{-- Ringkasan Laporan Keseluruhan --}}
    <div class="stats-wrapper" style="margin-bottom: 25px;">
        <!-- Header -->
        <div class="stats-header">Ringkasan Laporan Bulan Ini</div>
        
        <!-- 3 Columns Data -->
        <table style="width: 100%; border-collapse: collapse; margin: 0;">
            <tr>
                <!-- Column 1: Deteksi -->
                <td style="width: 33.33%; padding: 12px 15px; vertical-align: top; border-right: 1px solid #e5e7eb;">
                    <div style="color: #005f77; font-weight: bold; font-size: 10px; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px;">STATISTIK DETEKSI</div>
                    <table style="width: 100%; font-size: 10px; border: none;">
                        <tr><td style="padding: 4px 0; border: none;">Total Data</td><td style="text-align: right; font-weight: bold; border: none;">{{ $semua->count() }}</td></tr>
                        <tr><td style="padding: 4px 0; border: none;">Kondisi Normal</td><td style="text-align: right; font-weight: bold; color: #15803d; border: none;">{{ $semua->where('status', 'Normal')->count() }}</td></tr>
                        <tr><td style="padding: 4px 0; border: none;">Indikasi Stunting</td><td style="text-align: right; font-weight: bold; color: #b91c1c; border: none;">{{ $semua->where('status', 'Stunting')->count() }}</td></tr>
                    </table>
                </td>

                <!-- Column 2: SKDN -->
                <td style="width: 33.33%; padding: 12px 15px; vertical-align: top; border-right: 1px solid #e5e7eb;">
                    <div style="color: #0369a1; font-weight: bold; font-size: 10px; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px;">DATA SKDN</div>
                    <table style="width: 100%; font-size: 10px; border: none;">
                        <tr><td style="padding: 4px 0; border: none;">Balita di Wilayah (S)</td><td style="text-align: right; font-weight: bold; border: none;">{{ isset($sValue) ? $sValue : 0 }}</td></tr>
                        <tr><td style="padding: 4px 0; border: none;">Balita Terdaftar (K)</td><td style="text-align: right; font-weight: bold; border: none;">{{ isset($kValue) ? $kValue : 0 }}</td></tr>
                        <tr><td style="padding: 4px 0; border: none;">Datang Ditimbang (D)</td><td style="text-align: right; font-weight: bold; border: none;">{{ isset($dValue) ? $dValue : 0 }}</td></tr>
                        <tr><td style="padding: 4px 0; border: none;">Berat Badan Naik (N)</td><td style="text-align: right; font-weight: bold; color: #15803d; border: none;">{{ isset($nValue) ? $nValue : 0 }}</td></tr>
                    </table>
                </td>

                <!-- Column 3: NTOB -->
                <td style="width: 33.33%; padding: 12px 15px; vertical-align: top;">
                    <div style="color: #8b5cf6; font-weight: bold; font-size: 10px; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px;">DATA NTOB</div>
                    <table style="width: 100%; font-size: 10px; border: none;">
                        <tr><td style="padding: 4px 0; border: none;">Naik (N)</td><td style="text-align: right; font-weight: bold; color: #15803d; border: none;">{{ isset($nValue) ? $nValue : 0 }}</td></tr>
                        <tr><td style="padding: 4px 0; border: none;">Turun / Tetap (T)</td><td style="text-align: right; font-weight: bold; color: #b91c1c; border: none;">{{ isset($tValue) ? $tValue : 0 }}</td></tr>
                        <tr><td style="padding: 4px 0; border: none;">Tidak Ditimbang (O)</td><td style="text-align: right; font-weight: bold; color: #f59e0b; border: none;">{{ isset($oValue) ? $oValue : 0 }}</td></tr>
                        <tr><td style="padding: 4px 0; border: none;">Baru Pertama (B)</td><td style="text-align: right; font-weight: bold; color: #0369a1; border: none;">{{ isset($bValue) ? $bValue : 0 }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Footer / Coverages -->
        <div style="background-color: #f9fafb; border-top: 1px solid #e5e7eb; padding: 12px 15px;">
            <table style="width: 100%; font-size: 10px; border-collapse: collapse; border: none;">
                <tr>
                    <td style="width: 33.33%; text-align: center; border: none; border-right: 1px dashed #cbd5e1;">
                        <div style="font-size: 15px; font-weight: bold; color: #111827; margin-bottom: 2px;">{{ (isset($sValue) && $sValue > 0) ? round(($kValue / $sValue) * 100, 1) : 0 }}%</div>
                        <div style="color: #6b7280;">K/S = Cakupan balita yang memiliki KMS</div>
                    </td>
                    <td style="width: 33.33%; text-align: center; border: none; border-right: 1px dashed #cbd5e1;">
                        <div style="font-size: 15px; font-weight: bold; color: #111827; margin-bottom: 2px;">{{ (isset($sValue) && $sValue > 0) ? round(($dValue / $sValue) * 100, 1) : 0 }}%</div>
                        <div style="color: #6b7280;">D/S = Cakupan balita yang ditimbang</div>
                    </td>
                    <td style="width: 33.33%; text-align: center; border: none;">
                        <div style="font-size: 15px; font-weight: bold; color: #15803d; margin-bottom: 2px;">{{ (isset($dValue) && $dValue > 0) ? round(($nValue / $dValue) * 100, 1) : 0 }}%</div>
                        <div style="color: #6b7280;">N/D = Cakupan balita yang timbangannya naik</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Tabel Data --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 18%;">Nama Orang Tua</th>
                <th style="width: 18%;">Nama Anak</th>
                <th style="width: 8%;">Umur (bln)</th>
                <th style="width: 6%;">JK</th>
                <th style="width: 8%;">BB (kg)</th>
                <th style="width: 8%;">TB (cm)</th>
                <th style="width: 8%;">Z-Score</th>
                <th style="width: 11%;">Status</th>
                <th style="width: 10%;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($semua as $index => $d)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left">{{ $d->child->user->nama_lengkap ?? '-' }}</td>
                    <td class="text-left">{{ $d->child->nama_lengkap_anak ?? '-' }}</td>
                    <td class="text-center">{{ $d->umur }}</td>
                    <td class="text-center">{{ $d->jenis_kelamin }}</td>
                    <td class="text-center">{{ $d->berat_badan }}</td>
                    <td class="text-center">{{ $d->tinggi_badan }}</td>
                    <td class="text-center">{{ $d->z_score }}</td>
                    <td class="text-center">
                        <span class="badge {{ $d->status == 'Stunting' ? 'badge-danger' : 'badge-success' }}">
                            {{ $d->status == 'Tinggi' ? 'Normal' : $d->status }}
                        </span>
                    </td>
                    <td class="text-center">{{ $d->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 15px 0; color: #777;">Belum ada data deteksi stunting.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan digenerate secara otomatis dari Sistem Monitoring Stunting Anak</p>
        <p>Waktu Cetak: {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM YYYY [pukul] HH:mm:ss') }} WIB</p>
    </div>

</body>
</html>
