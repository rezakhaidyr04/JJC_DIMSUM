@extends('layouts.app')

@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok')

@section('content')
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
                        <a href="{{ route('laporan.index', array_filter(['tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai, 'export' => 'pdf'])) }}" target="_blank" class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('laporan.index') }}" class="row g-2 mb-3">
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

                    <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0">
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
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item['nama_barang'] }}</td>
                                        <td class="text-center">{{ $item['stok_awal'] }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ $item['barang_masuk'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">{{ $item['barang_keluar'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $item['stok_akhir'] }}</span>
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

        @media print {
            .main-header,
            .main-sidebar,
            .main-footer,
            .content-header,
            .card-tools,
            .btn,
            .nav,
            .navbar {
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
