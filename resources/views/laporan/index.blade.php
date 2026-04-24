@extends('layouts.app')

@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok')

@section('content')
    @php
        $laporanCollection = collect($laporan);
        $totalBarang = $laporanCollection->count();
        $totalMasuk = $laporanCollection->sum('barang_masuk');
        $totalKeluar = $laporanCollection->sum('barang_keluar');
        $totalStokAkhir = $laporanCollection->sum('stok_akhir');
    @endphp

    <div class="print-only-header">
        <h2>Laporan Stok Barang - Cikampek Jajanan</h2>
        <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</p>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Stok Barang</h3>
                    <div class="card-tools">
                        <a href="{{ route('laporan.index', array_filter(['tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai, 'export' => 'excel'])) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                        <a href="{{ route('laporan.index', array_filter(['tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai, 'export' => 'pdf'])) }}" class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="laporan-insights">
                        <div class="laporan-insight">
                            <div class="laporan-insight__label"><i class="fas fa-cubes"></i> Total Barang</div>
                            <div class="laporan-insight__value">{{ $totalBarang }}</div>
                        </div>
                        <div class="laporan-insight">
                            <div class="laporan-insight__label"><i class="fas fa-arrow-down"></i> Total Masuk</div>
                            <div class="laporan-insight__value">{{ $totalMasuk }}</div>
                        </div>
                        <div class="laporan-insight">
                            <div class="laporan-insight__label"><i class="fas fa-arrow-up"></i> Total Keluar</div>
                            <div class="laporan-insight__value">{{ $totalKeluar }}</div>
                        </div>
                        <div class="laporan-insight">
                            <div class="laporan-insight__label"><i class="fas fa-boxes"></i> Total Stok Akhir</div>
                            <div class="laporan-insight__value">{{ $totalStokAkhir }}</div>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('laporan.index') }}" class="row g-2 mb-3 laporan-filter">
                        <div class="col-md-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" value="{{ $tanggalMulai }}">
                        </div>
                        <div class="col-md-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control" value="{{ $tanggalSelesai }}">
                        </div>
                        <div class="col-md-6 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-rotate-left"></i> Reset
                            </a>
                        </div>
                    </form>

                    <div class="laporan-meta">
                        <span class="laporan-chip"><i class="fas fa-database"></i> Data: {{ $totalBarang }} barang</span>
                        <span class="laporan-chip"><i class="fas fa-clock"></i> Sinkron: {{ now()->format('d M Y H:i') }} WIB</span>
                    </div>

                    <div class="table-responsive laporan-table">
                    <table class="table table-bordered table-striped table-hover table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Nama Barang</th>
                                <th>Stok Awal</th>
                                <th>Barang Masuk</th>
                                <th>Barang Keluar</th>
                                <th>Stok Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($laporan) > 0)
                                @foreach($laporan as $item)
                                    <tr>
                                        <td><span class="laporan-no">{{ $loop->iteration }}</span></td>
                                        <td>{{ $item['nama_barang'] }}</td>
                                        <td class="text-center"><span class="badge laporan-badge laporan-badge--muted">{{ $item['stok_awal'] }}</span></td>
                                        <td class="text-center">
                                            <span class="badge laporan-badge laporan-badge--masuk">{{ $item['barang_masuk'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge laporan-badge laporan-badge--keluar">{{ $item['barang_keluar'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge laporan-badge laporan-badge--akhir">{{ $item['stok_akhir'] }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .print-only-header {
            display: none;
        }

        .laporan-insights {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.75rem;
            margin-bottom: 0.95rem;
        }

        .laporan-insight {
            background: linear-gradient(180deg, #fff 0%, #fff8f8 100%);
            border: 1px solid #f3cfd3;
            border-radius: 0.8rem;
            padding: 0.7rem 0.85rem;
            box-shadow: 0 10px 20px rgba(185, 23, 32, 0.08);
        }

        .laporan-insight__label {
            font-size: 0.74rem;
            color: #9f1d28;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.2rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .laporan-insight__value {
            color: #7f1d1d;
            font-size: 1.05rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .laporan-filter {
            border: 1px solid #f1d1d4;
            background: linear-gradient(180deg, #fff 0%, #fffafa 100%);
            border-radius: 0.75rem;
            padding: 0.75rem;
        }

        .laporan-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-bottom: 0.95rem;
        }

        .laporan-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.38rem;
            border: 1px solid #f6c8cc;
            background: linear-gradient(180deg, #fffafa 0%, #fff3f4 100%);
            color: #8f1b24;
            border-radius: 999px;
            padding: 0.36rem 0.7rem;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .laporan-table {
            border-radius: 0.82rem;
            overflow: hidden;
            border: 1px solid #f1d1d4;
            box-shadow: 0 12px 26px rgba(198, 40, 51, 0.1);
        }

        .laporan-table .table thead th {
            background: linear-gradient(90deg, #cf202c 0%, #b91720 100%);
            color: #fff;
            border: none;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .laporan-table .table tbody tr {
            transition: transform 0.14s ease, background-color 0.14s ease;
        }

        .laporan-table .table tbody tr:hover {
            background-color: #fff6f6;
            transform: translateX(2px);
        }

        .laporan-no {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 1.8rem;
            height: 1.8rem;
            border-radius: 999px;
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #9f1239;
            font-weight: 700;
            font-size: 0.78rem;
        }

        .laporan-badge {
            color: #fff;
            border: 1px solid transparent;
            box-shadow: 0 4px 10px rgba(17, 24, 39, 0.16);
        }

        .laporan-badge--muted {
            background: linear-gradient(180deg, #6b7280 0%, #4b5563 100%);
            border-color: rgba(75, 85, 99, 0.45);
        }

        .laporan-badge--masuk {
            background: linear-gradient(180deg, #16a34a 0%, #15803d 100%);
            border-color: rgba(21, 128, 61, 0.45);
        }

        .laporan-badge--keluar {
            background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%);
            border-color: rgba(217, 119, 6, 0.45);
        }

        .laporan-badge--akhir {
            background: linear-gradient(180deg, #2563eb 0%, #1d4ed8 100%);
            border-color: rgba(29, 78, 216, 0.45);
        }

        @media (max-width: 991.98px) {
            .laporan-insights {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767px) {
            .laporan-insights {
                grid-template-columns: 1fr;
                gap: 0.55rem;
                margin-bottom: 0.72rem;
            }

            .laporan-insight {
                padding: 0.58rem 0.7rem;
            }

            .laporan-insight__label {
                font-size: 0.7rem;
            }

            .laporan-insight__value {
                font-size: 0.95rem;
            }

            .laporan-meta {
                gap: 0.45rem;
                margin-bottom: 0.72rem;
            }

            .laporan-chip {
                font-size: 0.74rem;
                padding: 0.28rem 0.55rem;
            }

            .laporan-filter {
                padding: 0.62rem;
            }
        }

        @media (max-width: 480px) {
            .card-header {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 0.45rem;
            }

            .card-tools {
                width: 100%;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0.4rem;
            }

            .card-tools .btn {
                width: 100%;
                padding-left: 0.45rem;
                padding-right: 0.45rem;
            }

            .laporan-chip {
                font-size: 0.7rem;
                padding: 0.24rem 0.48rem;
            }

            .laporan-table .table th,
            .laporan-table .table td {
                white-space: nowrap;
            }
        }

        @media print {
            .main-header,
            .main-sidebar,
            .main-footer,
            .content-header,
            .card-tools,
            .btn,
            .nav,
            .navbar,
            .laporan-insights,
            .laporan-meta,
            .laporan-filter {
                display: none !important;
            }

            .content-wrapper {
                margin-left: 0 !important;
                min-height: auto !important;
            }

            .content,
            .container-fluid,
            .row,
            .col-md-12 {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            .card {
                border: 1px solid #d1d5db !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                margin: 0 !important;
            }

            .card-header {
                background: #fff !important;
                color: #111 !important;
                border-bottom: 1px solid #d1d5db !important;
                padding: 10px 12px !important;
            }

            .card-body {
                padding: 0 !important;
            }

            .table-responsive {
                overflow: visible !important;
            }

            .table {
                margin-bottom: 0 !important;
            }

            .table th,
            .table td {
                color: #111 !important;
                background: #fff !important;
                border-color: #d1d5db !important;
                padding: 8px !important;
            }

            .badge {
                border: 1px solid #d1d5db !important;
                background: #fff !important;
                color: #111 !important;
            }

            .print-only-header {
                display: block;
                text-align: center;
                margin-bottom: 12px;
            }

            .print-only-header h2 {
                font-size: 18px;
                margin: 0;
            }

            .print-only-header p {
                font-size: 12px;
                margin: 4px 0 0;
                color: #374151;
            }

            .card-tools {
                display: none;
            }

            body {
                background: white;
            }
        }
    </style>
@endsection
