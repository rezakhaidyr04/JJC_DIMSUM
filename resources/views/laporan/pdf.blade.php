<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok - Cikampek Jajanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111;
            margin: 18px;
        }

        .header {
            text-align: center;
            margin-bottom: 14px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 4px 0;
            font-size: 12px;
            color: #374151;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 8px;
            font-size: 12px;
        }

        th {
            background: #f3f4f6;
            font-weight: bold;
            text-align: center;
        }

        td.center {
            text-align: center;
        }

        .hint {
            margin-top: 10px;
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
        <h1>Laporan Stok Barang - Cikampek Jajanan</h1>
        <p>Periode: {{ $tanggalMulai ?: '-' }} s/d {{ $tanggalSelesai ?: '-' }}</p>
        <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th>Nama Barang</th>
                <th style="width: 14%;">Stok Awal</th>
                <th style="width: 14%;">Barang Masuk</th>
                <th style="width: 14%;">Barang Keluar</th>
                <th style="width: 14%;">Stok Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $item)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td>{{ $item['nama_barang'] }}</td>
                    <td class="center">{{ $item['stok_awal'] }}</td>
                    <td class="center">{{ $item['barang_masuk'] }}</td>
                    <td class="center">{{ $item['barang_keluar'] }}</td>
                    <td class="center">{{ $item['stok_akhir'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="hint">Tips: gunakan Save as PDF dari dialog print browser.</div>

    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
