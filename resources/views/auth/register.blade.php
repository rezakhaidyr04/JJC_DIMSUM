<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Cikampek Jajanan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-yellow: #d4a437;
            --brand-red: #c62833;
            --brand-red-dark: #8f1b24;
            --text-main: #221b16;
            --text-soft: #6f6155;
            --panel: #fffdf8;
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
            max-width: 470px;
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
            border: 1px solid rgba(212, 164, 55, 0.45);
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
            max-width: 470px;
            background: var(--panel);
            border-radius: 18px;
            border: 1px solid #e9dbc6;
            box-shadow: 0 18px 36px rgba(24, 12, 12, 0.28);
            overflow: hidden;
        }

        .auth-head {
            background: linear-gradient(135deg, var(--brand-red) 0%, var(--brand-red-dark) 100%);
            color: #fff;
            text-align: center;
            padding: 28px 20px;
            border-bottom: 1px solid rgba(212, 164, 55, 0.35);
        }

        .auth-head h1 {
            margin: 0;
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
        }

        .auth-head p {
            margin: 6px 0 0;
            opacity: 0.95;
            font-size: 0.95rem;
        }

        .auth-body { padding: 24px; }

        .form-label {
            color: var(--text-main);
            font-size: 0.92rem;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #d8cabc;
            padding: 10px 12px;
            font-size: 0.95rem;
            background: #fffdf9;
        }

        .form-control:focus {
            border-color: var(--brand-red);
            box-shadow: 0 0 0 0.2rem rgba(226, 29, 43, 0.14);
        }

        .btn-brand {
            width: 100%;
            border: 0;
            border-radius: 10px;
            background: var(--brand-red);
            color: #fff;
            font-weight: 600;
            padding: 11px 14px;
        }

        .btn-brand:hover {
            background: var(--brand-red-dark);
            color: #fff;
        }

        .auth-foot {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #efefef;
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
    <header class="page-header" aria-label="Header register">
        <img src="{{ asset('images/logo-login.png') }}" alt="Logo Cikampek Jajanan" class="page-header-logo">
        <h2 class="page-header-title">Cikampek Jajanan</h2>
    </header>

    <div class="auth-card">
        <div class="auth-head">
            <h1>Daftar Akun</h1>
            <p>Daftar akun baru</p>
        </div>

        <div class="auth-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Pendaftaran gagal.</strong>
                    <ul class="mb-0 mt-2 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required autofocus>
                    @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                    @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                    @error('password_confirmation')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-brand"><i class="fas fa-user-plus me-2"></i>Daftar</button>
            </form>

            <div class="auth-foot">
                Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
