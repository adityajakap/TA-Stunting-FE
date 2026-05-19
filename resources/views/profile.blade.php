@extends('layouts.app')

@section('title', 'Profil Akun')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm p-4" style="border-radius: 16px;">
        <h3 class="mb-4" style="color: #005f77;">Profil Akun</h3>

        <div class="row mb-3">
            <div class="col-md-4 fw-semibold text-secondary">Nama Lengkap</div>
            <div class="col-md-8">{{ Auth::user()->nama_lengkap }}</div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4 fw-semibold text-secondary">Username</div>
            <div class="col-md-8">{{ Auth::user()->username }}</div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4 fw-semibold text-secondary">NIK Ibu</div>
            <div class="col-md-8">{{ Auth::user()->nik_ibu }}</div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4 fw-semibold text-secondary">Role</div>
            <div class="col-md-8 text-capitalize">{{ Auth::user()->role }}</div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4 fw-semibold text-secondary">Terdaftar Sejak</div>
            <div class="col-md-8">{{ Auth::user()->created_at->format('d M Y, H:i') }}</div>
        </div>

        <div class="text-end">
            <a href="{{ url()->previous() }}" class="btn text-white" style="background-color: #005f77;">← Kembali</a>
        </div>
    </div>
</div>
@endsection
