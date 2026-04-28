@extends('layouts.app')

@section('title', 'Operasional Cabang - Detail')
@section('page-title', 'Operasional Cabang')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                        <div>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-1">
                                    <li class="breadcrumb-item"><a href="{{ route('stok-opname.index', ['tanggal' => $selectedTanggal]) }}">Daftar Cabang</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ $cabang->nama_cabang }}</li>
                                </ol>
                            </nav>
                            <h3 class="card-title mb-0">Operasional untuk: {{ $cabang->nama_cabang }}</h3>
                        </div>
                        <div class="text-md-end">
                            <div class="badge bg-primary fs-6">{{ $cabang->kode_cabang ?? 'CABANG' }}</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <a href="{{ route('stok-opname.index', ['tanggal' => $selectedTanggal]) }}" class="btn btn-secondary mb-3">&larr; Kembali ke daftar cabang</a>

                    <div class="alert alert-info d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                        <div>Tanggal aktif: <strong>{{ \Carbon\Carbon::parse($selectedTanggal)->format('d M Y') }}</strong></div>
                        <div class="small text-muted">Anda sedang membuka halaman detail cabang <strong>{{ $cabang->nama_cabang }}</strong>.</div>
                    </div>

                    <div class="card mb-3 border-warning">
                        <div class="card-header bg-warning text-dark">
                            <strong>Aktivitas Terakhir Cabang Ini</strong>
                        </div>
                        <div class="card-body">
                            @forelse($recentActivities as $activity)
                                <div class="border rounded p-3 mb-2">
                                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                                        <div>
                                            <strong>{{ \Carbon\Carbon::parse($activity['tanggal'])->format('d M Y') }}</strong>
                                            <div class="text-muted small">oleh {{ $activity['user_name'] }} • {{ \Carbon\Carbon::parse($activity['created_at'])->format('H:i') }} WIB</div>
                                        </div>
                                        <div class="text-md-end small">
                                            <span class="badge bg-danger me-1">Keluar: {{ $activity['barang_keluar_count'] }}</span>
                                            <span class="badge bg-success me-1">Masuk: {{ $activity['barang_masuk_count'] }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-2 small">
                                        <span class="me-3"><strong>Dibawa:</strong> {{ $activity['total_bawa'] }}</span>
                                        <span class="me-3"><strong>Sisa:</strong> {{ $activity['total_sisa'] }}</span>
                                        <span class="me-3"><strong>Terpakai:</strong> {{ $activity['total_terpakai'] }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted">Belum ada aktivitas sebelumnya untuk cabang ini.</div>
                            @endforelse
                        </div>
                    </div>

                    <div id="input-pagi" class="card mb-3">
                        <div class="card-header bg-danger text-white">
                            <h5 class="card-title mb-0">Input Pagi: Barang Dibawa Ke Cabang</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('stok-opname.store-berangkat') }}">
                                @csrf
                                <input type="hidden" name="tanggal" value="{{ $selectedTanggal }}">
                                <input type="hidden" name="cabang_id" value="{{ $selectedCabang }}">

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="catatan" class="form-label">Catatan Pagi (Opsional)</label>
                                        <input type="text" name="catatan" id="catatan" class="form-control" value="{{ old('catatan') }}" placeholder="Contoh: Shift pagi">
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
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3 d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan Input Pagi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="input-malam" class="card">
                        <div class="card-header bg-danger text-white">
                            <h5 class="card-title mb-0">Input Malam: Barang Sisa Dari Cabang</h5>
                        </div>
                        <div class="card-body">
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
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Simpan Input Malam
                                    </button>
                                </div>
                            </form>
                        </div>
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

        // scroll on hash
        document.addEventListener('DOMContentLoaded', function () {
            if (window.location.hash) {
                try {
                    var el = document.querySelector(window.location.hash);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        var focusable = el.querySelector('input, select, textarea, button');
                        if (focusable) focusable.focus();
                    }
                } catch (e) {}
            }
        });
    </script>
    @endpush
@endsection
