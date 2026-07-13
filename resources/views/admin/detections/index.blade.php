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

    .action-buttons {
        display: flex;
        gap: 0.5rem;
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

    /* Rounded corners for table inside card */
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

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .modal-content {
        background-color: white;
        padding: 2rem;
        border-radius: 8px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .hidden {
        display: none;
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

        .action-buttons {
            width: 100% !important;
            display: flex !important;
            gap: 8px !important;
            margin-left: 0 !important;
        }

        .action-buttons a {
            flex: 1 !important;
            text-align: center !important;
            margin-left: 0 !important;
            padding: 8px 12px !important;
            font-size: 0.85rem !important;
            white-space: nowrap !important;
        }
    }

    @media (max-width: 767.98px) {
        .table thead th, .table tbody td {
            font-size: 0.8rem !important;
            padding: 0.5rem 0.6rem !important;
        }

        .table thead th:nth-child(1), .table tbody td:nth-child(1) {
            min-width: 150px !important;
        }
        .table thead th:nth-child(2), .table tbody td:nth-child(2) {
            min-width: 150px !important;
        }
        .table thead th:nth-child(4), .table tbody td:nth-child(4),
        .table thead th:nth-child(9), .table tbody td:nth-child(9) {
            min-width: 120px !important;
            white-space: nowrap !important;
        }
    }
</style>

<div class="card-wrapper">

    {{-- Header Judul dan Tombol --}}
    <div class="main-header" style="margin-top: 0; margin-bottom: 1.5rem;">
        <div style="display:flex; align-items:center; gap:0.5rem;">
            <x-back-button />
            <h1 class="main-title">Data Deteksi Stunting</h1>
        </div>
        <div class="action-buttons">
            <a href="{{ route('admin.detections.create') }}" class="btn btn-primary ms-2" style="background-color:#005f77; border:none;">Tambah Deteksi</a>
        </div>
    </div>

    {{-- Tabel Data Perbulan --}}
    <div class="card mb-5">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Kader</th>
                            <th class="text-center">Bulan</th>
                            <th class="text-center">Tahun</th>
                            <th class="text-center">Jumlah Data</th>
                            <th class="text-center">Tanggal Pelaksanaan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($groupedData as $index => $data)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $data['nama_kader'] ?? 'Kader' }}</td>
                                <td>{{ $data['monthName'] }}</td>
                                <td>{{ $data['year'] }}</td>
                                <td>{{ $data['count'] }}</td>
                                <td>{{ $data['tanggal_pelaksanaan'] ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.detections.show', ['month' => $data['month'], 'year' => $data['year']]) }}" class="btn btn-primary btn-sm text-white" style="background-color: #005f77; border: none; font-weight: 600; border-radius: 6px; padding: 6px 16px;">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data deteksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>



{{-- No filter/search for admin detections --}}
@endsection
