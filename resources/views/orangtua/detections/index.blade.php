@extends('layouts.app')

@section('content')
<style>
    .main-header { display: flex; justify-content: space-between; align-items: center; max-width: 1280px; margin: 2rem auto 1rem; padding: 0 1rem; }
    .main-title { color: #005f77; font-size: 2rem; margin: 0; }
    .action-buttons { display: flex; gap: 0.5rem; }
    .card-wrapper { max-width: 1280px; margin: 0 auto; padding: 0 1rem 2rem; }
    .card { background-color: #ffffff; border-radius: 1rem; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); }
    .card-body { padding: 0; }
    .table { width: 100%; border-collapse: separate; border-spacing: 0; margin-bottom: 0; background-color: white; }
    .table thead th { background-color: #f9fafb; padding: 0.75rem 1rem; font-weight: 600; color: #1f2937; text-align: center; border: 1px solid #e5e7eb; font-size: 0.95rem; }
    .table tbody tr { border-bottom: 1px solid #e5e7eb; }
    .table tbody tr:hover { background-color: #f9fafb; }
    .table tbody td { padding: 0.75rem 1rem; color: #374151; border: 1px solid #e5e7eb; font-size: 0.95rem; text-align: center; }
    .table thead th:first-child { border-top-left-radius: 0.75rem; }
    .table thead th:last-child { border-top-right-radius: 0.75rem; }
    .table tbody tr:last-child td:first-child { border-bottom-left-radius: 0.75rem; }
    .table tbody tr:last-child td:last-child { border-bottom-right-radius: 0.75rem; }
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%; display: block; }
    @media (max-width: 991.98px) {
        .main-header { flex-direction: column !important; align-items: stretch !important; gap: 12px !important; margin-top: 0.5rem !important; margin-bottom: 1rem !important; }
        .action-buttons { width: 100% !important; display: flex !important; gap: 8px !important; margin-left: 0 !important; }
        .action-buttons a { flex: 1 !important; text-align: center !important; margin-left: 0 !important; padding: 8px 12px !important; font-size: 0.85rem !important; white-space: nowrap !important; }
    }
    @media (max-width: 767.98px) {
        .table thead th, .table tbody td { font-size: 0.8rem !important; padding: 0.5rem 0.6rem !important; }
    }
</style>

<div class="card-wrapper">
    <div class="main-header" style="margin-top: 0; margin-bottom: 1.5rem;">
        <div style="display:flex; align-items:center; gap:0.5rem;">
            <x-back-button />
            <h1 class="main-title">Data Deteksi Stunting</h1>
        </div>
        <div class="action-buttons">
            <a href="{{ route('orangtua.detections.create') }}" class="btn btn-primary ms-2" style="background-color:#005f77; border:none;">Deteksi Baru</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="padding: 1rem; margin-bottom: 1rem; color: #0f5132; background-color: #d1e7dd; border: 1px solid #badbcc; border-radius: 0.375rem;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-5">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Bulan</th>
                            <th class="text-center">Tahun</th>
                            <th class="text-center">Jumlah Data</th>
                            <th class="text-center">Tanggal Deteksi Terakhir</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($groupedData as $index => $data)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $data['monthName'] }}</td>
                                <td>{{ $data['year'] }}</td>
                                <td>{{ $data['count'] }}</td>
                                <td>{{ $data['tanggal_pelaksanaan'] ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('orangtua.detections.show', ['month' => $data['month'], 'year' => $data['year']]) }}" class="btn btn-primary btn-sm text-white" style="background-color: #005f77; border: none; font-weight: 600; border-radius: 6px; padding: 6px 16px;">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada riwayat deteksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
