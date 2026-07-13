@extends('layouts.app')

@section('content')
<style>
    .main-header {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        max-width: 1280px;
        margin: 2rem auto 1rem;
        padding: 0 1rem;
    }
    .main-title {
        color: #005f77;
        font-size: 2rem;
        margin: 0;
        margin-left: 1rem;
    }
    .back-btn {
        color: #000;
        font-size: 1.5rem;
        text-decoration: none;
    }
    .card-wrapper {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1rem 2rem;
    }
    .controls-wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .btn-cetak {
        background-color: #005f77;
        color: #fff;
        border-radius: 8px;
        padding: 0.5rem 1.5rem;
        border: none;
        font-weight: 600;
        text-decoration: none;
    }
    .card {
        background-color: #ffffff;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .card-body {
        padding: 0;
    }
    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
        background-color: white;
    }
    .table thead th {
        background-color: #f9fafb;
        padding: 0.75rem 1rem;
        font-weight: 600;
        color: #1f2937;
        text-align: left;
        border: 1px solid #e5e7eb;
        font-size: 0.95rem;
    }
    .table tbody tr {
        border-bottom: 1px solid #e5e7eb;
    }
    .table tbody tr:hover {
        background-color: #f9fafb;
    }
    .table tbody td {
        padding: 0.75rem 1rem;
        color: #374151;
        border: 1px solid #e5e7eb;
        font-size: 0.95rem;
        vertical-align: middle;
    }
    .table thead th:first-child {
        border-top-left-radius: 0.75rem;
    }
    .table thead th:last-child {
        border-top-right-radius: 0.75rem;
    }
    .table tbody tr:last-child td:first-child {
        border-bottom-left-radius: 0.75rem;
    }
    .table tbody tr:last-child td:last-child {
        border-bottom-right-radius: 0.75rem;
    }
    .badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 0.375rem;
        font-weight: 600;
    }
</style>

<div class="main-header">
    <a href="{{ route('admin.ntob.index') }}" class="back-btn"><i class="fas fa-chevron-left"></i></a>
    <h1 class="main-title">Data NTOB Bulan {{ \Carbon\Carbon::create()->month((int)$month)->translatedFormat('F') }} {{ $year }}</h1>
</div>

<div class="card-wrapper">
    <div class="controls-wrapper">
        <a href="{{ route('admin.ntob.pdf', ['month' => $month, 'year' => $year]) }}" class="btn-cetak" target="_blank">Cetak NTOB</a>
    </div>

    <!-- Ringkasan Laporan NTOB -->
    <div style="border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 2rem; background: #fff; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div style="background-color: #005f77; color: #fff; padding: 10px 15px; font-weight: 700; font-size: 13px; text-transform: uppercase;">
            RINGKASAN LAPORAN
        </div>
        <div style="padding: 15px;">
            <div style="color: #8b5cf6; font-weight: 700; font-size: 12px; margin-bottom: 10px; border-bottom: 1px solid #e5e7eb; padding-bottom: 5px;">DATA NTOB</div>
            <table style="width: 100%; font-size: 13px; border: none;">
                <tr>
                    <td style="padding: 6px 0; border: none;">Naik (N)</td>
                    <td style="text-align: right; font-weight: bold; color: #15803d; border: none;">{{ $nValue }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; border: none;">Turun / Tetap (T)</td>
                    <td style="text-align: right; font-weight: bold; color: #b91c1c; border: none;">{{ $tValue }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; border: none;">Tidak Ditimbang (O)</td>
                    <td style="text-align: right; font-weight: bold; color: #f59e0b; border: none;">{{ $oValue }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; border: none;">Baru Pertama (B)</td>
                    <td style="text-align: right; font-weight: bold; color: #0369a1; border: none;">{{ $bValue }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Umur (bulan)</th>
                    <th>Jenis kelamin</th>
                    <th>Berat (kg)</th>
                    <th>Z-Score</th>
                    <th>Status</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($monthDetections as $d)
                <tr>
                    <td>{{ $d['child']['nama_lengkap_anak'] ?? '-' }}</td>
                    <td>{{ $d['umur'] ?? '-' }}</td>
                    <td>{{ ($d['jenis_kelamin'] ?? '') == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>{{ $d['berat_badan'] ?? '-' }}</td>
                    <td>{{ $d['z_score'] ?? '-' }}</td>
                    <td>
                        <span class="badge {{ ($d['status'] ?? '') == 'Stunting' ? 'bg-danger' : 'bg-success' }}">
                            {{ ($d['status'] ?? '') == 'Tinggi' ? 'Normal' : ($d['status'] ?? '-') }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($d['created_at'])->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data anak pada bulan ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
        </div>
    </div>
</div>
@endsection
