{{-- resources/views/namafile.blade.php --}}
@extends('layouts.app')

@section('title', 'Deteksi Stunting')

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

    .btn-icon-mini {
        background-color: #005f77;
        color: white;
        padding: 0.3rem 0.6rem;
        border: none;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        cursor: pointer;
    }

    .btn-icon-mini:hover { background-color: #014f66; }

    .card-wrapper { max-width: 1280px; margin: 6rem auto 0 auto; padding-bottom: 2rem; }

    .card { background-color: #ffffff; border-radius: 1rem; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: flex; flex-direction: column; }
    .card-body { padding: 1rem; display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1; }

    .table thead th { background: #f8fafc; }

    .empty-message { text-align: center; font-size: 1rem; color: #6b7280; margin: 3rem 0; }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e5a6e;
        margin-bottom: 1.5rem;
        margin-top: 2rem;
    }

    .table {
        /* Use separate borders so we can apply rounded corners to the table cells */
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #f9fafb;
        color: #1f2937;
        font-weight: 600;
        padding: 0.75rem;
        border: 1px solid #e5e7eb;
        text-align: left;
    }

    .table tbody tr {
        border-bottom: 1px solid #e5e7eb;
    }

    .table tbody tr:hover {
        background-color: #f9fafb;
    }

    .table tbody td {
        padding: 0.75rem;
        border: 1px solid #e5e7eb;
        color: #374151;
    }

    /* Rounded corners for the table inside the card */
    .table thead th:first-child { border-top-left-radius: 0.75rem; }
    .table thead th:last-child { border-top-right-radius: 0.75rem; }
    .table tbody tr:last-child td:first-child { border-bottom-left-radius: 0.75rem; }
    .table tbody tr:last-child td:last-child { border-bottom-right-radius: 0.75rem; }

    /* Custom Table Card Overrides to fix Flexbox Overflow Bugs */
    .table-card {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        overflow: hidden !important;
    }

    .table-responsive {
        display: block !important;
        width: 100% !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
    }

    /* ========== RESPONSIVE DESIGN ========== */
    @media (max-width: 767.98px) {
        .main-header {
            margin: 1rem auto 0.5rem;
            padding: 0 1rem;
        }

        .main-title {
            font-size: 1.5rem;
        }

        .card-wrapper {
            padding: 0 1rem 1.5rem;
            margin-top: 1rem !important;
        }

        .section-title {
            font-size: 1.3rem;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            padding: 0;
        }

        .table thead th, .table tbody td {
            padding: 0.6rem 0.5rem;
            font-size: 0.8rem;
            white-space: nowrap; /* Prevent ugly squishing and wrapping of cells */
        }
    }
</style>

<div class="card-wrapper">
    <div class="card mb-4">
        <div class="card-body p-4">
            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom: 1.5rem;">
                <x-back-button />
                <h1 style="color: #005f77; font-size: 1.6rem; font-weight: 700; margin: 0;">Form Deteksi Stunting</h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success" style="padding: 1rem; margin-bottom: 1rem; color: #0f5132; background-color: #d1e7dd; border: 1px solid #badbcc; border-radius: 0.375rem;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger" style="padding: 1rem; margin-bottom: 1rem; color: #842029; background-color: #f8d7da; border: 1px solid #f5c2c7; border-radius: 0.375rem;">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger" style="padding: 1rem; margin-bottom: 1rem; color: #842029; background-color: #f8d7da; border: 1px solid #f5c2c7; border-radius: 0.375rem;">
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Deteksi --}}
            <form action="{{ route('orangtua.detections.store') }}" method="POST">
        @csrf




        <div class="mb-3">
            <label>Berat Badan (kg)</label>
            <input type="number" step="0.1" name="berat_badan" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tinggi Badan (cm)</label>
            <input type="number" step="0.1" name="tinggi_badan" class="form-control" required>
        </div>

                <div class="d-grid gap-2 d-md-flex justify-content-end">
                    <x-button>Deteksi</x-button>
                </div>
            </form>
        </div>
    </div>

    @if(session('rekomendasi_menu'))
    <div class="mb-4 mt-4" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
        <h3 style="color: #005f77; font-weight: 800; font-size: 1.5rem; margin-bottom: 0.5rem;">Rekomendasi Menu</h3>
        <ul style="padding-left: 20px; font-size: 1rem; color: #333; margin-bottom: 0;">
            @foreach(session('rekomendasi_menu') as $menu)
                <li style="margin-bottom: 4px;">{{ is_object($menu) ? $menu->name : ($menu['name'] ?? 'Menu Sehat') }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Hasil Deteksi Terbaru --}}
    @isset($hasil)
    <div class="mb-3">
        <h3 class="mb-3 fw-bold">Hasil Deteksi Terbaru</h3>
        <div class="card p-3">
            <p><strong>Nama:</strong> {{ $hasil->nama }}</p>
            <p><strong>Umur:</strong> {{ $hasil->umur }} bulan</p>
            <p><strong>Jenis Kelamin:</strong> {{ $hasil->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
            <p><strong>Berat Badan:</strong> {{ $hasil->berat_badan }} kg</p>
            <p><strong>Tinggi Badan:</strong> {{ $hasil->tinggi_badan }} cm</p>
            <p><strong>Z-Score:</strong> {{ $hasil->z_score }}</p>
            <p><strong>Status:</strong>
                <span class="badge {{ $hasil->status == 'Stunting' ? 'bg-danger' : 'bg-success' }}">
                    {{ $hasil->status == 'Tinggi' ? 'Normal' : $hasil->status }}
                </span>
            </p>
        </div>
    </div>
    @endisset

    @if(isset($kmsData) && !empty($kmsData['who']))
    <div class="mb-4">
        <h2 class="section-title">Grafik Pertumbuhan (KMS) - {{ $kmsData['gender'] == 'L' ? 'Laki-laki' : 'Perempuan' }}</h2>
        <div class="card p-3" style="border-radius: 12px; border: 1px solid #e2e8f0; background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <canvas id="kmsChart" width="400" height="200"></canvas>
        </div>
    </div>
    @endif

    {{-- Riwayat Deteksi (Kader) --}}
    <h2 class="section-title">Riwayat Deteksi (Ditambahkan oleh Kader)</h2>
    <div class="card table-card mb-4">
        <div class="card-body p-0" style="display: block !important;">
            <div class="table-responsive" style="border-radius: 1rem; overflow: hidden;">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Umur (bulan)</th>
                            <th>Jenis Kelamin</th>
                            <th>Berat (kg)</th>
                            <th>Tinggi (cm)</th>
                            <th>Z-Score</th>
                            <th>Status</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $riwayatKader = collect($semua)->filter(function($d) {
                                return (is_object($d) ? ($d->added_by ?? 'orangtua') : ($d['added_by'] ?? 'orangtua')) === 'kader';
                            });
                        @endphp
                        @forelse($riwayatKader as $d)
                        <tr>
                            <td>{{ is_object($d) ? $d->nama : ($d['nama'] ?? '-') }}</td>
                            <td>{{ is_object($d) ? $d->umur : ($d['umur'] ?? '-') }}</td>
                            <td>{{ (is_object($d) ? $d->jenis_kelamin : ($d['jenis_kelamin'] ?? '')) == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>{{ is_object($d) ? $d->berat_badan : ($d['berat_badan'] ?? '-') }}</td>
                            <td>{{ is_object($d) ? $d->tinggi_badan : ($d['tinggi_badan'] ?? '-') }}</td>
                            <td>{{ is_object($d) ? $d->z_score : ($d['z_score'] ?? '-') }}</td>
                            <td>
                                @php
                                    $status = is_object($d) ? $d->status : ($d['status'] ?? '');
                                @endphp
                                <span class="badge {{ $status == 'Stunting' ? 'bg-danger' : 'bg-success' }}">
                                    {{ $status == 'Tinggi' ? 'Normal' : $status }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $date = is_object($d) ? $d->created_at : ($d['created_at'] ?? now());
                                    if (is_string($date)) {
                                        $date = \Carbon\Carbon::parse($date);
                                    }
                                @endphp
                                {{ $date->format('d M Y H:i') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data deteksi dari kader.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Riwayat Deteksi (Orangtua) --}}
    <h2 class="section-title">Riwayat Deteksi (Tambah Mandiri Orangtua)</h2>
    <div class="card table-card mb-4">
        <div class="card-body p-0" style="display: block !important;">
            <div class="table-responsive" style="border-radius: 1rem; overflow: hidden;">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Umur (bulan)</th>
                            <th>Jenis Kelamin</th>
                            <th>Berat (kg)</th>
                            <th>Tinggi (cm)</th>
                            <th>Z-Score</th>
                            <th>Status</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $riwayatOrangtua = collect($semua)->filter(function($d) {
                                return (is_object($d) ? ($d->added_by ?? 'orangtua') : ($d['added_by'] ?? 'orangtua')) === 'orangtua';
                            });
                        @endphp
                        @forelse($riwayatOrangtua as $d)
                        <tr>
                            <td>{{ is_object($d) ? $d->nama : ($d['nama'] ?? '-') }}</td>
                            <td>{{ is_object($d) ? $d->umur : ($d['umur'] ?? '-') }}</td>
                            <td>{{ (is_object($d) ? $d->jenis_kelamin : ($d['jenis_kelamin'] ?? '')) == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>{{ is_object($d) ? $d->berat_badan : ($d['berat_badan'] ?? '-') }}</td>
                            <td>{{ is_object($d) ? $d->tinggi_badan : ($d['tinggi_badan'] ?? '-') }}</td>
                            <td>{{ is_object($d) ? $d->z_score : ($d['z_score'] ?? '-') }}</td>
                            <td>
                                @php
                                    $status = is_object($d) ? $d->status : ($d['status'] ?? '');
                                @endphp
                                <span class="badge {{ $status == 'Stunting' ? 'bg-danger' : 'bg-success' }}">
                                    {{ $status == 'Tinggi' ? 'Normal' : $status }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $date = is_object($d) ? $d->created_at : ($d['created_at'] ?? now());
                                    if (is_string($date)) {
                                        $date = \Carbon\Carbon::parse($date);
                                    }
                                @endphp
                                {{ $date->format('d M Y H:i') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data deteksi yang ditambahkan secara mandiri.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if(isset($kmsData) && !empty($kmsData['who']))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const kmsData = @json($kmsData);
        const ctx = document.getElementById('kmsChart').getContext('2d');
        
        const labels = kmsData.who.map(d => d.Month);
        
        const sd3 = kmsData.who.map(d => d.SD3);
        const sd2 = kmsData.who.map(d => d.SD2);
        const median = kmsData.who.map(d => d.SD0);
        const sd2neg = kmsData.who.map(d => d.SD2neg);
        const sd3neg = kmsData.who.map(d => d.SD3neg);
        
        // Connect history dots by filtering nulls for the line
        const historyPoints = [];
        if (kmsData.history) {
            kmsData.history.forEach(hist => {
                if(hist.umur <= 60) {
                    historyPoints.push({x: hist.umur, y: hist.tinggi_badan});
                }
            });
        }

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Sangat Pendek (-3 SD)',
                        data: sd3neg,
                        borderColor: '#e74c3c',
                        borderWidth: 1.5,
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: 'Stunting (-2 SD)',
                        data: sd2neg,
                        borderColor: '#f1c40f',
                        borderWidth: 1.5,
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: 'Normal (Median)',
                        data: median,
                        borderColor: '#2ecc71',
                        borderWidth: 2,
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: 'Tinggi (+2 SD)',
                        data: sd2,
                        borderColor: '#3498db',
                        borderWidth: 1.5,
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: 'Tinggi Badan Anak',
                        data: historyPoints,
                        borderColor: '#9b59b6',
                        backgroundColor: '#9b59b6',
                        borderWidth: 2,
                        pointRadius: 4,
                        fill: false,
                        showLine: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        type: 'linear',
                        title: { display: true, text: 'Umur (Bulan)' },
                        min: 0,
                        max: 60,
                        ticks: { stepSize: 6 }
                    },
                    y: {
                        title: { display: true, text: 'Tinggi Badan (cm)' }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            title: (context) => `Umur: ${context[0].parsed.x} Bulan`
                        }
                    }
                }
            }
        });
    });
</script>
@endif

@endsection
