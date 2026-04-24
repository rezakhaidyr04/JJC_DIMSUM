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
            margin-bottom: 12px;
            padding: 10px 10px 12px;
            border: 1px solid #f1d1d4;
            border-top: 4px solid #cf202c;
            border-radius: 8px;
            background: #fff8f8;
        }

        .brand-pill {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            border: 1px solid #f6c8cc;
            background: #fff1f2;
            color: #9f1d28;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #9f1d28;
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

        .summary-table {
            margin-top: 0;
            margin-bottom: 10px;
            table-layout: fixed;
        }

        .summary-table td {
            border: 1px solid #f1d1d4;
            background: #fffafa;
            text-align: center;
            padding: 7px 6px;
        }

        .summary-label {
            display: block;
            font-size: 10px;
            color: #9f1d28;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .summary-value {
            display: block;
            font-size: 14px;
            font-weight: bold;
            color: #7f1d1d;
        }

        .data-table {
            margin-top: 0;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 8px;
            font-size: 12px;
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

        .no-chip {
            display: inline-block;
            min-width: 18px;
            border-radius: 999px;
            padding: 1px 5px;
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #9f1239;
            font-weight: bold;
            font-size: 11px;
            line-height: 1.2;
        }

        .cell-chip {
            display: inline-block;
            min-width: 26px;
            border-radius: 999px;
            padding: 1px 7px;
            color: #fff;
            font-weight: bold;
            font-size: 11px;
            line-height: 1.2;
        }

        .cell-chip-muted {
            background: #6b7280;
        }

        .cell-chip-green {
            background: #16a34a;
        }

        .cell-chip-amber {
            background: #d97706;
        }

        .cell-chip-blue {
            background: #2563eb;
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
    @php
        $laporanCollection = collect($laporan);
        $totalBarang = $laporanCollection->count();
        $totalMasuk = $laporanCollection->sum('barang_masuk');
        $totalKeluar = $laporanCollection->sum('barang_keluar');
        $totalStokAkhir = $laporanCollection->sum('stok_akhir');
    @endphp

    <div class="header">
        <div class="brand-pill">Jajanan Cikampek</div>
        <h1>Laporan Stok Barang - Cikampek Jajanan</h1>
        <p>Periode: {{ $tanggalMulai ?: '-' }} s/d {{ $tanggalSelesai ?: '-' }}</p>
        <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</p>
    </div>

    <table class="summary-table">
        <tr>
            <td>
                <span class="summary-label">Total Barang</span>
                <span class="summary-value">{{ $totalBarang }}</span>
            </td>
            <td>
                <span class="summary-label">Total Masuk</span>
                <span class="summary-value">{{ $totalMasuk }}</span>
            </td>
            <td>
                <span class="summary-label">Total Keluar</span>
                <span class="summary-value">{{ $totalKeluar }}</span>
            </td>
            <td>
                <span class="summary-label">Total Stok Akhir</span>
                <span class="summary-value">{{ $totalStokAkhir }}</span>
            </td>
        </tr>
    </table>

    <table class="data-table">
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
                    <td class="center"><span class="no-chip">{{ $loop->iteration }}</span></td>
                    <td>{{ $item['nama_barang'] }}</td>
                    <td class="center"><span class="cell-chip cell-chip-muted">{{ $item['stok_awal'] }}</span></td>
                    <td class="center"><span class="cell-chip cell-chip-green">{{ $item['barang_masuk'] }}</span></td>
                    <td class="center"><span class="cell-chip cell-chip-amber">{{ $item['barang_keluar'] }}</span></td>
                    <td class="center"><span class="cell-chip cell-chip-blue">{{ $item['stok_akhir'] }}</span></td>
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
