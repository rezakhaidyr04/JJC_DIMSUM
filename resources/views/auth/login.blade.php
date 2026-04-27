<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cikampek Jajanan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-yellow: #ffd400;
            --brand-red: #c62833;
            --brand-red-dark: #8f1b24;
            --text-main: #221b16;
            --text-soft: #6f6155;
            --panel: #fff3cc;
            --field-bg: #fff9e8;
            --field-border: #d4bf92;
            --field-border-focus: #ffcf00;
        }

        * { box-sizing: border-box; }

        html {
            width: 100%;
            overflow-x: hidden;
            -webkit-text-size-adjust: 100%;
        }

        body {
            margin: 0;
            min-height: 100vh;
            width: 100%;
            overflow-x: hidden;
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #ffffff;
            display: flex;
            padding: 0;
        }

        .login-wrapper {
            display: flex;
            width: 100%;
            min-height: 100dvh;
        }

        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #c62833 0%, #8f1b24 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-left::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 1;
        }

        .login-left img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            position: relative;
            z-index: 0;
        }

        .login-right {
            flex: 1;
            background: linear-gradient(135deg, #ffd400 0%, #ffe680 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 30px;
            overflow-y: auto;
            min-width: 0;
        }

        .auth-container {
            width: 100%;
            max-width: 380px;
        }

        .auth-logo-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .page-header-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid var(--brand-red);
            background: rgba(255, 255, 255, 0.9);
            padding: 8px;
            object-fit: contain;
            display: inline-block;
            margin-bottom: 16px;
            box-shadow: 0 4px 12px rgba(198, 40, 51, 0.2);
        }

        .auth-title {
            margin: 0 0 8px;
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: var(--text-main);
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .auth-subtitle {
            margin: 0 0 8px;
            color: var(--text-main);
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .auth-description {
            margin: 0;
            color: var(--text-soft);
            font-size: 13px;
            font-weight: 500;
            line-height: 1.5;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            border: none;
            box-shadow: 0 8px 24px rgba(24, 12, 12, 0.15);
            overflow: hidden;
        }

        .auth-head {
            background: linear-gradient(135deg, var(--brand-red) 0%, var(--brand-red-dark) 100%);
            color: #ffffff;
            text-align: center;
            padding: 24px;
            border-bottom: none;
        }

        .auth-head h1 {
            margin: 0;
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.5px;
        }

        .auth-head p {
            margin: 6px 0 0;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .auth-body {
            padding: 28px;
            background: rgba(255, 255, 255, 0.95);
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            color: var(--text-main);
            font-size: 0.92rem;
            font-weight: 700;
            margin-bottom: 6px;
            display: block;
            letter-spacing: 0.3px;
        }

        .form-control {
            border-radius: 8px;
            border: 1.5px solid var(--field-border);
            padding: 11px 13px;
            min-height: 44px;
            font-size: 0.92rem;
            background: #ffffff;
            color: var(--text-main);
            width: 100%;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--brand-red);
            box-shadow: 0 0 0 3px rgba(198, 40, 51, 0.1);
            background: #ffffff;
        }

        .form-control::placeholder {
            color: #b8b8b8;
        }

        .form-control.is-invalid {
            border-color: var(--brand-red);
            box-shadow: none;
        }

        .invalid-feedback {
            color: var(--brand-red);
            font-size: 0.82rem;
            margin-top: 4px;
            display: block;
            font-weight: 500;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 16px 0 20px 0;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 1.5px solid var(--field-border);
            border-radius: 4px;
            background-color: #ffffff;
            margin: 0;
            cursor: pointer;
            accent-color: var(--brand-red);
        }

        .form-check-input:checked {
            background-color: var(--brand-red);
            border-color: var(--brand-red);
        }

        .form-check-label {
            color: var(--text-main);
            font-weight: 500;
            font-size: 0.9rem;
            cursor: pointer;
            margin: 0;
        }

        .btn-brand {
            width: 100%;
            border: 0;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--brand-red) 0%, var(--brand-red-dark) 100%);
            color: #ffffff;
            font-weight: 700;
            padding: 13px 16px;
            min-height: 46px;
            font-size: 0.98rem;
            box-shadow: 0 4px 14px rgba(198, 40, 51, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            margin-top: 8px;
            letter-spacing: 0.5px;
        }

        .btn-brand:hover {
            background: linear-gradient(135deg, #a91f26 0%, #6f1419 100%);
            color: #ffffff;
            box-shadow: 0 6px 18px rgba(198, 40, 51, 0.4);
            transform: translateY(-2px);
        }

        .btn-brand:active {
            transform: translateY(0);
        }

        .alert-danger {
            border: 1.5px solid rgba(198, 40, 51, 0.3);
            background: rgba(198, 40, 51, 0.08);
            color: #8f1b24;
            border-radius: 8px;
            padding: 12px;
            font-size: 0.88rem;
            margin-bottom: 16px;
        }

        .alert-danger strong {
            color: var(--brand-red);
        }

        .alert-danger ul {
            margin: 6px 0 0;
            padding-left: 18px;
        }

        .alert-danger li {
            margin: 3px 0;
        }

        .auth-foot {
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid #e8e8e8;
            text-align: center;
            color: var(--text-soft);
            font-size: 0.88rem;
        }

        .auth-foot a {
            color: var(--brand-red);
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .auth-foot a:hover {
            text-decoration: underline;
            color: var(--brand-red-dark);
        }

        @media (max-width: 991.98px) {
            .login-wrapper {
                flex-direction: column;
            }

            .login-left {
                min-height: 220px;
            }

            .login-right {
                padding: 28px 20px;
            }

            .auth-container {
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .login-left {
                min-height: 180px;
            }

            .page-header-logo {
                width: 70px;
                height: 70px;
            }

            .auth-title {
                font-size: 22px;
            }
        }

        @media (max-width: 576px) {
            .login-left {
                display: none;
            }

            .login-right {
                padding: 18px 12px;
                justify-content: flex-start;
            }

            .auth-body {
                padding: 20px 16px;
            }

            .form-label {
                font-size: 0.88rem;
            }

            .btn-brand {
                font-size: 0.92rem;
                letter-spacing: 0.3px;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Left Side - Background Image -->
        <div class="login-left">
            <img src="{{ asset('images/login-bg.jpeg') }}" alt="Cikampek Jajanan">
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-right">
            <div class="auth-container">
                <!-- Logo & Title -->
                <div class="auth-logo-section">
                    <img src="{{ asset('images/logo-login.png') }}" alt="Logo Jajanan Cikampek" class="page-header-logo">
                    <p class="auth-subtitle">SELAMAT DATANG KEMBALI!</p>
                    <p class="auth-description">Masuk untuk menikmati jajanan terbaik dari Cikampek.</p>
                </div>

                <!-- Auth Card -->
                <div class="auth-card">
                    <div class="auth-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Login gagal.</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group">
                                <label for="email" class="form-label">Nomor WhatsApp atau Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan nomor/email Anda" required autofocus>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">Kata Sandi</label>
                                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan kata sandi Anda" required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-check">
                                <input type="checkbox" id="remember" name="remember" class="form-check-input">
                                <label class="form-check-label" for="remember">Ingat saya</label>
                            </div>

                            <button type="submit" class="btn btn-brand">MASUK</button>
                        </form>

                        <div class="auth-foot">
                            Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
