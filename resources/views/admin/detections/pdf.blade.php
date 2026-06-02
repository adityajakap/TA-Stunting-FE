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
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
            margin: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #005f77;
            padding-bottom: 10px;
        }

        .header h1 {
            color: #005f77;
            margin: 0 0 5px 0;
            font-size: 20px;
            font-weight: bold;
        }

        .header p {
            margin: 2px 0;
            font-size: 11px;
            color: #555;
        }

        /* Stats Section - elegant left-bordered box */
        .stats-container {
            background-color: #f0fdfa;
            border-left: 4px solid #005f77;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .stats-title {
            color: #005f77;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .stats-row {
            width: 100%;
        }

        .stats-col {
            width: 33.33%;
            display: inline-block;
            vertical-align: top;
        }

        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .stat-label {
            font-size: 9px;
            color: #666;
            margin-top: 2px;
        }

        /* Table Design matching tahapan perkembangan */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            table-layout: fixed;
        }

        table.data-table th, table.data-table td {
            border: 1px solid #ddd;
            padding: 8px 6px;
            vertical-align: middle;
            font-size: 10px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        table.data-table th {
            background-color: #005f77;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        table.data-table tbody td {
            color: #374151;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        /* Status Badges */
        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
            text-align: center;
            text-transform: uppercase;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #15803d;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            text-align: right;
            font-size: 9px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN DATA DETEKSI STUNTING</h1>
        <p>Sistem Monitoring Stunting Anak</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM YYYY') }} pukul {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('HH:mm') }} WIB</p>
    </div>

    {{-- Statistik Deteksi --}}
    <div class="stats-container">
        <div class="stats-title">Ringkasan Statistik Deteksi</div>
        <table style="width: 100%; border: none; margin: 0;">
            <tr style="border: none;">
                <td style="width: 33.33%; border: none; padding: 0;">
                    <div class="stat-value">{{ $semua->count() }}</div>
                    <div class="stat-label">Total Data Deteksi</div>
                </td>
                <td style="width: 33.33%; border: none; padding: 0;">
                    <div class="stat-value" style="color: #b91c1c;">{{ $semua->where('status', 'Stunting')->count() }}</div>
                    <div class="stat-label">Terindikasi Stunting</div>
                </td>
                <td style="width: 33.33%; border: none; padding: 0;">
                    <div class="stat-value" style="color: #15803d;">{{ $semua->where('status', 'Normal')->count() }}</div>
                    <div class="stat-label">Kondisi Normal</div>
                </td>
            </tr>
        </table>
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
