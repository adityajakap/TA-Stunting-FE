@extends('layouts.app')

@section('title', 'Tahapan Perkembangan')

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
</style>

<div class="container px-0">
    <div class="card shadow-sm">
        <div class="card-body">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4" style="max-width: 1280px; margin: 0 auto; ">
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <x-back-button />
                    <h1 class="main-title mb-0" style="color: #005f77; font-size: 1.75rem;">Tahapan Perkembangan</h1>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" onclick="document.getElementById('filterModal').classList.remove('hidden')" class="btn btn-outline-secondary" style="border-radius: 0.5rem;">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('orangtua.tahapan_perkembangan.create') }}"
                        class="btn text-white"
                        style="background-color: #005f77;">+ Tambah Pencapaian
                    </a>    
                </div>
            </div>

            {{-- MODAL FILTER --}}
            <div id="filterModal" class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-10 z-50 flex items-center justify-center">
                <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
                    <form method="GET" action="{{ route('orangtua.tahapan_perkembangan.index') }}">
                        <div class="mb-4 font-semibold text-sky-900">Filter Kategori</div>
                        <div class="flex flex-wrap gap-x-4 gap-y-2 mb-6">
                            @foreach($kategoris as $kategori)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="kategori[]" value="{{ $kategori->id }}"
                                        class="form-checkbox accent-sky-600"
                                        {{ in_array($kategori->id, (array) $kategoriIds) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sky-800">{{ $kategori->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="submit" class="px-4 py-2 rounded text-white" style="background-color: #005f77;">Terapkan</button>
                            <a href="{{ route('orangtua.tahapan_perkembangan.index') }}" class="px-4 py-2 rounded border border-gray-300 text-gray-700 bg-gray-100">Reset</a>
                            <button type="button" onclick="document.getElementById('filterModal').classList.add('hidden')" class="px-4 py-2 rounded border border-gray-300 text-gray-700 bg-gray-100">Tutup</button>
                        </div>
                    </form>
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
                                                <strong class="d-block mt-2">Catatan Orang Tua:</strong>
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
