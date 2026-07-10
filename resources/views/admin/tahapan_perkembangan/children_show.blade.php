@extends('layouts.app')

@section('title', 'Perkembangan - ' . $child->nama_lengkap_anak)

@section('content')
<style>
    .badge {
        display: inline-block;
        border-radius: 0.5rem;
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
        margin-right: 0.3rem;
        margin-bottom: 0.2rem;
        font-weight: 600;
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
        padding: 0.75rem;
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
        padding: 0.75rem;
        color: #374151;
        border: 1px solid #e5e7eb;
        font-size: 0.95rem;
        vertical-align: top;
    }

    .table thead th:first-child { border-top-left-radius: 0.75rem; }
    .table thead th:last-child { border-top-right-radius: 0.75rem; }
    .table tbody tr:last-child td:first-child { border-bottom-left-radius: 0.75rem; }
    .table tbody tr:last-child td:last-child { border-bottom-right-radius: 0.75rem; }

    .text-center { text-align: center; }
    .text-muted { color: #6b7280; }
    .table-responsive { overflow-x: auto; margin-bottom: 2rem; }
    
    .btn-primary-custom {
        background-color: #005f77;
        border: none;
        color: #fff;
        padding: 0.5rem 0.9rem;
        border-radius: 0.375rem;
        text-decoration: none;
        font-size: 0.95rem;
    }

    .btn-primary-custom:hover {
        background-color: #014f66;
    }

    @media (max-width: 767.98px) {
        .card-body {
            padding: 1rem !important;
        }

        .d-flex.justify-content-between.align-items-center.mb-4 {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 12px !important;
            margin-bottom: 1.25rem !important;
        }

        .d-flex.justify-content-between.align-items-center.mb-4 > div:first-child {
            width: 100% !important;
        }

        .d-flex.justify-content-between.align-items-center.mb-4 h1 {
            font-size: 1.4rem !important;
            line-height: 1.4 !important;
        }

        .action-buttons {
            width: 100% !important;
            display: flex !important;
            gap: 8px !important;
        }

        .action-buttons a {
            flex: 1 !important;
            text-align: center !important;
            font-size: 0.85rem !important;
            padding: 8px 12px !important;
            white-space: nowrap !important;
        }

        /* Table column min-widths for mobile horizontal scrolling */
        .table thead th:nth-child(1),
        .table tbody td:nth-child(1) {
            min-width: 180px !important;
        }

        .table thead th:nth-child(2),
        .table tbody td:nth-child(2) {
            min-width: 110px !important;
            white-space: nowrap !important;
        }

        .table thead th:nth-child(3),
        .table tbody td:nth-child(3) {
            min-width: 130px !important;
            white-space: nowrap !important;
        }

        .table thead th:nth-child(4),
        .table tbody td:nth-child(4) {
            min-width: 220px !important;
        }

        .table thead th, .table tbody td {
            padding: 0.6rem 0.5rem !important;
            font-size: 0.8rem !important;
        }
    }
</style>

<div class="card-wrapper container px-0">
    <div class="card shadow-sm">
        <div class="card-body">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <x-back-button :url="route('admin.perkembangan.children.index')" />
                    <h1 class="main-title mb-0" style="color: #005f77; font-size: 1.75rem;">Perkembangan: {{ $child->nama_lengkap_anak }}</h1>
                </div>
                <div class="action-buttons" style="display: flex; gap: 0.5rem; align-items: center;">
                    <a href="{{ route('admin.perkembangan.children.create', $child->id ?? request()->route('user')) }}" class="btn-primary-custom" style="display: inline-flex; align-items: center;">+ Tambah Pencapaian</a>
                </div>
            </div>

            {{-- UMUR ANAK INFO --}}
            <div class="mb-4 p-3 rounded" style="background-color: #f0fdfa; border-left: 4px solid #0d9488;">
                <strong>Informasi:</strong> Usia {{ $child->nama_lengkap_anak }} saat ini adalah <strong>{{ $child->umur_bulan }} bulan</strong>. Indikator di bawah ini merupakan alat pemantauan perkembangan dan bukan diagnosis medis.
            </div>

            {{-- TABEL BERDASARKAN KATEGORI --}}
            @forelse($groupedData as $kategori => $items)
                <h4 class="mt-4 mb-3" style="color: #005f77; border-bottom: 2px solid #e5e7eb; padding-bottom: 0.5rem;">
                    <i class="fas {{ $kategori == 'Motorik' ? 'fa-running' : ($kategori == 'Bahasa' ? 'fa-comments' : 'fa-tooth') }} me-2"></i> Kategori: {{ $kategori }}
                </h4>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 30%;">Nama Tahapan</th>
                                <th style="width: 15%;">Pencapaian</th>
                                <th style="width: 20%;">Status Evaluasi</th>
                                <th style="width: 35%;">Rekomendasi / Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->tahapan->nama_tahapan }}</strong><br>
                                        <small class="text-muted d-block mt-1">{{ $item->tahapan->deskripsi }}</small>
                                        <small class="text-muted d-block mt-1">Rentang ideal: {{ $item->tahapan->umur_minimal_bulan }} - {{ $item->tahapan->umur_maksimal_bulan }} bulan</small>
                                    </td>
                                    <td>
                                        @if($item->achieved_data)
                                            <span class="d-block font-weight-bold">{{ \Carbon\Carbon::parse($item->achieved_data->tanggal_pencapaian)->format('d M Y') }}</span>
                                        @else
                                            <span class="text-muted fst-italic">Belum dicatat</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $item->status_detail['badge'] }}">
                                            {{ $item->status_detail['label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="font-size: 0.85rem; line-height: 1.4;">
                                            <span class="d-block mb-1">{{ $item->status_detail['rekomendasi'] }}</span>
                                            @if($item->achieved_data && $item->achieved_data->catatan)
                                                <strong class="d-block mt-2">Catatan Admin/Orang Tua:</strong>
                                                <span class="text-muted">{{ $item->achieved_data->catatan }}</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <div class="text-center text-muted mt-5 mb-5">
                    <i class="fas fa-box-open fa-3x mb-3 text-light"></i>
                    <p>Tidak ada data tahapan perkembangan.</p>
                </div>
            @endforelse

        </div>
    </div>
</div>





{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@endsection
