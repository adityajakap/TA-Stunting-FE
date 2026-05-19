<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Perkembangan Anak</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
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
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 4px;
        }
        .category-title {
            color: #005f77;
            border-bottom: 1px solid #ccc;
            margin-top: 20px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            font-size: 16px;
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
            background-color: #f2f2f2;
            text-align: left;
            font-weight: bold;
        }
        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
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
            font-size: 11px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Pemantauan Perkembangan Anak</h1>
        <p style="margin: 0;">Dokumen ini merupakan catatan pemantauan perkembangan dan bukan diagnosis medis.</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="20%"><strong>Nama Anak</strong></td>
            <td width="30%">: {{ $child->nama_lengkap_anak }}</td>
            <td width="20%"><strong>Nama Orang Tua</strong></td>
            <td width="30%">: {{ $child->user->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Lahir</strong></td>
            <td>: {{ \Carbon\Carbon::parse($child->tanggal_lahir)->format('d M Y') }}</td>
            <td><strong>Usia Saat Ini</strong></td>
            <td>: {{ $child->umur_bulan }} bulan</td>
        </tr>
    </table>

    @forelse($groupedData as $kategori => $items)
        <h3 class="category-title">Kategori: {{ $kategori }}</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Nama Tahapan</th>
                    <th width="15%">Tanggal Pencapaian</th>
                    <th width="20%">Status Evaluasi</th>
                    <th width="30%">Catatan / Rekomendasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                    <tr>
                        <td align="center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->tahapan->nama_tahapan }}</strong><br>
                            <span style="font-size: 10px; color: #555;">Ideal: {{ $item->tahapan->umur_minimal_bulan }} - {{ $item->tahapan->umur_maksimal_bulan }} bln</span>
                        </td>
                        <td>
                            @if($item->achieved_data)
                                {{ \Carbon\Carbon::parse($item->achieved_data->tanggal_pencapaian)->format('d/m/Y') }}
                            @else
                                <span style="color: #999;"><i>Belum dicatat</i></span>
                            @endif
                        </td>
                        <td>
                            @php
                                $bg = 'badge-secondary';
                                if (str_contains($item->status_detail['badge'], 'success')) $bg = 'badge-success';
                                elseif (str_contains($item->status_detail['badge'], 'warning')) $bg = 'badge-warning';
                                elseif (str_contains($item->status_detail['badge'], 'danger')) $bg = 'badge-danger';
                                elseif (str_contains($item->status_detail['badge'], 'info')) $bg = 'badge-info';
                                elseif (str_contains($item->status_detail['badge'], 'primary')) $bg = 'badge-primary';
                            @endphp
                            <span class="badge {{ $bg }}">{{ $item->status_detail['label'] }}</span>
                        </td>
                        <td style="font-size: 10px;">
                            {{ $item->status_detail['rekomendasi'] }}
                            @if($item->achieved_data && $item->achieved_data->catatan)
                                <br><br><strong>Catatan:</strong> {{ $item->achieved_data->catatan }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <p style="text-align: center; color: #777;">Belum ada data pencapaian tahapan perkembangan.</p>
    @endforelse

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}
    </div>

</body>
</html>
