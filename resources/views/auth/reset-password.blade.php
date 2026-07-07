<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Password Baru - Jajanan Cikampek</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --brand-primary-dark: #8f1b24;
            --brand-surface: rgba(255, 255, 255, 0.2);
            --brand-border: rgba(255, 255, 255, 0.24);
            --brand-text: #ffffff;
            --brand-muted: rgba(255, 255, 255, 0.86);
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Poppins', sans-serif; min-height: 100vh; overflow-x: hidden; background: #0f1720; }
        .login-wrapper { min-height: 100vh; display: flex; }
        .login-left { flex: 1; position: relative; overflow: hidden; }
        .login-left::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(0,0,0,0.18), rgba(0,0,0,0.42)); z-index: 1; }
        .login-left img { width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; }
        .login-right { width: min(100%, 460px); display: flex; align-items: center; justify-content: center; padding: 32px 24px; background: linear-gradient(180deg, #3f474f 0%, #2f363e 100%); position: relative; }
        .login-right::before,
        .login-right::after { content: ''; position: absolute; border-radius: 50%; filter: blur(2px); pointer-events: none; }
        .login-right::before { width: 220px; height: 220px; background: radial-gradient(circle, rgba(181,31,45,0.16) 0%, rgba(181,31,45,0) 72%); top: -60px; right: -90px; }
        .login-right::after { width: 280px; height: 280px; background: radial-gradient(circle, rgba(255,255,255,0.07) 0%, rgba(255,255,255,0) 72%); bottom: -120px; left: -100px; }
        .auth-container { width: 100%; max-width: 380px; position: relative; z-index: 2; }
        .auth-logo-section { text-align: center; margin-bottom: 18px; }
        .page-header-logo { width: 78px; height: 78px; border-radius: 50%; object-fit: cover; border: 4px solid rgba(255,255,255,0.88); box-shadow: 0 12px 26px rgba(0,0,0,0.25); background: #fff; margin-bottom: 14px; }
        .auth-subtitle { color: var(--brand-text); font-size: 1.22rem; font-weight: 800; letter-spacing: 0.8px; margin: 0 0 8px; }
        .auth-description { color: var(--brand-muted); font-size: 0.92rem; line-height: 1.65; margin: 0; }
        .auth-card { background: var(--brand-surface); backdrop-filter: blur(18px); -webkit-backdrop-filter: blur(18px); border: 1px solid var(--brand-border); border-radius: 22px; box-shadow: 0 18px 42px rgba(0,0,0,0.18); overflow: hidden; }
        .auth-body { padding: 28px 26px 26px; }
        .alert { border: none; border-radius: 14px; font-size: 0.9rem; }
        .alert-info { background: rgba(255,255,255,0.16); color: #fff; }
        .alert-danger { background: rgba(181,31,45,0.16); color: #fff; }
        .form-group { margin-bottom: 18px; }
        .form-label { color: #fff; font-weight: 700; font-size: 0.94rem; margin-bottom: 8px; }
        .form-control { border-radius: 14px; border: 1px solid rgba(255,255,255,0.14); background: rgba(255,255,255,0.92); color: #1f2937; padding: 14px 15px; font-size: 0.94rem; }
        .form-control:focus { border-color: #f0b0b7; box-shadow: 0 0 0 0.2rem rgba(181,31,45,0.18); background: #fff; }
        .invalid-feedback { display: block; color: #ffd5d8; font-size: 0.82rem; margin-top: 6px; }
        .password-field { position: relative; }
        .password-field .form-control { padding-right: 54px; }
        .password-toggle { position: absolute; top: 50%; right: 16px; transform: translateY(-50%); background: transparent; border: none; color: #6b7280; padding: 4px; line-height: 1; }
        .btn-brand { width: 100%; border: none; border-radius: 14px; padding: 14px 16px; background: linear-gradient(180deg, #c61f2f 0%, var(--brand-primary-dark) 100%); color: #fff; font-weight: 800; font-size: 0.98rem; letter-spacing: 0.7px; margin-top: 8px; }
        .btn-brand:hover,
        .btn-brand:focus { color: #fff; transform: translateY(-1px); box-shadow: 0 10px 22px rgba(143, 27, 36, 0.28); }
        .auth-foot { text-align: center; color: #eef2f7; font-size: 0.9rem; margin-top: 18px; }
        .auth-foot a { color: #fff; font-weight: 700; text-decoration: none; }
        .auth-foot a:hover,
        .auth-foot a:focus { text-decoration: underline; }
        @media (max-width: 991.98px) { .login-wrapper { flex-direction: column; } .login-left { min-height: 260px; } .login-right { width: 100%; min-height: calc(100vh - 260px); } }
        @media (max-width: 575.98px) { .login-left { min-height: 210px; } .auth-body { padding: 24px 18px 20px; } .login-right { padding: 24px 16px; } }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-left"><img src="{{ asset('images/login-bg.jpeg') }}" alt="Cikampek Jajanan"></div>
        <div class="login-right">
            <div class="auth-container">
                <div class="auth-logo-section">
                    <img src="{{ asset('images/logo-login.png') }}" alt="Logo Jajanan Cikampek" class="page-header-logo">
                    <p class="auth-subtitle">PASSWORD BARU</p>
                    <p class="auth-description">Buat kata sandi baru untuk akun Anda.</p>
                </div>
                <div class="auth-card">
                    <div class="auth-body">
                        @if (session('status'))
                            <div class="alert alert-info">{{ session('status') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Gagal memperbarui password.</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email Anda" required autofocus>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label for="password" class="form-label">Kata Sandi Baru</label>
                                <div class="password-field">
                                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password baru" required>
                                    <button type="button" class="password-toggle" data-target="password" aria-label="Lihat password" aria-pressed="false"><i class="fa-regular fa-eye"></i></button>
                                </div>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <div class="password-field">
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Ulangi password baru" required>
                                    <button type="button" class="password-toggle" data-target="password_confirmation" aria-label="Lihat password" aria-pressed="false"><i class="fa-regular fa-eye"></i></button>
                                </div>
                                @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <button type="submit" class="btn btn-brand">SIMPAN PASSWORD BARU</button>
                        </form>
                        <div class="auth-foot"><a href="{{ route('login') }}">Kembali ke login</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.password-toggle').forEach(function (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                var targetId = this.getAttribute('data-target');
                var input = document.getElementById(targetId);

                if (!input) {
                    return;
                }

                var isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';

                var icon = this.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-eye', !isHidden);
                    icon.classList.toggle('fa-eye-slash', isHidden);
                }

                this.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
                this.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Lihat password');
            });
        });
    </script>
</body>
</html>