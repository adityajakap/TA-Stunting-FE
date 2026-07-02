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
                    <button type="button" class="btn-primary-custom text-white" style="background-color: #005f77; border: none; font-weight: 600; white-space: nowrap; display: inline-flex; align-items: center;" data-bs-toggle="modal" data-bs-target="#exportPdfModal"><i class="fas fa-file-pdf" style="margin-right: 5px;"></i> Export PDF</button>
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



<!-- Modal Pilih Bulan (Satu Anak) -->
<div class="modal fade" id="exportPdfModal" tabindex="-1" aria-labelledby="exportPdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content" style="border-radius: 12px; border: none; background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.15); padding: 10px 15px;">
            <form action="{{ route('admin.perkembangan.children.pdf', $child->id ?? request()->route('user')) }}" method="GET" target="_blank">
                <div class="modal-body p-3">
                    <!-- Title "Pilih Bulan" in blue/teal -->
                    <h5 id="exportPdfModalLabel" style="color: #0b5d76; font-weight: 700; font-size: 1.15rem; margin-bottom: 1.25rem; text-align: left;">Pilih Bulan</h5>
                    
                    @if(isset($availableMonths) && $availableMonths->isEmpty())
                        <p class="text-center text-muted mb-0">Belum ada data perkembangan untuk diekspor.</p>
                    @else
                        <!-- Horizontal Checkboxes exactly side-by-side -->
                        <div class="d-flex flex-wrap align-items-center gap-3 mb-4" style="font-size: 0.95rem; justify-content: flex-start; padding-left: 2px;">
                            @foreach($availableMonths as $month)
                                <div class="form-check form-check-inline m-0 d-flex align-items-center" style="gap: 5px;">
                                    <input class="form-check-input month-checkbox" type="checkbox" name="months[]" value="{{ $month['value'] }}" id="month-{{ $month['value'] }}" checked style="border-color: #0b5d76; cursor: pointer; width: 16px; height: 16px; margin: 0;">
                                    <label class="form-check-label" for="month-{{ $month['value'] }}" style="color: #0b5d76; font-weight: 500; cursor: pointer; padding: 0; line-height: 1.2;">
                                        {{ explode(' ', $month['label'])[0] }}
                                    </label>
                                </div>
                            @endforeach
                            
                            <div class="form-check form-check-inline m-0 d-flex align-items-center" style="gap: 5px;">
                                <input class="form-check-input" type="checkbox" id="select-all-months" checked style="border-color: #0b5d76; cursor: pointer; width: 16px; height: 16px; margin: 0;">
                                <label class="form-check-label" for="select-all-months" style="font-weight: 500; color: #0b5d76; cursor: pointer; padding: 0; line-height: 1.2;">
                                    Semua
                                </label>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Centered Buttons: Terapkan, Reset, Tutup -->
                    <div class="d-flex justify-content-center gap-2 mt-4 pt-1">
                        <button type="submit" class="btn text-white" style="background-color: #0b5d76; border-radius: 8px; padding: 6px 18px; font-weight: 600; font-size: 0.9rem; border: none;" {{ isset($availableMonths) && $availableMonths->isEmpty() ? 'disabled' : '' }}>Terapkan</button>
                        <button type="button" class="btn text-white" id="reset-months-btn" style="background-color: #0b5d76; border-radius: 8px; padding: 6px 18px; font-weight: 600; font-size: 0.9rem; border: none;" {{ isset($availableMonths) && $availableMonths->isEmpty() ? 'disabled' : '' }}>Reset</button>
                        <button type="button" class="btn text-white" data-bs-dismiss="modal" style="background-color: #0b5d76; border-radius: 8px; padding: 6px 18px; font-weight: 600; font-size: 0.9rem; border: none;">Tutup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select-all-months');
        const checkboxes = document.querySelectorAll('.month-checkbox');
        const resetBtn = document.getElementById('reset-months-btn');

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checkboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                });
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    const allChecked = Array.from(checkboxes).every(c => c.checked);
                    selectAll.checked = allChecked;
                });
            });
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                checkboxes.forEach(cb => cb.checked = false);
                if (selectAll) selectAll.checked = false;
            });
        }
    });
</script>

{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@endsection
