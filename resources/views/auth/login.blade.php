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

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at 85% 14%, rgba(212, 164, 55, 0.18), transparent 30%),
                        linear-gradient(145deg, #3a1f1f 0%, var(--brand-red-dark) 42%, var(--brand-red) 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .page-header {
            width: 100%;
            max-width: 430px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            color: #f8efe0;
            margin-bottom: 14px;
            text-align: center;
        }

        .page-header-logo {
            width: 56px;
            height: 56px;
            border-radius: 999px;
            border: 1px solid rgba(255, 212, 0, 0.55);
            background: rgba(255, 255, 255, 0.16);
            padding: 5px;
            object-fit: contain;
        }

        .page-header-title {
            margin: 0;
            font-family: 'Playfair Display', serif;
            font-size: 1.55rem;
            line-height: 1.1;
        }

        .auth-card {
            width: 100%;
            max-width: 430px;
            background: var(--panel);
            border-radius: 18px;
            border: 1px solid #cfb27a;
            box-shadow: 0 18px 36px rgba(24, 12, 12, 0.28);
            overflow: hidden;
        }

        .auth-head {
            background: linear-gradient(135deg, #fff176 0%, var(--brand-yellow) 55%, #ffc400 100%);
            color: var(--brand-red-dark);
            text-align: center;
            padding: 28px 20px;
            border-bottom: 1px solid rgba(143, 27, 36, 0.28);
        }

        .auth-head h1 {
            margin: 0;
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
        }

        .auth-head p {
            margin: 6px 0 0;
            color: #5f1e20;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .auth-body {
            padding: 24px;
            background: linear-gradient(180deg, #fff5d7 0%, #ffefc2 100%);
        }

        .form-label {
            color: var(--text-main);
            font-size: 0.92rem;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid var(--field-border);
            padding: 10px 12px;
            font-size: 0.95rem;
            background: var(--field-bg);
            color: var(--text-main);
        }

        .form-control:focus {
            border-color: var(--field-border-focus);
            box-shadow: 0 0 0 0.2rem rgba(255, 212, 0, 0.2);
            background: #fffdf3;
        }

        .form-control.is-invalid {
            border-color: var(--brand-red);
            box-shadow: none;
        }

        .invalid-feedback {
            color: var(--brand-red-dark);
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .form-check-input {
            border-color: #c6b7a6;
            background-color: #fffdf8;
            margin-top: 0.2rem;
        }

        .form-check-input:checked {
            background-color: var(--brand-yellow);
            border-color: #e0bb00;
        }

        .form-check-input:focus {
            border-color: var(--field-border-focus);
            box-shadow: 0 0 0 0.2rem rgba(255, 212, 0, 0.2);
        }

        .form-check-label {
            color: var(--text-main);
            font-weight: 500;
        }

        .btn-brand {
            width: 100%;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, #fff176 0%, var(--brand-yellow) 55%, #ffc400 100%);
            color: #5f1e20;
            font-weight: 600;
            padding: 11px 14px;
            box-shadow: 0 8px 18px rgba(83, 55, 12, 0.26);
        }

        .btn-brand:hover {
            background: linear-gradient(135deg, #ffef5c 0%, #ffd000 100%);
            color: #4a1719;
        }

        .alert-danger {
            border: 1px solid rgba(198, 40, 51, 0.24);
            background: linear-gradient(135deg, #fff3f0 0%, #ffe8de 100%);
            color: #7f1620;
            border-radius: 12px;
        }

        .auth-foot {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #ece0d2;
            text-align: center;
            color: var(--text-soft);
            font-size: 0.92rem;
        }

        .auth-foot a {
            color: var(--brand-red);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-foot a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <header class="page-header" aria-label="Header login">
        <img src="{{ asset('images/logo-login.png') }}" alt="Logo Jajanan Cikampek" class="page-header-logo">
        <h2 class="page-header-title">Jajanan Cikampek</h2>
    </header>

    <div class="auth-card">
        <div class="auth-head">
            <h1>Masuk Akun</h1>
            <p>Masuk ke sistem</p>
        </div>

        <div class="auth-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Login gagal.</strong>
                    <ul class="mb-0 mt-2 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
                    @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" id="remember" name="remember" class="form-check-input">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>

                <button type="submit" class="btn btn-brand"><i class="fas fa-sign-in-alt me-2"></i>Masuk</button>
            </form>

            <div class="auth-foot">
                Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
