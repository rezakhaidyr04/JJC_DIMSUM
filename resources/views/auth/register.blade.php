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
            --brand-yellow: #ffdf2b;
            --brand-red: #e21d2b;
            --brand-red-dark: #b8131e;
            --text-main: #222;
            --text-soft: #5d5d5d;
            --panel: #fff;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at 85% 15%, rgba(226, 29, 43, 0.14), transparent 35%),
                        linear-gradient(135deg, #fff7c7 0%, var(--brand-yellow) 100%);
            display: grid;
            place-items: center;
            padding: 20px;
        }

        .auth-card {
            width: 100%;
            max-width: 470px;
            background: var(--panel);
            border-radius: 18px;
            border: 1px solid #f0f0f0;
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.12);
            overflow: hidden;
        }

        .auth-head {
            background: linear-gradient(135deg, var(--brand-red) 0%, var(--brand-red-dark) 100%);
            color: #fff;
            text-align: center;
            padding: 28px 20px;
        }

        .auth-head h1 {
            margin: 6px 0 0;
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
            border: 1px solid #dcdcdc;
            padding: 10px 12px;
            font-size: 0.95rem;
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
    <div class="auth-card">
        <div class="auth-head">
            <i class="fas fa-drumstick-bite fa-2x"></i>
            <h1>Cikampek Jajanan</h1>
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
