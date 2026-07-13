@extends('layouts.app')

@section('content')
<style>
    .main-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1280px;
        margin: 2rem auto 1rem;
        padding: 0 1rem;
    }

    .main-title {
        color: #005f77;
        font-size: 2rem;
        margin: 0;
    }

    .card-wrapper {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1rem 2rem;
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

    .text-center {
        text-align: center;
    }

    .badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 0.375rem;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: capitalize;
    }

    .bg-success {
        background-color: #10b981;
        color: white;
    }

    .bg-danger {
        background-color: #ef4444;
        color: white;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
        display: block;
    }

    @media (max-width: 991.98px) {
        .main-header {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 12px !important;
            margin-top: 0.5rem !important;
            margin-bottom: 1rem !important;
        }
    }

    @media (max-width: 767.98px) {
        .table thead th, .table tbody td {
            font-size: 0.8rem !important;
            padding: 0.5rem 0.6rem !important;
        }
    }
</style>

<div class="card-wrapper">
    <div class="main-header" style="margin-top: 0; margin-bottom: 1.5rem;">
        <div style="display:flex; align-items:center; gap:0.5rem;">
            <x-back-button :url="route('admin.detections.index')" />
            <h1 class="main-title">Data Deteksi Bulan {{ $monthName }}</h1>
        </div>
    </div>

    @php
        $riwayatKader = collect($semua)->filter(function($d) {
            return (is_object($d) ? ($d->added_by ?? 'orangtua') : ($d['added_by'] ?? 'orangtua')) === 'kader';
        });
        $riwayatOrangtua = collect($semua)->filter(function($d) {
            return (is_object($d) ? ($d->added_by ?? 'orangtua') : ($d['added_by'] ?? 'orangtua')) === 'orangtua';
        });
    @endphp

    {{-- Tabel Riwayat Deteksi Kader --}}
    <h3 style="color: #005f77; margin-bottom: 1rem; font-size: 1.5rem; font-weight: 700;">Riwayat Deteksi Kader</h3>
    <div class="card mb-5">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Orang Tua</th>
                            <th>Nama Anak</th>
                            <th>Umur (bln)</th>
                            <th>J. Kelamin</th>
                            <th>Berat (kg)</th>
                            <th>Tinggi (cm)</th>
                            <th>Z-Score</th>
                            <th>Status</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayatKader as $d)
                            <tr>
                                <td>{{ $d->child?->user?->nama_lengkap ?? '-' }}</td>
                                <td>{{ $d->child?->nama_lengkap_anak ?? '-' }}</td>
                                <td>{{ $d->umur }}</td>
                                <td>{{ $d->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td>{{ $d->berat_badan }}</td>
                                <td>{{ $d->tinggi_badan }}</td>
                                <td>{{ $d->z_score }}</td>
                                <td>
                                    <span class="badge {{ $d->status == 'Stunting' ? 'bg-danger' : 'bg-success' }}">
                                        {{ $d->status == 'Tinggi' ? 'Normal' : $d->status }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($d->created_at)->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Belum ada data deteksi dari Kader pada bulan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tabel Riwayat Deteksi Orang Tua --}}
    <h3 style="color: #005f77; margin-bottom: 1rem; font-size: 1.5rem; font-weight: 700;">Riwayat Deteksi Orang Tua</h3>
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Orang Tua</th>
                            <th>Nama Anak</th>
                            <th>Umur (bln)</th>
                            <th>J. Kelamin</th>
                            <th>Berat (kg)</th>
                            <th>Tinggi (cm)</th>
                            <th>Z-Score</th>
                            <th>Status</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayatOrangtua as $d)
                            <tr>
                                <td>{{ $d->child?->user?->nama_lengkap ?? '-' }}</td>
                                <td>{{ $d->child?->nama_lengkap_anak ?? '-' }}</td>
                                <td>{{ $d->umur }}</td>
                                <td>{{ $d->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td>{{ $d->berat_badan }}</td>
                                <td>{{ $d->tinggi_badan }}</td>
                                <td>{{ $d->z_score }}</td>
                                <td>
                                    <span class="badge {{ $d->status == 'Stunting' ? 'bg-danger' : 'bg-success' }}">
                                        {{ $d->status == 'Tinggi' ? 'Normal' : $d->status }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($d->created_at)->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Belum ada data deteksi dari Orang Tua pada bulan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
