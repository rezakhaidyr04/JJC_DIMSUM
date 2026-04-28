@extends('layouts.app')

@section('title', 'Tambah Barang Masuk')
@section('page-title', 'Tambah Barang Masuk')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Barang Masuk</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('barang-masuk.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="penginput_nama" class="form-label">Nama Penginput</label>
                            <input
                                type="text"
                                class="form-control"
                                id="penginput_nama"
                                value="{{ auth()->user()->name }}"
                                readonly
                            >
                            <small class="text-muted">Diambil otomatis dari akun login.</small>
                        </div>

                        <div class="mb-3">
                            <label for="barang_id" class="form-label">Barang <span class="text-danger">*</span></label>
                            <select class="form-select @error('barang_id') is-invalid @enderror" id="barang_id" name="barang_id" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barang as $item)
                                    <option value="{{ $item->id }}" {{ old('barang_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_barang }} (Stok: {{ $item->stok }})
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('jumlah') is-invalid @enderror" 
                                   id="jumlah" name="jumlah" value="{{ old('jumlah') }}" min="1" required>
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                   id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jam_realtime_masuk" class="form-label">Jam (Real Time)</label>
                            <input type="text" class="form-control" id="jam_realtime_masuk" readonly>
                            <small class="text-muted">Waktu tersimpan mengikuti jam server saat simpan.</small>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const clockInput = document.getElementById('jam_realtime_masuk');

            const updateClock = () => {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                clockInput.value = `${hours}:${minutes}:${seconds}`;
            };

            updateClock();
            setInterval(updateClock, 1000);
        });
    </script>
    @endpush
@endsection
