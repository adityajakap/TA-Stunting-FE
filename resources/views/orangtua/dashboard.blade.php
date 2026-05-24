@extends('layouts.app')

@section('content')

<style>
    /* OVERRIDE agar container bawaan layout jadi full width hanya untuk halaman ini */
    .container.mt-4 {
        max-width: 100% !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .section-wrapper {
        padding: 30px 20px 20px 20px;
        width: 100%;
    }

    .section-title {
        color: #005f77;
        font-weight: 700;
        font-size: 1.8rem;
        margin-bottom: 1rem;
    }

    .section-title-feature {
        color: #005f77;
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .hero-section {
        display: flex;
        flex-direction: column-reverse;
        justify-content: space-between;
        align-items: center;
        padding: 20px 0;
        gap: 20px;
    }

    .hero-text {
        width: 100%;
        order: 2;
    }

    .hero-text p {
        font-size: 1rem;
        line-height: 1.6;
        color: #555;
    }

    .hero-image {
        width: 100%;
        height: 250px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        order: 1;
    }

    .hero-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        display: block;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 15px;
        padding: 20px 0;
        justify-content: space-between;
    }

    .feature-box-link {
        text-decoration: none;
        display: block;
        width: 100%;
        color: inherit;
    }

    .feature-box-link:hover {
        text-decoration: none;
        color: inherit;
    }

    .feature-box {
        background-color: #0b5d76;
        color: white;
        padding: 20px;
        border-radius: 16px;
        transition: all 0.3s ease-in-out;
        text-align: center;
        min-height: 150px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .feature-box:hover {
        background-color: #084c61;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(11, 93, 118, 0.3);
    }

    .feature-box h3 {
        color: #ffffff;
        font-size: 1.2rem;
        font-weight: 600;
        margin: 10px 0 8px 0;
    }

    .feature-box p {
        color: #f1f1f1;
        font-size: 0.9rem;
        line-height: 1.5;
        margin: 0;
    }

    .icon-feature {
        font-size: 2.5rem;
        color: #ffffff;
        margin-bottom: 10px;
        display: block;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 15px;
        padding: 20px 0;
        width: 100%;
    }

    .article-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 15px;
        padding: 20px 0;
        width: 100%;
    }

    .menu-block {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 12px;
        text-align: center;
        width: 100%;
        transition: transform 0.3s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .menu-block:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    }

    .menu-block img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    
    .article-card {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        overflow: hidden;
        width: 100%;
        transition: transform 0.3s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .article-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }

    .article-card img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 8px;
    }

    .article-content {
        padding: 15px;
        text-align: left;
    }

    .article-content h6 {
        font-weight: 600;
        margin: 0 0 8px 0;
        color: #333;
        font-size: 0.95rem;
        line-height: 1.4;
    }

    .article-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 15px 15px;
        font-size: 0.8rem;
        color: #6c757d;
        flex-wrap: wrap;
        gap: 10px;
    }

    .article-actions .views {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .article-actions .views i {
        color: #005f77;
    }

    .article-actions a {
        background: #005f77;
        color: white;
        border-radius: 6px;
        padding: 6px 12px;
        text-decoration: none;
        font-size: 0.8rem;
        display: inline-block;
        font-weight: 500;
    }

    .article-actions a:hover {
        background: #00485e;
        transform: translateY(-1px);
    }

    .section-header {
        padding: 15px 0;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .section-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 1.3rem;
        color: #005f77;
    }

    .section-header .btn-arrow {
        background: white;
        border: 2px solid #00a896;
        color: #00a896;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        font-size: 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .section-header .btn-arrow:hover {
        background: #00a896;
        color: white;
    }

    /* ========== RESPONSIVE DESIGN ========== */
    /* Tablet (sm) - 576px to 767px */
    @media (min-width: 576px) {
        .section-wrapper {
            padding: 35px 30px 30px 30px;
        }

        .section-title {
            font-size: 2rem;
        }

        .section-title-feature {
            font-size: 1.6rem;
        }

        .hero-section {
            gap: 30px;
            padding: 30px 0;
        }

        .hero-image {
            height: 280px;
        }

        .feature-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            padding: 30px 0;
        }

        .feature-box {
            min-height: 160px;
            padding: 25px;
        }

        .feature-box h3 {
            font-size: 1.3rem;
        }

        .icon-feature {
            font-size: 2.8rem;
        }

        .menu-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .article-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .menu-block img {
            height: 160px;
        }

        .article-card img {
            height: 130px;
        }

        .section-header h4 {
            font-size: 1.5rem;
        }
    }

    /* Medium screens (md) - 768px to 991px */
    @media (min-width: 768px) {
        .section-wrapper {
            padding: 40px 40px 40px 40px;
        }

        .section-title {
            font-size: 2.5rem;
        }

        .section-title-feature {
            font-size: 1.8rem;
        }

        .hero-section {
            flex-direction: row;
            gap: 40px;
            padding: 40px 0;
        }

        .hero-text {
            max-width: 50%;
            order: 1;
        }

        .hero-image {
            width: 45%;
            height: 320px;
            order: 2;
        }

        .feature-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            padding: 40px 0;
        }

        .feature-box {
            min-height: 180px;
        }

        .menu-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 30px 0;
        }

        .article-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .menu-block img {
            height: 170px;
        }

        .section-header h4 {
            font-size: 1.8rem;
        }
    }

    /* Large screens (lg) and up - 992px+ */
    @media (min-width: 992px) {
        .section-wrapper {
            padding: 50px 5% 5% 5%;
        }

        .section-title {
            font-size: 3rem;
        }

        .section-title-feature {
            font-size: 2rem;
        }

        .hero-section {
            justify-content: space-between;
            align-items: center;
            padding: 60px 0;
        }

        .hero-text {
            max-width: 55%;
        }

        .hero-image {
            width: 40%;
            height: 400px;
        }

        .feature-grid {
            display: flex;
            gap: 20px;
            padding: 40px 0;
            justify-content: space-between;
        }

        .feature-box-link {
            flex: 1;
            display: flex;
        }

        .feature-box {
            width: 100%;
            padding: 30px;
            min-height: 200px;
        }

        .feature-box h3 {
            font-size: 1.5rem;
        }

        .icon-feature {
            font-size: 3rem;
        }

        .menu-grid {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 40px 0;
            overflow-x: auto;
            flex-wrap: nowrap;
            white-space: nowrap;
        }

        .menu-block {
            width: 280px;
            flex: 0 0 auto;
        }

        .menu-block img {
            height: 180px;
        }

        .article-grid {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 40px 0;
            overflow-x: auto;
        }

        .article-card {
            min-width: 300px;
            flex: 0 0 auto;
        }

        .article-card img {
            height: 150px;
        }

        .section-header h4 {
            font-size: 1.8rem;
        }

        .section-header .btn-arrow {
            width: 40px;
            height: 40px;
        }
    }

    /* Child selection spacing: small on mobile, larger on tablet/desktop */
    .child-card-wrapper { margin-top: 20px; }
    @media (min-width: 576px) {
        .child-card-wrapper { margin-top: 28px; }
    }
    @media (min-width: 768px) {
        .child-card-wrapper { margin-top: 48px; }
    }
    @media (min-width: 992px) {
        .child-card-wrapper { margin-top: 64px; }
    }
    @media (min-width: 1200px) {
        .child-card-wrapper { margin-top: 80px; }
    }

    /* Child Selector Bar styling */
    .child-bar {
        background: #fff;
        padding: 16px;
        border-radius: 12px;
        border: 1px solid #c8d1d1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .child-bar-info {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        min-width: 0;
        flex: 1;
    }

    .child-bar-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .child-select-wrapper {
        position: relative;
    }

    .child-select {
        width: 320px;
        padding: 10px 36px 10px 14px;
        border-radius: 20px;
        border: 1px solid #cfd8dc;
        background: #fff;
        color: #333;
    }

    /* Mobile Responsive Styles for Child Selector */
    @media (max-width: 767.98px) {
        .child-bar {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 12px !important;
            padding: 14px !important;
        }

        .child-bar-info {
            width: 100% !important;
            text-align: left !important;
        }

        .child-bar-info h5 {
            white-space: normal !important; /* Allow long name wrapping on mobile */
            font-size: 1.1rem !important;
            line-height: 1.4 !important;
        }

        .child-bar-actions {
            width: 100% !important;
            justify-content: space-between !important;
            gap: 8px !important;
        }

        .child-bar-actions form {
            flex: 1 !important;
            min-width: 0 !important;
        }

        .child-select-wrapper {
            width: 100% !important;
        }

        .child-select {
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
            font-size: 0.9rem !important;
            padding: 8px 30px 8px 12px !important;
        }

        .child-bar-actions .btn {
            font-size: 0.9rem !important;
            padding: 8px 14px !important;
        }
    }
</style>

<div class="section-wrapper">

    {{-- CHILD MANAGEMENT SECTION --}}

    <div class="mb-4 child-card-wrapper">

        @if($children->isEmpty())
            <div class="alert alert-warning">
                <strong>Perhatian!</strong> Anda belum memiliki data anak. Silakan tambah data anak terlebih dahulu untuk mengakses fitur-fitur aplikasi.
            </div>
            <div class="card" style="border-radius: 12px; border: 1px solid #c8d1d1; max-width: 600px; margin: 0 auto; background: #fff; box-shadow: none;">
                <div class="card-body p-4">
                    <h5 class="text-center mb-4" style="color: #005f77; font-weight: 700; font-size: 1.35rem;">Tambah Data Anak</h5>
                    <form action="{{ route('orangtua.children.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_lengkap_anak" class="form-label" style="font-weight: 500; color: #333; margin-bottom: 6px;">Nama Lengkap Anak</label>
                            <input type="text" class="form-control" id="nama_lengkap_anak" name="nama_lengkap_anak" required style="border-radius: 10px; border: 1px solid #c8d1d1; padding: 10px 14px; background: #fff; color: #333;">
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label" style="font-weight: 500; color: #333; margin-bottom: 6px;">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required style="border-radius: 10px; border: 1px solid #c8d1d1; padding: 10px 14px; background: #fff; color: #333;">
                        </div>
                        <div class="mb-4">
                            <label for="nik_anak" class="form-label" style="font-weight: 500; color: #333; margin-bottom: 6px;">NIK Anak</label>
                            <input type="text" class="form-control" id="nik_anak" name="nik_anak" maxlength="16" style="border-radius: 10px; border: 1px solid #c8d1d1; padding: 10px 14px; background: #fff; color: #333;">
                        </div>
                        <div style="text-align: left;">
                            <button type="submit" class="btn" style="background-color: #005f77; color: white; border: none; border-radius: 10px; padding: 10px 24px; font-weight: 600; width: auto;">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="child-bar">
                <div class="child-bar-info">
                    <div>
                        <h5 style="margin:0;color:#005f77;font-weight:700;">Anak aktif: <span style="font-weight:600;">{{ $selectedChild ? $selectedChild->nama_lengkap_anak : 'Belum ada anak yang dipilih' }}</span></h5>
                        <p style="margin:0;color:#6c757d;font-size:0.9rem;">Pilih anak untuk memonitor tumbuh kembangnya.</p>
                    </div>
                </div>
                <div class="child-bar-actions">
                    <form action="{{ route('orangtua.children.select') }}" method="POST" class="m-0">
                        @csrf
                        <div class="child-select-wrapper">
                            <select name="child_id" class="form-select child-select" onchange="this.form.submit()">
                                <option value="" disabled {{ !$selectedChildId ? 'selected' : '' }}>-- Pilih Anak --</option>
                                @foreach($children as $child)
                                    <option value="{{ $child->id }}" {{ $selectedChildId == $child->id ? 'selected' : '' }}>
                                        {{ $child->nama_lengkap_anak }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#6c757d;pointer-events:none;font-size:0.9rem;"></i>
                        </div>
                    </form>
                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#addChildModal" style="background:#005f77;color:white;border-radius:8px;padding:10px 14px;border:none;white-space:nowrap;">
                        + Anak
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- HERO SECTION --}}
    <div class="hero-section">
        <div class="hero-text">
            <h2 class="section-title">Pantau Tumbuh Kembang, Cegah Stunting Sejak Dini!</h2>
            <p>Pantau dan deteksi tumbuh kembang anak Anda secara berkala, serta dapatkan rekomendasi menu bergizi yang disesuaikan dengan kebutuhan hariannya untuk mendukung pertumbuhan yang optimal.</p>
            <div class="mt-3">
                <a href="{{ route('orangtua.detections.create') }}" class="btn btn-primary" style="background-color: #005f77; border: none;">Deteksi Stunting</a>
                <a href="{{ route('orangtua.tahapan_perkembangan.index') }}" class="btn btn-primary" style="background-color: #005f77; border: none;">Monitoring Anak</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="{{ asset('images/logo.png') }}" alt="Ilustrasi Dashboard" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
    </div>

    {{-- FITUR UTAMA --}}
    <div class="feature-grid">
        <a href="{{ route('orangtua.detections.create') }}" class="feature-box-link">
            <div class="feature-box">
                <i class="fas fa-chart-line icon-feature"></i>
                <h3>Deteksi</h3>
                <p>Deteksi stunting pada anak dengan menggunakan metode yang tepat.</p>
            </div>
        </a>
        <a href="{{ route('orangtua.nutritionUs.index') }}" class="feature-box-link">
            <div class="feature-box">
                <i class="fas fa-leaf icon-feature"></i>
                <h3>Menu</h3>
                <p>Dapatkan rekomendasi menu bergizi untuk tumbuh kembang anak.</p>
            </div>
        </a>
        <a href="{{ route('bmi') }}" class="feature-box-link">
            <div class="feature-box">
                <i class="fas fa-calculator icon-feature"></i>
                <h3>BMI</h3>
                <p>Hitung status gizi anak secara cepat dan mudah berdasarkan tinggi dan berat badan.</p>
            </div>
        </a>
    </div>



    {{-- TODAY MENU --}}
    <div class="section-header">
        <h4 class="section-title-feature mb-0">Today Menu's</h4>
    </div>

    <div class="menu-grid">
        @php
            $menuItems = collect(['pagi', 'siang', 'malam', 'snack'])->map(function($waktu) use ($menus) {
                return $menus[$waktu] ?? null;
            })->filter()->take(3);
        @endphp
        @foreach ($menuItems as $menu)
            <div class="menu-block">
                <img src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('default-image.png') }}" alt="Menu">
                <h6 style="font-weight: 600; margin: 10px 0 5px 0;">{{ $menu->name }}</h6>
                <small style="color: #6c757d; text-transform: capitalize;">{{ $menu->category }}</small>
                <div style="margin-top: 15px;">
                    <a href="{{ route('orangtua.nutritionUs.show', $menu->id) }}" 
                    style="background:#005f77; color:white; border-radius:8px; padding:10px 20px; text-decoration:none; font-size:0.9rem; display:inline-block; font-weight: 500;">
                        Lihat Menu
                    </a>
                </div>
            </div>
        @endforeach
    </div>


    {{-- ARTICLES --}}
    <div class="section-header">
        <h4 class="section-title-feature mb-0">Articles</h4>
    </div>

    <div class="article-grid">
        @foreach ($artikels->take(3) as $artikel)
            <div class="article-card">
                <img src="{{ $artikel->image ? asset('storage/' . $artikel->image) : asset('default-image.png') }}" alt="Artikel">
                <div class="article-content">
                    <h6>{{ Str::limit($artikel->title, 60) }}</h6>
                </div>
                <div class="article-actions">
                    <div class="views">
                        <i class="fas fa-eye"></i>
                        <span>{{ $artikel->views ?? 100 }}</span>
                    </div>
                    <a href="{{ route('orangtua.artikel.show', $artikel->id) }}">Read All</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal Add Child -->
<div class="modal fade" id="addChildModal" tabindex="-1" aria-labelledby="addChildModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: 1px solid #c8d1d1; background: #fff; box-shadow: none;">
            <div class="modal-header border-0" style="background-color: #fff; border-top-left-radius: 12px; border-top-right-radius: 12px; justify-content: center; position: relative; padding-top: 24px; padding-bottom: 0;">
                <h5 class="modal-title" id="addChildModalLabel" style="color: #005f77; font-weight: 700; font-size: 1.35rem; width: 100%; text-align: center;">Tambah Data Anak</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; right: 20px; top: 24px;"></button>
            </div>
            <div class="modal-body p-4" style="padding-top: 15px !important;">
                <form action="{{ route('orangtua.children.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_lengkap_anak_modal" class="form-label" style="font-weight: 500; color: #333; margin-bottom: 6px;">Nama Lengkap Anak</label>
                        <input type="text" class="form-control" id="nama_lengkap_anak_modal" name="nama_lengkap_anak" required style="border-radius: 10px; border: 1px solid #c8d1d1; padding: 10px 14px; background: #fff; color: #333;">
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_lahir_modal" class="form-label" style="font-weight: 500; color: #333; margin-bottom: 6px;">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggal_lahir_modal" name="tanggal_lahir" required style="border-radius: 10px; border: 1px solid #c8d1d1; padding: 10px 14px; background: #fff; color: #333;">
                    </div>
                    <div class="mb-4">
                        <label for="nik_anak_modal" class="form-label" style="font-weight: 500; color: #333; margin-bottom: 6px;">NIK Anak</label>
                        <input type="text" class="form-control" id="nik_anak_modal" name="nik_anak" maxlength="16" style="border-radius: 10px; border: 1px solid #c8d1d1; padding: 10px 14px; background: #fff; color: #333;">
                    </div>
                    <div style="text-align: left;">
                        <button type="submit" class="btn" style="background-color: #005f77; color: white; border: none; border-radius: 10px; padding: 10px 24px; font-weight: 600; width: auto;">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
