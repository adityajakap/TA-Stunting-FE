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
    .card {
        background-color: #ffffff;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
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
    .btn-lihat {
        background-color: #005f77;
        color: #fff;
        border-radius: 20px;
        padding: 0.4rem 1.2rem;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
    }
</style>

<div class="main-header">
    <a href="{{ route('admin.dashboard') }}" class="back-btn"><i class="fas fa-chevron-left"></i></a>
    <h1 class="main-title">Data NTOB</h1>
</div>

<div class="card-wrapper">
    <div class="card mb-5">
        <div class="card-body">
            <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Posyandu</th>
                        <th>Bulan</th>
                        <th>Tanggal Kegiatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groupedData as $row)
                        <tr>
                            <td>{{ $row['no'] }}</td>
                            <td>{{ $row['posyandu'] }}</td>
                            <td>{{ $row['bulan_nama'] }}</td>
                            <td>{{ $row['tanggal_kegiatan'] }}</td>
                            <td>
                                <a href="{{ route('admin.ntob.show', ['month' => $row['bulan'], 'year' => $row['tahun']]) }}" class="btn-lihat">Lihat NTOB</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data kegiatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>
@endsection
