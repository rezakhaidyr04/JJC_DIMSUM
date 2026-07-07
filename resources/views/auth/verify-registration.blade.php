<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - Cikampek Jajanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { min-height: 100vh; background: linear-gradient(135deg, #f8f1e8 0%, #efe4d3 55%, #f7d7d5 100%); font-family: Arial, sans-serif; }
        .otp-shell { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
        .otp-card { width: 100%; max-width: 520px; background: rgba(255,255,255,0.9); border-radius: 24px; box-shadow: 0 24px 60px rgba(70, 36, 24, 0.15); overflow: hidden; border: 1px solid rgba(198,40,51,0.12); }
        .otp-head { background: linear-gradient(135deg, #c62833, #8f1b24); color: #fff; padding: 28px; }
        .otp-head h1 { margin: 0; font-size: 1.8rem; font-weight: 700; }
        .otp-head p { margin: 8px 0 0; opacity: 0.9; }
        .otp-body { padding: 28px; }
        .form-label { font-weight: 700; color: #4a372e; }
        .form-control { min-height: 48px; border-radius: 12px; }
        .btn-brand { background: linear-gradient(135deg, #c62833, #8f1b24); color: #fff; border: 0; border-radius: 12px; min-height: 48px; font-weight: 700; }
        .btn-brand:hover { color: #fff; opacity: 0.95; }
        .btn-soft { background: #fff3cc; color: #6b4e00; border: 1px solid #ead08d; border-radius: 12px; min-height: 48px; font-weight: 700; }
        .alert-info { background: #fef7e8; border-color: #f0dcad; color: #7b5a00; }
        .small-note { color: #7c6c5e; font-size: 0.95rem; }
    </style>
</head>
<body>
    <div class="otp-shell">
        <div class="otp-card">
            <div class="otp-head">
                <h1>Verifikasi OTP</h1>
                <p>Masukkan kode OTP yang dikirim ke email Anda sebelum login.</p>
            </div>
            <div class="otp-body">
                @if (session('status'))
                    <div class="alert alert-info">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <p class="small-note mb-4">Akun baru belum bisa masuk ke dashboard sampai kode OTP berhasil diverifikasi.</p>

                <form method="POST" action="{{ route('registration.verify') }}" class="mb-3">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $email) }}" required>
                        @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="otp" class="form-label">Kode OTP</label>
                        <input type="text" id="otp" name="otp" class="form-control @error('otp') is-invalid @enderror" value="{{ old('otp') }}" maxlength="6" inputmode="numeric" autocomplete="one-time-code" required>
                        @error('otp')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-brand w-100">Verifikasi Sekarang</button>
                </form>

                <form method="POST" action="{{ route('registration.resend') }}" class="mb-3">
                    @csrf
                    <input type="hidden" name="email" value="{{ old('email', $email) }}">
                    <button type="submit" class="btn btn-soft w-100">Kirim Ulang OTP</button>
                </form>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color:#8f1b24;">Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>