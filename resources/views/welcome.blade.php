<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cikampek Jajanan - Sistem Manajemen Stok</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --brand-yellow: #ffdf2b;
            --brand-yellow-soft: #fff6bf;
            --brand-red: #e21d2b;
            --brand-red-dark: #b8131e;
            --text-main: #222;
            --text-soft: #5d5d5d;
            --panel: #ffffff;
            --bg-soft: #fffdf3;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: var(--text-main);
            background: var(--bg-soft);
        }

        .topbar {
            background: linear-gradient(90deg, var(--brand-yellow) 0%, #ffe96d 100%);
            border-bottom: 2px solid var(--brand-red);
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--brand-red) !important;
            letter-spacing: 0.2px;
        }

        .navbar-brand i { margin-right: 8px; }

        .nav-link {
            font-weight: 600;
            color: var(--text-main) !important;
        }

        .btn-brand {
            background: var(--brand-red);
            border: 0;
            color: #fff;
            border-radius: 999px;
            padding: 10px 20px;
            font-weight: 600;
        }

        .btn-brand:hover {
            background: var(--brand-red-dark);
            color: #fff;
        }

        .btn-brand-outline {
            border: 2px solid var(--brand-red);
            color: var(--brand-red);
            border-radius: 999px;
            padding: 8px 18px;
            font-weight: 600;
            background: transparent;
        }

        .btn-brand-outline:hover {
            background: var(--brand-red);
            color: #fff;
        }

        .hero {
            position: relative;
            padding: 84px 16px 64px;
            background: radial-gradient(circle at 85% 10%, rgba(226, 29, 43, 0.12), transparent 35%),
                        linear-gradient(135deg, var(--brand-yellow-soft) 0%, #fff9d7 100%);
        }

        .hero-wrap {
            max-width: 1080px;
            margin: 0 auto;
            text-align: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            color: var(--brand-red);
            border: 1px solid rgba(226, 29, 43, 0.2);
            border-radius: 999px;
            padding: 8px 14px;
            font-weight: 600;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .hero h1 {
            margin: 0;
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 6vw, 3.8rem);
            color: var(--brand-red);
            line-height: 1.1;
        }

        .hero p {
            margin: 14px auto 0;
            max-width: 700px;
            color: var(--text-soft);
            font-size: 1.02rem;
        }

        .hero-cta {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .section {
            max-width: 1120px;
            margin: 0 auto;
            padding: 64px 16px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 36px;
        }

        .section-title h2 {
            margin: 0;
            font-family: 'Playfair Display', serif;
            color: var(--brand-red);
            font-size: clamp(1.7rem, 4vw, 2.6rem);
        }

        .section-title p {
            margin: 10px 0 0;
            color: var(--text-soft);
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .feature-card {
            background: var(--panel);
            border-radius: 16px;
            border: 1px solid #f1f1f1;
            border-top: 4px solid var(--brand-red);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
            padding: 22px;
        }

        .feature-card i {
            color: var(--brand-red);
            font-size: 1.8rem;
            margin-bottom: 12px;
        }

        .feature-card h3 {
            margin: 0 0 8px;
            font-size: 1.08rem;
            font-weight: 700;
        }

        .feature-card p {
            margin: 0;
            color: var(--text-soft);
            font-size: 0.95rem;
            line-height: 1.65;
        }

        .contact-box {
            background: #fff;
            border: 1px solid #f2f2f2;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            padding: 28px;
            max-width: 700px;
            margin: 0 auto;
            text-align: center;
        }

        .contact-item {
            margin-top: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .contact-item a {
            color: var(--brand-red);
            font-weight: 600;
            text-decoration: none;
        }

        .contact-item a:hover { text-decoration: underline; }

        .site-footer {
            margin-top: 40px;
            background: #1f1f1f;
            color: #efefef;
            border-top: 3px solid var(--brand-red);
        }

        .site-footer .inner {
            max-width: 1120px;
            margin: 0 auto;
            padding: 22px 16px;
            text-align: center;
            font-size: 0.95rem;
        }

        .site-footer strong { color: var(--brand-yellow); }

        @media (max-width: 992px) {
            .feature-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 640px) {
            .feature-grid { grid-template-columns: 1fr; }
            .hero { padding-top: 62px; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg topbar sticky-top">
        <div class="container-fluid px-3 px-md-4">
            <a class="navbar-brand" href="{{ url('/') }}"><i class="fas fa-drumstick-bite"></i>Cikampek Jajanan</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#landingNav" aria-controls="landingNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="landingNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-brand btn-sm">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        @if (Route::has('register'))
                            <li class="nav-item"><a class="btn btn-brand btn-sm" href="{{ route('register') }}">Daftar</a></li>
                        @endif
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-wrap">
            <div class="hero-badge"><i class="fas fa-utensils"></i> Sekali Jajan, Jajan Teroos!!!</div>
            <h1>Cikampek Jajanan</h1>
            <p>Sistem manajemen stok yang rapi untuk mengelola barang masuk, barang keluar, dan laporan bisnis harian.</p>

            <div class="hero-cta">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-brand"><i class="fas fa-chart-line me-2"></i>Buka Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-brand"><i class="fas fa-sign-in-alt me-2"></i>Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-brand-outline"><i class="fas fa-user-plus me-2"></i>Daftar Akun</a>
                    @endif
                @endauth
            </div>
        </div>
    </section>

    <section id="fitur" class="section">
        <div class="section-title">
            <h2>Fitur Utama</h2>
            <p>Dirancang supaya alur kerja stok lebih teratur dan mudah dipantau.</p>
        </div>

        <div class="feature-grid">
            <article class="feature-card">
                <i class="fas fa-boxes"></i>
                <h3>Data Barang</h3>
                <p>Kelola daftar barang secara terpusat lengkap dengan detail yang dibutuhkan operasional harian.</p>
            </article>
            <article class="feature-card">
                <i class="fas fa-arrow-down"></i>
                <h3>Barang Masuk</h3>
                <p>Catat pemasukan stok secara akurat untuk menjaga ketersediaan barang dan histori transaksi.</p>
            </article>
            <article class="feature-card">
                <i class="fas fa-arrow-up"></i>
                <h3>Barang Keluar</h3>
                <p>Pantau pengeluaran stok agar kontrol persediaan tetap stabil dan mudah diaudit.</p>
            </article>
            <article class="feature-card">
                <i class="fas fa-chart-pie"></i>
                <h3>Dashboard Ringkas</h3>
                <p>Lihat ringkasan kondisi stok dan aktivitas terkini dalam tampilan visual yang jelas.</p>
            </article>
            <article class="feature-card">
                <i class="fas fa-file-alt"></i>
                <h3>Laporan</h3>
                <p>Buat dan tinjau laporan stok untuk mendukung keputusan operasional yang lebih cepat.</p>
            </article>
            <article class="feature-card">
                <i class="fas fa-user-shield"></i>
                <h3>Akses Aman</h3>
                <p>Autentikasi pengguna dan pembagian peran membantu menjaga keamanan data bisnis.</p>
            </article>
        </div>
    </section>

    <section id="kontak" class="section" style="padding-top: 0;">
        <div class="section-title">
            <h2>Kontak</h2>
            <p>Hubungi Cikampek Jajanan untuk informasi lebih lanjut.</p>
        </div>

        <div class="contact-box">
            <h3 class="mb-1" style="color: var(--brand-red); font-family: 'Playfair Display', serif;">Cikampek Jajanan</h3>
            <div class="contact-item"><i class="fab fa-whatsapp" style="color: var(--brand-red);"></i><a href="https://wa.me/6208229859222" target="_blank">0822 9859 2222</a></div>
            <div class="contact-item"><i class="fab fa-instagram" style="color: var(--brand-red);"></i><a href="https://instagram.com/jajanancikampek" target="_blank">@jajanancikampek</a></div>
        </div>
    </section>

    <footer class="site-footer">
        <div class="inner">Copyright 2024 <strong>Cikampek Jajanan</strong>. Sistem Manajemen Stok.</div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
