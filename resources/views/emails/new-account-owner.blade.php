<!DOCTYPE html>
<html lang="id">
<body style="margin:0;padding:0;background:#f6f4ef;font-family:Arial,Helvetica,sans-serif;color:#2a211d;">
    <div style="max-width:640px;margin:0 auto;padding:24px;">
        <div style="background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #eadfcf;">
            <div style="background:linear-gradient(135deg,#3c3c3c,#111111);color:#fff;padding:24px 28px;">
                <h1 style="margin:0;font-size:22px;">Pemberitahuan Akun Baru</h1>
            </div>
            <div style="padding:28px;line-height:1.7;font-size:15px;">
                <p style="margin-top:0;">Halo Owner,</p>
                <p>Ada pendaftaran akun baru di sistem JJC DIMSUM.</p>
                <table style="width:100%;border-collapse:collapse;margin:20px 0 24px;">
                    <tr>
                        <td style="padding:10px 0;color:#6f6155;width:140px;">Nama</td>
                        <td style="padding:10px 0;font-weight:700;">{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding:10px 0;color:#6f6155;">Email</td>
                        <td style="padding:10px 0;font-weight:700;">{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td style="padding:10px 0;color:#6f6155;">OTP</td>
                        <td style="padding:10px 0;font-weight:700;letter-spacing:3px;">{{ $otpCode }}</td>
                    </tr>
                </table>
                <p style="margin-bottom:0;color:#6f6155;">Akun belum bisa dipakai sebelum pemilik email menyelesaikan verifikasi OTP.</p>
            </div>
        </div>
    </div>
</body>
</html>