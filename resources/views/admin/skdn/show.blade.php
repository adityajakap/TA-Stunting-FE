@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
    .controls-wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    .sasaran-input {
        border-radius: 8px;
        border: 1px solid #ccc;
        padding: 0.5rem 1rem;
        width: 250px;
    }
    .btn-masukan {
        background-color: #005f77;
        color: #fff;
        border-radius: 8px;
        padding: 0.5rem 1.5rem;
        border: none;
        font-weight: 600;
    }
    .btn-cetak {
        background-color: #005f77;
        color: #fff;
        border-radius: 8px;
        padding: 0.5rem 1.5rem;
        border: none;
        font-weight: 600;
        text-decoration: none;
    }
    .chart-container {
        width: 100%;
        max-width: 600px;
        margin: 0 auto 2rem;
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
    .badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 0.375rem;
        font-weight: 600;
    }
</style>

<div class="main-header">
    <a href="{{ route('admin.skdn.index') }}" class="back-btn"><i class="fas fa-chevron-left"></i></a>
    <h1 class="main-title">Data SKDN Bulan {{ \Carbon\Carbon::create()->month((int)$month)->translatedFormat('F') }} {{ $year }}</h1>
</div>

<div class="card-wrapper">
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="controls-wrapper">
        @if(is_null($sValue))
            <form action="{{ route('admin.skdn.target.store', ['month' => $month, 'year' => $year]) }}" method="POST" class="d-flex align-items-center gap-2">
                @csrf
                <input type="number" name="s_value" class="sasaran-input" placeholder="Masukan Jumlah Sasaran" required min="1">
                <button type="submit" class="btn-masukan">Masukan</button>
            </form>
        @else
            <div>
                <p style="margin: 0; font-weight: 500;">Jumlah sasaran Balita Posyandu Nusa Indah 1 sebanyak {{ $sValue }}</p>
            </div>
        @endif
        
        <a href="{{ route('admin.skdn.pdf', ['month' => $month, 'year' => $year]) }}" class="btn-cetak ms-auto" target="_blank">Cetak SKDN</a>
    </div>

    @if(!is_null($sValue))
    <div class="chart-container">
        <canvas id="skdnChart"></canvas>
    </div>
    @endif

    <div class="table-responsive mt-4" style="border-radius: 1rem; overflow: hidden; border: 1px solid #e5e7eb; background: #fff;">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Umur (bulan)</th>
                    <th>Jenis kelamin</th>
                    <th>Berat (kg)</th>
                    <th>Z-Score</th>
                    <th>Status</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($monthDetections as $d)
                <tr>
                    <td>{{ $d['child']['nama_lengkap_anak'] ?? '-' }}</td>
                    <td>{{ $d['umur'] ?? '-' }}</td>
                    <td>{{ ($d['jenis_kelamin'] ?? '') == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>{{ $d['berat_badan'] ?? '-' }}</td>
                    <td>{{ $d['z_score'] ?? '-' }}</td>
                    <td>
                        <span class="badge {{ ($d['status'] ?? '') == 'Stunting' ? 'bg-danger' : 'bg-success' }}">
                            {{ ($d['status'] ?? '') == 'Tinggi' ? 'Normal' : ($d['status'] ?? '-') }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($d['created_at'])->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data anak pada bulan ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(!is_null($sValue))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('skdnChart').getContext('2d');
        var skdnChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['S', 'K', 'D', 'N'],
                datasets: [{
                    label: 'SKDN Data',
                    data: [{{ $sValue }}, {{ $kValue }}, {{ $dValue }}, {{ $nValue }}],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)', // S - Red
                        'rgba(255, 206, 86, 0.8)', // K - Yellow
                        'rgba(54, 162, 235, 0.8)', // D - Blue
                        'rgba(75, 192, 192, 0.8)'  // N - Green
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1,
                    barPercentage: 0.5
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
