
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    {{-- Bootstrap 5.3 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Tailwind --}}
    <script>
    tailwind.config = {
        safelist: ['bg-[#005f77]', 'hover:bg-[#014f66]']
    }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Custom Styles --}}
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #fdfbfb, #ebedee);
            margin: 0;
            padding: 72px 0 0 0;
        }

        .btn-icon-mini {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #6c757d;
            cursor: pointer;
        }
        .btn-icon-mini:hover {
            color: #343a40;
        }

        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding-top: 0.7rem;
            padding-bottom: 0.7rem;
            height: auto;
            min-height: 72px;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #005f77 !important;
        }

        .navbar-brand img {
            max-width: 100%;
            height: auto;
        }

        .navbar-nav {
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .navbar-nav .nav-link {
            color: #005f77 !important;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 999px;
            transition: all 0.25s ease-in-out;
            font-size: 0.95rem;
            white-space: nowrap;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link:focus,
        .navbar-nav .nav-link.active {
            color: #ffffff !important;
            background-color: #005f77 !important;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transform: scale(1.02);
        }

        .navbar-nav .nav-link.dropdown-toggle:hover {
            color: #ffffff !important;
            background-color: rgba(0, 95, 119, 0.1);
            border-radius: 8px;
            padding: 6px 12px;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
        }

        main.container {
            max-width: 1280px;
            width: 100%;
        }

        .content-card {
            background-color: transparent;
            padding: 0;
            border-radius: 0;
            box-shadow: none;
        }

        .card-wrapper, .development-wrapper {
            margin-top: 1rem !important;
            margin-bottom: 2rem;
        }

        .dropdown-menu-end {
            right: 0;
            left: auto;
        }

        .dropdown-toggle::after {
            margin-left: 8px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ========== RESPONSIVE DESIGN ========== */
        /* Mobile (xs) - 320px to 575px */
        @media (max-width: 575.98px) {
            body {
                padding-top: 0 !important; /* Non-fixed navbar means no top padding needed! */
                font-size: 14px;
            }

            .navbar {
                position: relative !important; /* Make navbar scroll with the page on mobile */
                top: 0 !important;
                padding: 0.75rem 0.5rem !important;
                height: auto;
                min-height: 64px;
            }

            .navbar-brand {
                font-size: 1.2rem;
                margin-bottom: 0.5rem;
                display: flex;
                justify-content: center;
                width: 100%;
            }

            .navbar-brand img {
                height: 40px !important; /* Scale logo correctly, override inline styling */
            }

            .navbar-brand ~ .d-flex {
                flex-direction: column;
                align-items: center;
                width: 100%;
            }

            .navbar-nav {
                gap: 6px !important;
                margin-top: 8px !important;
                margin-bottom: 8px !important;
                justify-content: center;
                flex-direction: row !important;
                flex-wrap: wrap !important;
            }

            .navbar-nav .nav-link {
                padding: 4px 10px !important;
                font-size: 0.8rem !important;
            }

            .navbar-nav .nav-link:hover {
                transform: scale(1.01);
            }

            .dropdown {
                margin-top: 8px !important;
                margin-bottom: 4px !important;
                width: 100%;
                text-align: center;
            }

            .dropdown-toggle {
                display: block !important;
                text-align: center !important;
                font-size: 0.85rem !important;
                padding: 6px 12px !important;
                white-space: normal !important;
                word-break: break-word !important;
            }

            .dropdown-toggle .badge {
                display: block !important;
                width: fit-content !important;
                margin: 6px auto 0 !important;
                white-space: normal !important;
                font-size: 0.7rem !important;
                padding: 4px 8px !important;
            }

            main.container {
                margin-left: auto;
                margin-right: auto;
                padding-left: 0.75rem;
                padding-right: 0.75rem;
                margin-top: 1rem !important;
            }

            .container-fluid {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
        }

        /* Tablet (sm) - 576px to 767px */
        @media (min-width: 576px) and (max-width: 767.98px) {
            body {
                padding-top: 68px;
                font-size: 15px;
            }

            .navbar {
                padding: 0.6rem 1rem;
                min-height: 68px;
            }

            .navbar-brand {
                font-size: 1.3rem;
            }

            .navbar-nav .nav-link {
                padding: 5px 10px;
                font-size: 0.9rem;
            }

            main.container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        /* Medium screens (md) - 768px to 991px */
        @media (min-width: 768px) and (max-width: 991.98px) {
            body {
                padding-top: 70px;
                font-size: 15px;
            }

            .navbar {
                padding: 0.6rem 1.5rem;
            }

            .navbar-brand {
                font-size: 1.4rem;
            }

            main.container {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }

        /* Large screens (lg) and up - 992px+ */
        @media (min-width: 992px) {
            .navbar {
                padding: 0.7rem 2rem;
                height: 72px;
            }

            .navbar-brand {
                font-size: 1.5rem;
            }

            main.container {
                padding-left: 2rem;
                padding-right: 2rem;
                max-width: 95% !important;
                width: 95% !important;
            }

            /* Global widescreen overrides to stretch all features and match widescreen viewports cleanly */
            .card-wrapper, 
            .main-header, 
            .development-header, 
            .development-wrapper,
            .child-bar,
            .section-header,
            .hero-section,
            .feature-grid,
            .menu-grid,
            .article-grid {
                max-width: 100% !important;
                width: 100% !important;
            }

        }
    </style>
</head>
<body>
    <nav class="navbar fixed-top navbar-expand-lg px-4">
        <div class="container-fluid">

            {{-- Brand --}}
            <a class="navbar-brand" href="{{ session('user') && (session('user')['role'] ?? '') === 'admin' ? route('admin.dashboard') : route('orangtua.dashboard') }}">
                <img src="{{ asset('images/logo2.png') }}" alt="Stunting Logo" style="height:55px;">
            </a>

            {{-- NAVBAR MENU --}}
            @if(session('user'))
            @php
                $user = session('user');
                $role = $user['role'] ?? 'orangtua';
                $activeChildName = session('active_child_name');
            @endphp

            <div class="d-flex w-100 justify-content-between align-items-center">
                {{-- Menu Tengah --}}
                <ul class="navbar-nav d-flex flex-row flex-wrap gap-3 align-items-center mx-auto mb-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $role === 'admin' ? route('admin.dashboard') : route('orangtua.dashboard') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $role === 'admin' ? route('admin.detections.index') : route('orangtua.detections.create') }}">Deteksi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $role === 'admin' ? route('admin.nutrition.index') : route('orangtua.nutritionUs.index') }}">Menu</a>
                    </li>
                    <li class="nav-item">
                        @if($role === 'orangtua')
                            <a class="nav-link" href="{{ route('bmi') }}">BMI</a>
                        @endif
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $role === 'admin' ? route('admin.artikel.index') : route('orangtua.artikel.index') }}">Artikel</a>
                    </li>
                    {{-- Imunisasi feature removed --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $role === 'admin' ? route('admin.perkembangan.children.index') : route('orangtua.tahapan_perkembangan.index') }}">Perkembangan</a>
                    </li>
                    @if($role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.ntob.index') }}">NTOB</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.skdn.index') }}">SKDN</a>
                    </li>
                    @endif
                </ul>

                {{-- Dropdown Kanan --}}
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Hi, {{ $user['nama_lengkap'] ?? $user['username'] ?? 'Pengguna' }}
                        @if($activeChildName)
                            <span class="badge ms-2 text-white" style="background-color: #00a896; font-size: 0.75rem; vertical-align: middle;">
                                Anak Aktif: {{ $activeChildName }}
                            </span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile') }}">Profil</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </nav>

    {{-- FLOATING ALERTS --}}
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999; margin-top: 70px;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert" style="background-color: #d1fae5; color: #065f46;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert" style="background-color: #fee2e2; color: #991b1b;">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    {{-- MAIN CONTENT --}}
    <main class="container mt-4">
        <div class="content-card">

            @yield('content')
        </div>
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>