@extends('layouts.app')

@section('title', 'Operasional Cabang Harian')
@section('page-title', 'Operasional Cabang Harian')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pilih Tanggal & Cabang Operasional</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('stok-opname.index') }}" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label for="tanggal_filter" class="form-label">Tanggal Operasional</label>
                            <input type="date" id="tanggal_filter" name="tanggal" class="form-control" value="{{ $selectedTanggal }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="cabang_filter" class="form-label">Cabang</label>
                            <select id="cabang_filter" name="cabang_id" class="form-select" required>
                                <option value="">-- Pilih Cabang --</option>
                                @foreach($cabangList as $cabang)
                                    <option value="{{ $cabang->id }}" {{ (string) $selectedCabang === (string) $cabang->id ? 'selected' : '' }}>{{ $cabang->nama_cabang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Tampilkan</button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
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
                    <h3 class="card-title">Input Pagi: Barang Dibawa Ke Cabang</h3>
                </div>
                <div class="card-body">
                    @if(empty($selectedCabang))
                        <div class="alert alert-secondary">
                            Pilih tanggal dan cabang terlebih dahulu, lalu klik <strong>Tampilkan</strong>.
                        </div>
                    @endif

                    <div class="alert alert-warning">
                        <i class="fas fa-sun me-1"></i>
                        Input ini dilakukan pagi hari sebelum karyawan berangkat ke cabang. Akan otomatis tercatat sebagai barang keluar.
                    </div>

                    <form method="POST" action="{{ route('stok-opname.store-berangkat') }}">
                        @csrf
                        <input type="hidden" name="tanggal" value="{{ $selectedTanggal }}">
                        <input type="hidden" name="cabang_id" value="{{ $selectedCabang }}">

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="catatan" class="form-label">Catatan Pagi (Opsional)</label>
                                <input type="text" name="catatan" id="catatan" class="form-control" value="{{ old('catatan') }}" placeholder="Contoh: Shift pagi cabang CBG-01">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th>Nama Barang</th>
                                        <th style="width: 22%;">Jumlah Dibawa Ke Cabang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($barangList as $index => $barang)
                                        @php
                                            $existingItem = $existingItemsByBarang->get($barang->id);
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $barang->nama_barang }}</td>
                                            <td>
                                                <input type="hidden" name="berangkat[{{ $index }}][barang_id]" value="{{ $barang->id }}">
                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    name="berangkat[{{ $index }}][jumlah_bawa]"
                                                    min="0"
                                                    value="{{ old('berangkat.' . $index . '.jumlah_bawa', $existingItem?->jumlah_bawa ?? 0) }}"
                                                    placeholder="0"
                                                >
                                                @error('berangkat.' . $index . '.jumlah_bawa')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary" {{ empty($selectedCabang) ? 'disabled' : '' }}>
                                <i class="fas fa-save"></i> Simpan Input Pagi
                            </button>
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
                    <h3 class="card-title">Input Malam: Barang Sisa Dari Cabang</h3>
                </div>
                <div class="card-body">
                    @if(empty($selectedCabang))
                        <div class="alert alert-secondary">
                            Input malam bisa dilakukan setelah memilih cabang dan data input pagi tersedia.
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <i class="fas fa-moon me-1"></i>
                        Input ini dilakukan malam hari saat karyawan kembali ke pusat. Sistem otomatis mencatat barang sisa sebagai barang masuk.
                    </div>

                    <form method="POST" action="{{ route('stok-opname.store-sisa') }}">
                        @csrf
                        <input type="hidden" name="tanggal" value="{{ $selectedTanggal }}">
                        <input type="hidden" name="cabang_id" value="{{ $selectedCabang }}">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th>Nama Barang</th>
                                        <th style="width: 20%;">Jumlah Dibawa (Pagi)</th>
                                        <th style="width: 20%;">Jumlah Sisa (Malam)</th>
                                        <th style="width: 20%;">Terpakai (Auto)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($barangList as $index => $barang)
                                        @php
                                            $existingItem = $existingItemsByBarang->get($barang->id);
                                            $jumlahBawa = (int) ($existingItem?->jumlah_bawa ?? 0);
                                            $jumlahSisa = (int) ($existingItem?->jumlah_sisa ?? 0);
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $barang->nama_barang }}</td>
                                            <td>
                                                <input type="number" class="form-control js-malam-bawa" value="{{ $jumlahBawa }}" readonly>
                                                <input type="hidden" name="sisa[{{ $index }}][barang_id]" value="{{ $barang->id }}">
                                            </td>
                                            <td>
                                                <input
                                                    type="number"
                                                    class="form-control js-malam-sisa"
                                                    name="sisa[{{ $index }}][jumlah_sisa]"
                                                    min="0"
                                                    max="{{ $jumlahBawa }}"
                                                    value="{{ old('sisa.' . $index . '.jumlah_sisa', $jumlahSisa) }}"
                                                    placeholder="0"
                                                    {{ $jumlahBawa === 0 ? 'readonly' : '' }}
                                                >
                                                @error('sisa.' . $index . '.jumlah_sisa')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" class="form-control js-malam-terpakai" value="{{ max($jumlahBawa - $jumlahSisa, 0) }}" readonly>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-success" {{ empty($selectedCabang) ? 'disabled' : '' }}>
                                <i class="fas fa-save"></i> Simpan Input Malam
                            </button>
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
                    <h3 class="card-title">Riwayat Input Hari Ini</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th>Cabang</th>
                                    <th class="text-center">Total Dibawa</th>
                                    <th class="text-center">Total Sisa</th>
                                    <th class="text-center">Total Terpakai</th>
                                    <th class="text-center">Waktu Input</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todayRecords as $record)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $record->cabang?->nama_cabang ?? '-' }}</td>
                                        <td class="text-center">{{ $record->items->sum('jumlah_bawa') }}</td>
                                        <td class="text-center">{{ $record->items->sum('jumlah_sisa') }}</td>
                                        <td class="text-center">{{ $record->items->sum('jumlah_terpakai') }}</td>
                                        <td class="text-center">{{ $record->created_at->format('d M Y H:i') }} WIB</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada input operasional cabang hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('table tbody tr').forEach(function (row) {
            const bawaInput = row.querySelector('.js-malam-bawa');
            const sisaInput = row.querySelector('.js-malam-sisa');
            const terpakaiInput = row.querySelector('.js-malam-terpakai');

            if (!bawaInput || !sisaInput || !terpakaiInput) {
                return;
            }

            const recalc = function () {
                const bawa = parseInt(bawaInput.value || '0', 10);
                const sisa = parseInt(sisaInput.value || '0', 10);

                if (sisa > bawa) {
                    sisaInput.value = String(bawa);
                }

                terpakaiInput.value = Math.max(bawa - parseInt(sisaInput.value || '0', 10), 0);
            };

            sisaInput.addEventListener('input', recalc);
            recalc();
        });
    </script>
    @endpush
@endsection
