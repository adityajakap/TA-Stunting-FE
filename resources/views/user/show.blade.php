@extends('layouts.app')

@section('content')
<style>
    .container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 1.5rem;
        background-color: #f9fafb;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    h1 {
        font-size: 2rem;
        font-weight: bold;
        color: #111827;
        margin-bottom: 1rem;
        text-align: center;
    }

    .artikel-image {
        width: 100%;
        max-width: 600px;
        max-height: 300px;
        object-fit: cover;
        border-radius: 0.5rem;
        margin: 0 auto 1.5rem;
        display: block;
    }

    .content {
        color: #374151;
        line-height: 1.8;
        overflow-wrap: break-word;
        word-break: break-word;
        white-space: pre-line;
    }

    .back-link {
        display: inline-block;
        margin-top: 2rem;
        text-decoration: none;
        color: #2563eb;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    .btn-back {
        display: inline-block;
        margin-top: 2rem;
        padding: 0.5rem 1rem;
        background-color: #006d8c;
        color: white;
        border-radius: 0.5rem;
        font-weight: bold;
        text-decoration: none;
        font-size: 0.95rem;
        text-align: center;
        transition: background-color 0.3s ease;
    }

    .btn-back:hover {
        background-color: #00546b;
    }

    .judul-artikel {
        font-size: 2rem;
        font-weight: bold;
        color: #111827;
        margin-bottom: 1rem;
        text-align: center;
        white-space: normal; /* Biar teks boleh pindah baris */
        word-break: break-word; /* Biar teks kepanjangan bisa dipotong */
        overflow-wrap: break-word; /* Tambahan support supaya nggak kesamping */
    }




</style>

<div class="container">
    <h1 class="judul-artikel">{{ $artikel->title }}</h1>

    @if ($artikel->image)
        <img src="{{ config('services.api.storage_url') . '/' . $artikel->image }}" alt="Gambar Artikel" class="artikel-image">
    @else
        <img src="{{ asset('default-image.png') }}" alt="Gambar Default" class="artikel-image">
    @endif

    <div class="content">
        {!! nl2br(e($artikel->content)) !!}
    </div>

    <a href="{{ route('user.artikel.index') }}" class="btn-back">← Kembali ke Daftar Artikel</a>
</div>
@endsection
