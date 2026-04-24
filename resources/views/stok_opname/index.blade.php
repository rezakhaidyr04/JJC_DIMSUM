@extends('layouts.app')

@section('title', 'Stok Opname Harian')
@section('page-title', 'Stok Opname Harian')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Blind Entry Stok Opname (Akhir Shift)</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-shield-alt me-1"></i>
                        Isi jumlah fisik sesuai hitungan aktual. Jumlah stok sistem tidak ditampilkan di form ini.
                    </div>

                    <form method="POST" action="{{ route('stok-opname.store') }}">
                        @csrf

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th>Nama Barang</th>
                                        <th style="width: 28%;">Jumlah Fisik</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($barangList as $index => $barang)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $barang->nama_barang }}</td>
                                            <td>
                                                <input type="hidden" name="opname[{{ $index }}][barang_id]" value="{{ $barang->id }}">
                                                <input
                                                    type="number"
                                                    class="form-control @error('opname.' . $index . '.jumlah_fisik') is-invalid @enderror"
                                                    name="opname[{{ $index }}][jumlah_fisik]"
                                                    min="0"
                                                    required
                                                    value="{{ old('opname.' . $index . '.jumlah_fisik') }}"
                                                    placeholder="Masukkan jumlah fisik"
                                                >
                                                @error('opname.' . $index . '.jumlah_fisik')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Opname Hari Ini
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hasil Opname Hari Ini</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th>Barang</th>
                                    <th class="text-center">Jumlah Fisik</th>
                                    <th class="text-center">Status Selisih</th>
                                    <th class="text-center">Waktu Input</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todayOpname as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->barang->nama_barang }}</td>
                                        <td class="text-center">{{ $item->jumlah_fisik }}</td>
                                        <td class="text-center">
                                            @if($item->status === 'match')
                                                <span class="badge bg-success">Sesuai</span>
                                            @elseif($item->status === 'surplus')
                                                <span class="badge bg-primary">Surplus (+{{ $item->selisih }})</span>
                                            @else
                                                <span class="badge bg-danger">Minus ({{ $item->selisih }})</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->created_at->format('d M Y H:i') }} WIB</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Belum ada input opname hari ini.</td>
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
