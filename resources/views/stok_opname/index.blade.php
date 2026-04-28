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
                    
                    <!-- Daftar cabang: satu section per cabang -->
                    <div class="mt-3">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">Pilih Cabang (klik untuk langsung pilih)</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @foreach($cabangList as $cabang)
                                        <div class="col-12 col-md-6">
                                            <a href="{{ route('stok-opname.cabang', $cabang->id) }}?tanggal={{ $selectedTanggal }}#input-pagi" class="text-decoration-none">
                                                <div class="border rounded p-3 h-100 d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ $cabang->nama_cabang }}</strong>
                                                        <div class="text-muted small">{{ $cabang->kode_cabang ?? '' }} • ID: {{ $cabang->id }}</div>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('stok-opname.cabang', $cabang->id) }}?tanggal={{ $selectedTanggal }}#input-pagi" class="btn btn-sm btn-primary me-1">Pagi</a>
                                                        <a href="{{ route('stok-opname.cabang', $cabang->id) }}?tanggal={{ $selectedTanggal }}#input-malam" class="btn btn-sm btn-outline-secondary">Malam</a>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
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

        // Smooth-scroll to section when URL contains a hash (e.g. #input-pagi)
        document.addEventListener('DOMContentLoaded', function () {
            if (window.location.hash) {
                try {
                    var el = document.querySelector(window.location.hash);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        var focusable = el.querySelector('input, select, textarea, button');
                        if (focusable) focusable.focus();
                    }
                } catch (e) {
                    // ignore invalid selector
                }
            }
        });
    </script>
    @endpush
@endsection
