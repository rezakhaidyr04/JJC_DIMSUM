<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Stok Rendah</title>
</head>
<body style="margin:0;padding:0;background:#f6f7fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <div style="max-width:720px;margin:0 auto;padding:24px;">
        <div style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(17,24,39,.08);border:1px solid #e5e7eb;">
            <div style="background:linear-gradient(135deg,#dc2626,#991b1b);color:#fff;padding:24px 28px;">
                <div style="font-size:20px;font-weight:700;margin-bottom:6px;">Notifikasi Stok Rendah / Habis</div>
                <div style="font-size:14px;opacity:.95;">JJC DIMSUM</div>
            </div>

            <div style="padding:28px;">
                <p style="margin:0 0 8px;font-size:15px;">Halo Owner,</p>
                <p style="margin:0 0 16px;font-size:15px;line-height:1.6;">
                    Berikut daftar barang yang stoknya sudah <strong>rendah</strong> atau <strong>habis</strong>.
                </p>

                <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:12px;padding:14px 16px;margin-bottom:20px;">
                    <div style="font-size:14px;color:#6b7280;">Tanggal</div>
                    <div style="font-size:16px;font-weight:700;color:#111827;">{{ $dateLabel }}</div>
                    <div style="font-size:14px;color:#6b7280;margin-top:8px;">Waktu</div>
                    <div style="font-size:16px;font-weight:700;color:#111827;">{{ $timeLabel }}</div>
                </div>

                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font-size:14px;">
                    <thead>
                        <tr>
                            <th align="left" style="padding:12px;border-bottom:1px solid #e5e7eb;background:#f9fafb;">Nama Barang</th>
                            <th align="center" style="padding:12px;border-bottom:1px solid #e5e7eb;background:#f9fafb;">Stok</th>
                            <th align="center" style="padding:12px;border-bottom:1px solid #e5e7eb;background:#f9fafb;">Minimal</th>
                            <th align="center" style="padding:12px;border-bottom:1px solid #e5e7eb;background:#f9fafb;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td style="padding:12px;border-bottom:1px solid #eef2f7;">{{ $item['nama_barang'] }}</td>
                                <td align="center" style="padding:12px;border-bottom:1px solid #eef2f7;">{{ $item['stok'] }}</td>
                                <td align="center" style="padding:12px;border-bottom:1px solid #eef2f7;">{{ $item['stok_min'] }}</td>
                                <td align="center" style="padding:12px;border-bottom:1px solid #eef2f7;">
                                    <span style="display:inline-block;padding:6px 10px;border-radius:999px;background:{{ $item['status_label'] === 'HABIS' ? '#fee2e2' : '#fef3c7' }};color:{{ $item['status_label'] === 'HABIS' ? '#b91c1c' : '#b45309' }};font-weight:700;">
                                        {{ $item['status_label'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <p style="margin:20px 0 0;font-size:14px;line-height:1.6;color:#4b5563;">
                    Silakan lakukan pengecekan dan pengisian stok secepatnya agar operasional tetap lancar.
                </p>
            </div>
        </div>
    </div>
</body>
</html>