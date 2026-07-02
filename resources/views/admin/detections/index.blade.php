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
            <button type="button" class="btn btn-primary ms-2 text-white" style="background-color:#005f77; border:none; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#exportPdfModal">Export PDF</button>
            <a href="{{ route('admin.detections.create') }}" class="btn btn-primary ms-2" style="background-color:#005f77; border:none;">Tambah Deteksi</a>
        </div>
    </div>

    {{-- Tabel dalam Card --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Orang Tua</th>
                            <th>Nama Anak</th>
                            <th>Umur (bulan)</th>
                            <th>Jenis Kelamin</th>
                            <th>Berat Badan (kg)</th>
                            <th>Tinggi Badan (cm)</th>
                            <th>Z-Score</th>
                            <th>Status</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($semua as $d)
                            <tr>
                                <td>{{ $d->child->user->nama_lengkap ?? '-' }}</td>
                                <td>{{ $d->child->nama_lengkap_anak ?? '-' }}</td>
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
                                <td>{{ $d->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Belum ada data deteksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Modal Pilih Bulan -->
<div class="modal fade" id="exportPdfModal" tabindex="-1" aria-labelledby="exportPdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content" style="border-radius: 12px; border: none; background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.15); padding: 10px 15px;">
            <form action="{{ route('admin.detections.export-pdf') }}" method="GET" target="_blank">
                <div class="modal-body p-3">
                    <!-- Title "Export Data" in blue/teal -->
                    <h5 id="exportPdfModalLabel" style="color: #0b5d76; font-weight: 700; font-size: 1.15rem; margin-bottom: 1.25rem; text-align: left;">Export Data</h5>

                    <!-- Input S -->
                    <div class="mb-3">
                        <label for="s_value" class="form-label" style="color: #0b5d76; font-weight: 600; font-size: 0.95rem;">Jumlah Balita di Wilayah (S) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="s_value" name="s_value" required min="1" placeholder="Masukkan total balita di wilayah" style="border-radius: 8px; border: 1px solid #ced4da;">
                    </div>
                    
                    <h6 style="color: #0b5d76; font-weight: 600; font-size: 0.95rem; margin-bottom: 10px;">Pilih Bulan</h6>
                    
                    @if($availableMonths->isEmpty())
                        <p class="text-center text-muted mb-0">Belum ada data deteksi untuk diekspor.</p>
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
                        <button type="submit" class="btn text-white" style="background-color: #0b5d76; border-radius: 8px; padding: 6px 18px; font-weight: 600; font-size: 0.9rem; border: none;" {{ $availableMonths->isEmpty() ? 'disabled' : '' }}>Terapkan</button>
                        <button type="button" class="btn text-white" id="reset-months-btn" style="background-color: #0b5d76; border-radius: 8px; padding: 6px 18px; font-weight: 600; font-size: 0.9rem; border: none;" {{ $availableMonths->isEmpty() ? 'disabled' : '' }}>Reset</button>
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

{{-- No filter/search for admin detections --}}
@endsection
