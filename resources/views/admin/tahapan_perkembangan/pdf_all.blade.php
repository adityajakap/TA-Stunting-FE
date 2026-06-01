<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Perkembangan Semua Anak</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
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
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 11px;
            color: #666;
        }
        .summary-card {
            background-color: #f0fdfa;
            border-left: 4px solid #005f77;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .summary-card p {
            margin: 0;
            font-size: 12px;
            color: #005f77;
            font-weight: bold;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }
        table.data-table th {
            background-color: #005f77;
            color: white;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
            text-align: center;
        }
        /* Status Colors */
        .badge-info { background-color: #e0f2fe; color: #0284c7; }
        .badge-success { background-color: #dcfce7; color: #15803d; }
        .badge-warning { background-color: #fef9c3; color: #a16207; }
        .badge-danger { background-color: #fee2e2; color: #b91c1c; }
        .badge-secondary { background-color: #f3f4f6; color: #374151; }
        .badge-primary { background-color: #dbeafe; color: #1d4ed8; }
        
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Perkembangan Anak</h1>
        <p>Aplikasi Pemantauan Stunting & Perkembangan Anak Terpadu (Admin Portal)</p>
    </div>

    <div class="summary-card">
        <p>Total Catatan Perkembangan: {{ $allAchievements->count() }} Data</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Nama Orang Tua</th>
                <th width="15%">Nama Anak</th>
                <th width="20%">Nama Indikator / Tahapan</th>
                <th width="10%">Kategori</th>
                <th width="12%">Tgl Pencapaian</th>
                <th width="13%">Status Evaluasi</th>
                <th width="10%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($allAchievements as $index => $item)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td><strong>{{ $item->parent_name }}</strong></td>
                    <td>{{ $item->child_name }}</td>
                    <td>{{ $item->tahapan_name }}</td>
                    <td>{{ $item->kategori }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pencapaian)->format('d/m/Y') }}</td>
                    <td>
                        @php
                            $bg = 'badge-secondary';
                            if (str_contains($item->status_badge, 'success')) $bg = 'badge-success';
                            elseif (str_contains($item->status_badge, 'warning')) $bg = 'badge-warning';
                            elseif (str_contains($item->status_badge, 'danger')) $bg = 'badge-danger';
                            elseif (str_contains($item->status_badge, 'info')) $bg = 'badge-info';
                            elseif (str_contains($item->status_badge, 'primary')) $bg = 'badge-primary';
                        @endphp
                        <span class="badge {{ $bg }}">{{ $item->status_label }}</span>
                    </td>
                    <td><small style="color: #666;">{{ $item->catatan }}</small></td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" align="center" style="color: #999; font-style: italic; padding: 20px;">Belum ada data pencapaian tahapan perkembangan untuk diekspor pada bulan yang dipilih.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}
    </div>

</body>
</html>
