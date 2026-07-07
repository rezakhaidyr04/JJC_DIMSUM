<!DOCTYPE html>
<html lang="id">
<body style="margin:0;padding:0;background:#f6f4ef;font-family:Arial,Helvetica,sans-serif;color:#2a211d;">
    <div style="max-width:640px;margin:0 auto;padding:24px;">
        <div style="background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #eadfcf;">
            <div style="background:linear-gradient(135deg,#c62833,#8f1b24);color:#fff;padding:24px 28px;">
                <h1 style="margin:0;font-size:22px;">Kode OTP Registrasi JJC DIMSUM</h1>
            </div>
            <div style="padding:28px;line-height:1.7;font-size:15px;">
                <p style="margin-top:0;">Halo {{ $user->name }},</p>
                <p>Akun Anda sudah dibuat. Gunakan kode OTP berikut untuk verifikasi akun sebelum login:</p>
                <div style="margin:24px 0;padding:18px 20px;text-align:center;background:#fff7e2;border:1px dashed #d7bc72;border-radius:14px;">
                    <div style="font-size:32px;letter-spacing:8px;font-weight:700;color:#8f1b24;">{{ $otpCode }}</div>
                </div>
                <p>Kode ini berlaku selama 15 menit. Setelah berhasil verifikasi, Anda bisa login ke dashboard.</p>
                <p style="margin-bottom:0;color:#6f6155;">Jika Anda tidak merasa membuat akun ini, abaikan email ini.</p>
            </div>
        </div>
    </div>
</body>
</html>