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
    .table thead th {
        background-color: #e5e5e5;
        padding: 1rem;
        font-weight: 600;
        color: #000;
        border-bottom: none;
    }
    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #eee;
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

    <div class="table-responsive" style="border-radius: 1rem; overflow: hidden; border: 1px solid #e5e7eb; background: #fff;">
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
@endsection
