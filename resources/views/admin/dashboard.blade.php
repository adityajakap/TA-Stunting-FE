@extends('layouts.app')

@section('content')

<style>
    .container.mt-4 {
        max-width: 100% !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .section-wrapper {
        padding: 20px;
        width: 100%;
    }

    .section-title {
        color: #005f77;
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .hero-section {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        padding: 20px 0;
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
    }

    .feature-box-link {
        text-decoration: none;
        transition: transform 0.2s ease-in-out;
    }

    .feature-box-link:hover {
        transform: translateY(-4px);
    }

    .feature-box {
        background-color: #005f77;
        color: white;
        padding: 20px;
        border-radius: 12px;
        height: 100%;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        display: block;
    }

    .feature-box:hover {
        background-color: #00485e;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .icon-feature {
        font-size: 1.8rem;
        color: #ffffff;
        margin-bottom: 10px;
        display: block;
    }

    .feature-box h3 {
        color: white;
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }

    .feature-box p {
        color: white;
        font-size: 0.95rem;
        margin: 0;
    }

    /* ========== RESPONSIVE DESIGN ========== */
    /* Tablet (sm) - 576px to 767px */
    @media (min-width: 576px) {
        .section-wrapper {
            padding: 30px;
        }

        .section-title {
            font-size: 2.2rem;
        }

        .hero-section {
            gap: 30px;
        }

        .hero-image {
            height: 300px;
        }

        .feature-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .icon-feature {
            font-size: 2rem;
        }

        .feature-box h3 {
            font-size: 1.3rem;
        }
    }

    /* Medium screens (md) - 768px to 991px */
    @media (min-width: 768px) {
        .section-wrapper {
            padding: 40px;
        }

        .section-title {
            font-size: 2.5rem;
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
            height: 350px;
            order: 2;
        }

        .feature-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            padding: 30px 0;
        }

        .feature-box-link:hover {
            transform: translateY(-6px);
        }
    }

    /* Large screens (lg) and up - 992px+ */
    @media (min-width: 992px) {
        .section-wrapper {
            padding-inline: 5%;
        }

        .section-title {
            font-size: 3rem;
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
            flex-wrap: wrap;
            gap: 20px;
            padding: 40px 0;
        }

        .feature-box-link {
            flex: 1;
        }

        .icon-feature {
            font-size: 2rem;
        }

        .feature-box h3 {
            font-size: 1.3rem;
        }

        .feature-box p {
            font-size: 1rem;
        }
    }
</style>

<div class="section-wrapper">

    {{-- HERO SECTION --}}
    <div class="hero-section">
        <div class="hero-text">
            <h2 class="section-title">Selamat Datang di Dashboard Kader</h2>
            <p>Kelola data stunting, perkembangan anak, serta artikel dan rekomendasi nutrisi dengan antarmuka yang mudah digunakan dan informatif.</p>
            <div style="display: flex; gap: 15px; margin-top: 40px; flex-wrap: wrap;">
                <a href="{{ route('admin.ntob.index') }}" class="btn" style="background-color: #005f77; color: white; padding: 10px 24px; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-block; font-size: 0.95rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: all 0.3s ease;">Laporan NTOB</a>
                <a href="{{ route('admin.skdn.index') }}" class="btn" style="background-color: #005f77; color: white; padding: 10px 24px; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-block; font-size: 0.95rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: all 0.3s ease;">Laporan SKDN</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="{{ asset('images/logo.png') }}" alt="Dashboard Admin">
        </div>
    </div>

    {{-- FITUR UTAMA ADMIN --}}
    <div class="feature-grid">
        <a href="{{ route('admin.detections.index') }}" class="feature-box-link">
            <div class="feature-box">
                <i class="fas fa-search icon-feature"></i>
                <h3>Deteksi</h3>
                <p>Periksa hasil deteksi stunting dari akun orang tua.</p>
            </div>
        </a>
        <a href="{{ route('admin.nutrition.index') }}" class="feature-box-link">
            <div class="feature-box">
                <i class="fas fa-utensils icon-feature"></i>
                <h3>Menu</h3>
                <p>Atur dan kelola rekomendasi menu bergizi untuk anak-anak.</p>
            </div>
        </a>
        <a href="{{ route('admin.perkembangan.children.index') }}" class="feature-box-link">
            <div class="feature-box">
                <i class="fas fa-child icon-feature"></i>
                <h3>Perkembangan</h3>
                <p>Kelola data tahapan perkembangan anak dari berbagai usia.</p>
            </div>
        </a>
        <a href="{{ route('admin.artikel.index') }}" class="feature-box-link">
            <div class="feature-box">
                <i class="fas fa-newspaper icon-feature"></i>
                <h3>Artikel</h3>
                <p>Tambahkan, ubah, dan hapus artikel edukatif seputar stunting.</p>
            </div>
        </a>
    </div>
</div>
@endsection
