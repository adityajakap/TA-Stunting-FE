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
        <div class="controls-wrapper">
            @if(isset($month) && isset($year))
                <a href="{{ route('admin.detections.create') }}" class="btn btn-primary" style="background-color:#005f77; border:none; border-radius:6px; font-weight:600; padding:8px 16px; color:white; text-decoration:none;">
                    Tambah Deteksi
                </a>
            @endif
        </div>
    </div>

    <!-- Ringkasan Laporan Deteksi -->
    <div style="border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 2rem; background: #fff; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div style="background-color: #005f77; color: #fff; padding: 10px 15px; font-weight: 700; font-size: 13px; text-transform: uppercase;">
            RINGKASAN LAPORAN
        </div>
        <div style="padding: 15px;">
            <div style="color: #8b5cf6; font-weight: 700; font-size: 12px; margin-bottom: 10px; border-bottom: 1px solid #e5e7eb; padding-bottom: 5px;">DATA DETEKSI</div>
            
            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                <div style="flex: 1; min-width: 300px;">
                    <table style="width: 100%; font-size: 13px; border: none;">
                        <tr>
                            <td style="padding: 6px 0; border: none; width: 45%;">Nama Kader Posyandu Yang Bertanggung Jawab</td>
                            <td style="text-align: left; font-weight: bold; color: #374151; border: none;">: {{ $kaderName }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; border: none;">Tempat Pelaksanaan</td>
                            <td style="text-align: left; font-weight: bold; color: #374151; border: none;">: Posyandu Nusa Indah 1</td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; border: none;">Tanggal Pelaksanaan</td>
                            <td style="text-align: left; font-weight: bold; color: #374151; border: none;">: {{ $tanggalPelaksanaan }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; border: none;">Bulan</td>
                            <td style="text-align: left; font-weight: bold; color: #374151; border: none;">: {{ $monthName }} {{ $year }}</td>
                        </tr>
                    </table>
                </div>
                
                <div style="flex: 1; min-width: 300px; border-left: 1px dashed #e5e7eb; padding-left: 20px;">
                    <table style="width: 100%; font-size: 13px; border: none;">
                        <tr>
                            <td style="padding: 6px 0; border: none; width: 85%;">Seluruh balita di wilayah kerja (S)</td>
                            <td style="text-align: right; font-weight: bold; color: #10b981; border: none;">{{ $sValue ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; border: none;">Balita yang memiliki KMS (K)</td>
                            <td style="text-align: right; font-weight: bold; color: #3b82f6; border: none;">{{ $kValue ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; border: none;">Balita yang datang dan ditimbang (D)</td>
                            <td style="text-align: right; font-weight: bold; color: #f59e0b; border: none;">{{ $dValue ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; border: none;">Balita yang berat badannya naik (N)</td>
                            <td style="text-align: right; font-weight: bold; color: #ef4444; border: none;">{{ $nValue ?? 0 }}</td>
                        </tr>
                    </table>
                </div>
            </div>
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
