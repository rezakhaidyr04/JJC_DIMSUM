@extends('layouts.app')

@section('title', 'Laporan Cabang')
@section('page-title', 'Laporan Cabang')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Ringkas Per Cabang</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('laporan-cabang.index') }}" class="row g-2 mb-3">
                        <div class="col-md-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" value="{{ $tanggalMulai }}">
                        </div>
                        <div class="col-md-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control" value="{{ $tanggalSelesai }}">
                        </div>
                        <div class="col-md-3">
                            <label for="cabang_id" class="form-label">Cabang</label>
                            <select id="cabang_id" name="cabang_id" class="form-select">
                                <option value="">-- Semua Cabang --</option>
                                @foreach($cabangList as $cabang)
                                    <option value="{{ $cabang->id_cabang }}" {{ (string) $selectedCabang === (string) $cabang->id_cabang ? 'selected' : '' }}>{{ $cabang->nama_cabang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('laporan-cabang.index') }}" class="btn btn-secondary">
                                <i class="fas fa-rotate-left"></i> Reset
                            </a>
                        </div>
                    </form>

                    <div class="row g-2 mb-3">
                        <div class="col-md-3">
                            <div class="p-3 border rounded bg-light">
                                <div class="small text-muted">Total Record</div>
                                <div class="fs-5 fw-bold">{{ $totalRecords }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border rounded bg-light">
                                <div class="small text-muted">Total Cabang</div>
                                <div class="fs-5 fw-bold">{{ $totalCabang }}</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="p-3 border rounded bg-light">
                                <div class="small text-muted">Total Bawa</div>
                                <div class="fs-5 fw-bold">{{ $totalBawa }}</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="p-3 border rounded bg-light">
                                <div class="small text-muted">Total Sisa</div>
                                <div class="fs-5 fw-bold">{{ $totalSisa }}</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="p-3 border rounded bg-light">
                                <div class="small text-muted">Total Terpakai</div>
                                <div class="fs-5 fw-bold">{{ $totalTerpakai }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th style="width: 12%">Tanggal</th>
                                    <th>Cabang</th>
                                    <th class="text-center" style="width: 12%">Keluar / Bawa</th>
                                    <th class="text-center" style="width: 12%">Kembali / Sisa</th>
                                    <th class="text-center" style="width: 12%">Terpakai</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $row)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $row['tanggal'] }}</td>
                                        <td>{{ $row['cabang'] }}</td>
                                        <td class="text-center">{{ $row['total_bawa'] }}</td>
                                        <td class="text-center">{{ $row['total_sisa'] }}</td>
                                        <td class="text-center">{{ $row['total_terpakai'] }}</td>
                                        <td>{{ $row['catatan'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
