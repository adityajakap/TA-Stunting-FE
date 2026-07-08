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
    .card {
        background-color: #ffffff;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }
</style>

<div class="main-header">
    <a href="{{ route('admin.skdn.index') }}" class="back-btn"><i class="fas fa-chevron-left"></i></a>
    <h1 class="main-title">Grafik SKDN</h1>
</div>

<div class="card-wrapper">
    <div class="card mb-4" style="padding: 20px;">
        <h4 style="text-align: center; color: #005f77; margin-bottom: 10px;">Balok SKDN Posyandu Nusa Indah 1</h4>
        <h5 style="text-align: center; color: #555; margin-bottom: 20px;">Tahun: {{ $currentYear }}</h5>
        <div style="position: relative; height:40vh; width:100%">
            <canvas id="yearlyChart"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('yearlyChart').getContext('2d');
        var labels = {!! json_encode(array_column($yearlyChart, 'month')) !!};
        var dataS = {!! json_encode(array_column($yearlyChart, 'S')) !!};
        var dataK = {!! json_encode(array_column($yearlyChart, 'K')) !!};
        var dataD = {!! json_encode(array_column($yearlyChart, 'D')) !!};
        var dataN = {!! json_encode(array_column($yearlyChart, 'N')) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'S',
                        data: dataS,
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'K',
                        data: dataK,
                        backgroundColor: 'rgba(34, 197, 94, 0.8)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'D',
                        data: dataD,
                        backgroundColor: 'rgba(234, 179, 8, 0.8)',
                        borderColor: 'rgba(234, 179, 8, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'N',
                        data: dataN,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    });
</script>
@endsection
