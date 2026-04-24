<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Operasional Cabang Bulanan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #111; margin: 16px; }
        .header { border: 1px solid #f1d1d4; border-top: 4px solid #cf202c; border-radius: 8px; padding: 10px; margin-bottom: 10px; }
        .header-left { float: left; width: 14%; text-align: center; }
        .header-right { float: left; width: 86%; text-align: center; }
        .logo { width: 56px; height: 56px; border-radius: 50%; object-fit: cover; border: 1px solid #f4c8cc; }
        .clearfix { clear: both; }
        h1 { margin: 0; font-size: 18px; color: #9f1d28; }
        p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #d1d5db; padding: 6px; font-size: 10px; }
        th { background: #cf202c; color: #fff; text-align: center; }
        td.center { text-align: center; }
        .section-title { margin-top: 10px; margin-bottom: 2px; font-weight: bold; color: #8f1b24; }
        .footer-total { margin-top: 8px; border: 1px solid #f1d1d4; border-radius: 8px; padding: 8px; background: #fff8f8; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            @if(!empty($logoBase64))
                <img src="{{ $logoBase64 }}" alt="Logo" class="logo">
            @endif
        </div>
        <div class="header-right">
            <h1>Rekap Operasional Cabang Bulanan</h1>
            <p><strong>Cikampek Jajanan</strong></p>
            <p>Periode Rekap: {{ \Carbon\Carbon::parse($periodeMulai)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($periodeSelesai)->format('d-m-Y') }}</p>
            <p>Dicetak: {{ now()->format('d-m-Y H:i:s') }} WIB</p>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="section-title">Ringkasan Per Cabang</div>
    <table>
        <thead>
            <tr>
                <th>Cabang</th>
                <th>Total Dibawa</th>
                <th>Total Sisa</th>
                <th>Total Terpakai</th>
                <th>Jumlah Input</th>
            </tr>
        </thead>
        <tbody>
            @forelse($summaryByCabang as $namaCabang => $summary)
                <tr>
                    <td>{{ $namaCabang }}</td>
                    <td class="center">{{ $summary['total_bawa'] }}</td>
                    <td class="center">{{ $summary['total_sisa'] }}</td>
                    <td class="center">{{ $summary['total_terpakai'] }}</td>
                    <td class="center">{{ $summary['total_transaksi'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Detail Input Per Barang</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Cabang</th>
                <th>Penginput</th>
                <th>Barang</th>
                <th>Dibawa</th>
                <th>Sisa</th>
                <th>Terpakai</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($records as $record)
                @foreach($record->items as $item)
                    <tr>
                        <td class="center">{{ $no++ }}</td>
                        <td class="center">{{ $record->tanggal->format('d-m-Y') }}</td>
                        <td>{{ $record->cabang?->nama_cabang ?? '-' }}</td>
                        <td>{{ $record->user?->name ?? '-' }}</td>
                        <td>{{ $item->barang?->nama_barang ?? '-' }}</td>
                        <td class="center">{{ $item->jumlah_bawa }}</td>
                        <td class="center">{{ $item->jumlah_sisa }}</td>
                        <td class="center">{{ $item->jumlah_terpakai }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="8" class="center">Tidak ada data detail.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Top Konsumsi Barang (Total Terpakai)</div>
    <table>
        <thead>
            <tr>
                <th style="width: 6%;">No</th>
                <th>Nama Barang</th>
                <th style="width: 20%;">Total Terpakai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($konsumsiBarang as $namaBarang => $totalTerpakai)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td>{{ $namaBarang }}</td>
                    <td class="center">{{ $totalTerpakai }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="center">Tidak ada data konsumsi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @php
        $totalBawa = $summaryByCabang->sum('total_bawa');
        $totalSisa = $summaryByCabang->sum('total_sisa');
        $totalTerpakai = $summaryByCabang->sum('total_terpakai');
    @endphp

    <div class="footer-total">
        <strong>Total Akumulasi Bulan Ini:</strong>
        Dibawa = <strong>{{ $totalBawa }}</strong> |
        Sisa = <strong>{{ $totalSisa }}</strong> |
        Terpakai = <strong>{{ $totalTerpakai }}</strong>
    </div>
</body>
</html>
