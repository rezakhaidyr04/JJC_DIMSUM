<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Stok - Cikampek Jajanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111;
            margin: 16px;
            line-height: 1.35;
        }

        .header {
            margin-bottom: 12px;
            border: 1px solid #f1d1d4;
            border-top: 4px solid #cf202c;
            border-radius: 8px;
            background: #fff8f8;
            padding: 10px;
            overflow: hidden;
        }

        .header-left {
            float: left;
            width: 16%;
            text-align: center;
        }

        .header-right {
            float: left;
            width: 84%;
            text-align: center;
        }

        .logo {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            border: 1px solid #f6c8cc;
            object-fit: cover;
            background: #fff;
            margin-top: 3px;
        }

        .header h1 {
            margin: 2px 0 0;
            font-size: 19px;
            color: #9f1d28;
        }

        .header p {
            margin: 3px 0;
            font-size: 12px;
            color: #374151;
        }

        .clearfix {
            clear: both;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .legend-table {
            margin-bottom: 8px;
        }

        .legend-table td {
            border: 1px solid #f1d1d4;
            background: #fffafa;
            text-align: left;
            padding: 6px;
            font-size: 11px;
        }

        .legend-chip {
            display: inline-block;
            min-width: 42px;
            text-align: center;
            color: #fff;
            border-radius: 999px;
            font-size: 10px;
            font-weight: bold;
            padding: 1px 8px;
            margin-right: 6px;
        }

        .chip-masuk {
            background: #16a34a;
        }

        .chip-keluar {
            background: #d97706;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 7px 6px;
            font-size: 11px;
        }

        th {
            background: #cf202c;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }

        td.center {
            text-align: center;
        }

        .jenis-label {
            display: inline-block;
            min-width: 44px;
            text-align: center;
            color: #fff;
            border-radius: 999px;
            padding: 1px 8px;
            font-size: 10px;
            font-weight: bold;
        }

        .jenis-masuk {
            background: #16a34a;
        }

        .jenis-keluar {
            background: #d97706;
        }

        .footer-total {
            margin-top: 10px;
            border: 1px solid #f1d1d4;
            border-radius: 8px;
            background: #fff8f8;
            padding: 8px 10px;
            font-size: 11px;
        }

        .footer-total strong {
            color: #8f1b24;
        }

        .footer-note {
            margin-top: 6px;
            font-size: 10px;
            color: #6b7280;
            text-align: right;
        }

        .hint {
            margin-top: 8px;
            font-size: 11px;
            color: #6b7280;
            text-align: right;
        }

        @media print {
            .hint {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            @if(!empty($logoBase64))
                <img src="{{ $logoBase64 }}" alt="Logo Jajanan Cikampek" class="logo">
            @endif
        </div>
        <div class="header-right">
            <h1>Laporan Transaksi Stok</h1>
            <p><strong>Cikampek Jajanan</strong></p>
            <p>Periode: {{ $tanggalMulai ?: '-' }} s/d {{ $tanggalSelesai ?: '-' }}</p>
            <p>Tanggal Cetak Server: {{ now()->format('d-m-Y H:i:s') }} WIB</p>
        </div>
        <div class="clearfix"></div>
    </div>

    <table class="legend-table">
        <tr>
            <td>
                <span class="legend-chip chip-masuk">MASUK</span>
                Transaksi penambahan stok
            </td>
            <td>
                <span class="legend-chip chip-keluar">KELUAR</span>
                Transaksi pengurangan stok
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 11%;">Jenis</th>
                <th>Nama Barang</th>
                <th style="width: 11%;">Jumlah</th>
                <th style="width: 20%;">Nama Penginput</th>
                <th style="width: 19%;">Tanggal/Waktu Input</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksiRows as $item)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td class="center">
                        @if($item['jenis'] === 'Masuk')
                            <span class="jenis-label jenis-masuk">Masuk</span>
                        @else
                            <span class="jenis-label jenis-keluar">Keluar</span>
                        @endif
                    </td>
                    <td>{{ $item['nama_barang'] }}</td>
                    <td class="center">{{ $item['jumlah'] }}</td>
                    <td>{{ $item['nama_penginput'] }}</td>
                    <td class="center">{{ optional($item['waktu_input'])->format('d-m-Y H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Tidak ada data transaksi pada periode ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-total">
        <strong>Total Akumulasi Periode:</strong>
        Barang Masuk = <strong>{{ $totals['masuk'] }}</strong> |
        Barang Keluar = <strong>{{ $totals['keluar'] }}</strong> |
        Net Pergerakan Stok = <strong>{{ $totals['net'] }}</strong>
    </div>

    <div class="footer-note">Dokumen audit trail. Data penginput dan waktu input diambil dari server.</div>

    <div class="hint">Laporan berhasil dibuat dan siap diunduh.</div>


</body>
</html>
