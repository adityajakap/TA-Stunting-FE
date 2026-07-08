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
        padding: 2rem;
        text-align: center;
    }
</style>

<div class="main-header">
    <div style="display:flex; align-items:center; gap:0.5rem;">
        <h1 class="main-title">Laporan NTOB</h1>
    </div>
</div>

<div class="card-wrapper">
    <div class="card">
        <h3 style="color: #005f77; margin-bottom: 1rem;">Laporan NTOB</h3>
        <p style="color: #666;">Kerangka halaman NTOB telah berhasil dibuat. Silakan konfirmasi bentuk laporan atau form yang diinginkan kepada tim pengembang.</p>
    </div>
</div>
@endsection
