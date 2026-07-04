@extends('layouts.app')

@section('title', 'Tambah Deteksi Stunting (Admin)')

@section('content')
<style>
    .main-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        margin: 1rem auto;
        padding: 0 1rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .main-title {
        color: #005f77;
        font-size: 1.5rem;
        margin: 0;
    }

    .card-wrapper {
        width: 100%;
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

    .card-body {
        padding: 1.5rem;
    }

    .form-label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.9rem;
    }

    .form-control {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.6rem 0.8rem;
        font-size: 1rem;
        width: 100%;
        background-color: #fff;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #005f77;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 95, 119, 0.1);
    }

    .mb-3 {
        margin-bottom: 1.5rem;
    }

    .button-group {
        display: flex;
        gap: 0.75rem;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        flex: 1;
        min-width: 120px;
    }

    .btn-primary {
        background-color: #005f77;
        color: white;
    }

    .btn-primary:hover {
        background-color: #014f66;
        transform: translateY(-2px);
    }

    .btn-secondary {
        background-color: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
        transform: translateY(-2px);
    }

    .alert {
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
    }

    .alert-danger {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    /* ========== RESPONSIVE DESIGN ========== */
    /* Mobile (xs) - 320px to 575px */
    @media (max-width: 575.98px) {
        .main-header {
            margin: 0.75rem auto;
            padding: 0 0.75rem;
        }

        .main-title {
            font-size: 1.3rem;
        }

        .card-wrapper {
            padding: 0 0.75rem 1.5rem;
        }

        .card-body {
            padding: 1rem;
        }

        .form-label {
            font-size: 0.85rem;
        }

        .form-control {
            padding: 0.5rem 0.6rem;
            font-size: 0.95rem;
        }

        .mb-3 {
            margin-bottom: 1.2rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            min-width: 100px;
            flex: 1 1 48%;
        }

        .button-group {
            gap: 0.5rem;
            margin-top: 1.5rem;
        }

        .alert {
            padding: 0.75rem;
            font-size: 0.9rem;
        }
    }

    /* Tablet (sm) - 576px to 767px */
    @media (min-width: 576px) and (max-width: 767.98px) {
        .main-header {
            margin: 1rem auto;
            padding: 0 1rem;
        }

        .main-title {
            font-size: 1.5rem;
        }

        .card-wrapper {
            padding: 0 1rem 1.75rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        .form-label {
            font-size: 0.9rem;
        }

        .form-control {
            padding: 0.6rem 0.8rem;
        }

        .btn {
            min-width: 110px;
            font-size: 0.9rem;
        }
    }

    /* Medium screens (md) - 768px to 991px */
    @media (min-width: 768px) {
        .main-header {
            max-width: 1280px;
            margin: 1.5rem auto;
            padding: 0 1.5rem;
        }

        .main-title {
            font-size: 1.75rem;
        }

        .card-wrapper {
            max-width: 1280px;
            padding: 0 1.5rem 2rem;
        }

        .card-body {
            padding: 1.75rem;
        }

        .form-label {
            font-size: 0.95rem;
        }

        .form-control {
            padding: 0.7rem 1rem;
        }

        .btn {
            flex: 0 1 auto;
            min-width: auto;
            font-size: 0.95rem;
        }

        .button-group {
            flex-wrap: nowrap;
        }
    }

    /* Large screens (lg) and up - 992px+ */
    @media (min-width: 992px) {
        .main-header {
            max-width: 1280px;
            margin: 2rem auto 1rem;
            padding: 0 1rem;
        }

        .main-title {
            font-size: 2rem;
        }

        .card-wrapper {
            max-width: 1280px;
            padding: 0 1rem 2rem;
        }

        .card-body {
            padding: 2rem;
        }

        .form-label {
            font-size: 0.95rem;
        }

        .form-control {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }

        .btn {
            padding: 0.6rem 1.5rem;
            font-size: 1rem;
        }
    }
</style>

<div class="main-header">
    <div style="display:flex; align-items:center; gap:0.5rem;">
        <x-back-button :url="route('admin.detections.index')" />
        <h1 class="main-title">Form Deteksi</h1>
    </div>
</div>

<div class="card-wrapper">
    <div class="card">
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('admin.detections.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="child_id" class="form-label">Pilih Anak (Orangtua)</label>
                    <select name="child_id" id="child_id" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->nama_lengkap_anak }} ({{ $u->nik_anak }})</option>
                        @endforeach
                    </select>
                </div>


                <div class="mb-3">
                    <label for="berat_badan" class="form-label">Berat Badan (kg)</label>
                    <input type="number" step="0.1" name="berat_badan" id="berat_badan" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="tinggi_badan" class="form-label">Tinggi Badan (cm)</label>
                    <input type="number" step="0.1" name="tinggi_badan" id="tinggi_badan" class="form-control" required>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Tambah Deteksi</button>
                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
