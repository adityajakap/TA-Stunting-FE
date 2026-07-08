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

<div class="main-header" style="justify-content: space-between;">
    <div style="display: flex; align-items: center;">
        <a href="{{ route('admin.dashboard') }}" class="back-btn"><i class="fas fa-chevron-left"></i></a>
        <h1 class="main-title">Data SKDN</h1>
    </div>
    <a href="{{ route('admin.skdn.grafik') }}" class="btn-lihat" style="border-radius: 8px;">Lihat Grafik</a>
</div>

<div class="card-wrapper">
    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Posyandu</th>
                        <th class="text-center">Bulan</th>
                        <th class="text-center">Tahun</th>
                        <th class="text-center">Tanggal Kegiatan</th>
                        <th class="text-center">S</th>
                        <th class="text-center">D</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groupedData as $row)
                        <tr>
                            <td class="text-center">{{ $row['no'] }}</td>
                            <td class="text-center">{{ $row['posyandu'] }}</td>
                            <td class="text-center">{{ $row['bulan_nama'] }}</td>
                            <td class="text-center">{{ $row['tahun'] }}</td>
                            <td class="text-center">{{ $row['tanggal_kegiatan'] }}</td>
                            <td class="text-center">{{ $row['s_value'] ?: '' }}</td>
                            <td class="text-center">{{ $row['d_value'] ?: '' }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.skdn.show', ['month' => $row['bulan'], 'year' => $row['tahun']]) }}" class="btn-lihat">Lihat Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data kegiatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
