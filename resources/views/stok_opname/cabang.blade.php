@extends('layouts.app')

@section('title', 'Operasional Cabang - Detail')
@section('page-title', 'Operasional Cabang')

@section('content')
    <div class="stok-opname-page">
        <div class="stok-hero card border-0 mb-4">
            <div class="card-body p-3 p-lg-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-start">
                    <div class="flex-grow-1">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb stok-breadcrumb mb-3">
                                <li class="breadcrumb-item"><a href="{{ route('stok-opname.index', ['tanggal' => $selectedTanggal]) }}">Daftar Cabang</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $cabang->nama_cabang }}</li>
                            </ol>
                        </nav>

                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <span class="section-chip section-chip--light">Operasional Cabang</span>
                            <span class="section-chip section-chip--accent">{{ count($barangList) }} Barang</span>
                            <span class="section-chip section-chip--dark">{{ count($recentActivities) }} Aktivitas</span>
                        </div>

                        <h1 class="stok-page-title mb-2">Operasional untuk {{ $cabang->nama_cabang }}</h1>
                        <p class="stok-page-subtitle mb-0">
                            Kelola input pagi dan malam.
                        </p>
                    </div>

                    <div class="text-lg-end">
                        <div class="stok-code-badge">{{ $cabang->kode_cabang ?? 'CABANG' }}</div>
                        <div class="stok-code-caption mt-2">Kode cabang aktif</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <a href="{{ route('stok-opname.index', ['tanggal' => $selectedTanggal]) }}" class="btn btn-soft-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke daftar cabang
            </a>

            <div class="stok-date-pill">
                <span class="stok-date-label">Tanggal aktif</span>
                <strong>{{ \Carbon\Carbon::parse($selectedTanggal)->format('d M Y') }}</strong>
            </div>
        </div>

        <div class="row g-4 mb-4 align-items-start">
            <div class="col-lg-5">
                <div class="card stok-summary-card border-0">
                    <div class="card-header stok-section-header stok-section-header--info">
                        <div>
                            <div class="stok-section-eyebrow">Ringkasan</div>
                            <h5 class="mb-0">Detail Cabang Aktif</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-soft-info d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-0">
                            <div>Anda sedang membuka halaman detail cabang <strong>{{ $cabang->nama_cabang }}</strong>.</div>
                            <div class="small text-muted">Tanggal kerja: {{ \Carbon\Carbon::parse($selectedTanggal)->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card stok-summary-card h-100 border-0">
                    <div class="card-header stok-section-header stok-section-header--amber">
                        <div>
                            <div class="stok-section-eyebrow">Aktifitas</div>
                            <h5 class="mb-0">Terakhir Cabang Ini</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse($recentActivities as $activity)
                            <div class="activity-item {{ $loop->last ? 'mb-0' : '' }}">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="activity-date">{{ \Carbon\Carbon::parse($activity['tanggal'])->format('d M Y') }}</div>
                                        <div class="activity-meta">oleh {{ $activity['user_name'] }} • {{ \Carbon\Carbon::parse($activity['created_at'])->format('H:i') }} WIB</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="d-flex flex-wrap justify-content-end gap-2">
                                            <span class="badge rounded-pill bg-danger">Keluar: {{ $activity['barang_keluar_count'] }}</span>
                                            <span class="badge rounded-pill bg-success">Masuk: {{ $activity['barang_masuk_count'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="activity-stats mt-3">
                                    <div><span>Dibawa</span><strong>{{ $activity['total_bawa'] }}</strong></div>
                                    <div><span>Sisa</span><strong>{{ $activity['total_sisa'] }}</strong></div>
                                    <div><span>Terpakai</span><strong>{{ $activity['total_terpakai'] }}</strong></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-muted">Belum ada aktivitas sebelumnya untuk cabang ini.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div id="input-pagi" class="card stok-form-card mb-4 border-0">
            <div class="card-header stok-section-header stok-section-header--red">
                <div>
                    <div class="stok-section-eyebrow">Form Input</div>
                    <h5 class="mb-0">Input Pagi - Barang Dibawa Ke Cabang</h5>
                </div>
            </div>
            <div class="card-body p-3 p-lg-4">
                <form method="POST" action="{{ route('stok-opname.store-berangkat') }}">
                    @csrf
                    <input type="hidden" name="tanggal" value="{{ $selectedTanggal }}">
                    <input type="hidden" name="cabang_id" value="{{ $selectedCabang }}">

                    <div class="mb-3">
                        <label for="catatan" class="form-label fw-semibold">Catatan Pagi <span class="text-muted fw-normal">(Opsional)</span></label>
                        <input type="text" name="catatan" id="catatan" class="form-control form-control-lg" value="{{ old('catatan') }}" placeholder="Contoh: Shift pagi">
                    </div>

                    <div class="table-responsive stok-table-wrap">
                        <table class="table table-hover align-middle mb-0 stok-table">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">No</th>
                                    <th>Nama Barang</th>
                                    <th style="width: 180px;">Stok Global</th>
                                    <th style="width: 260px;">Jumlah Dibawa Ke Cabang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barangList as $index => $barang)
                                    @php
                                        $existingItem = $existingItemsByBarang->get($barang->id_barang);
                                        $stokSaatIni = (int) ($barang->stok ?? 0);
                                    @endphp
                                    <tr>
                                        <td class="text-center text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $barang->nama_barang }}</div>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill bg-info text-dark stok-value-badge">{{ $stokSaatIni }}</span>
                                        </td>
                                        <td>
                                            <input type="hidden" name="berangkat[{{ $index }}][barang_id]" value="{{ $barang->id_barang }}">
                                            <input
                                                type="number"
                                                class="form-control js-pagi-bawa"
                                                name="berangkat[{{ $index }}][jumlah_bawa]"
                                                min="0"
                                                max="{{ $stokSaatIni }}"
                                                value="{{ old('berangkat.' . $index . '.jumlah_bawa', $existingItem?->jumlah_bawa ?? 0) }}"
                                                placeholder="0"
                                                data-max-stok="{{ $stokSaatIni }}"
                                            >
                                            <small class="text-muted d-block mt-1">Maksimal: {{ $stokSaatIni }}</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 d-flex flex-wrap gap-2 justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-save me-1"></i> Simpan Input Pagi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="input-malam" class="card stok-form-card border-0">
            <div class="card-header stok-section-header stok-section-header--green">
                <div>
                    <div class="stok-section-eyebrow">Form Input</div>
                    <h5 class="mb-0">Input Malam - Barang Sisa Dari Cabang</h5>
                </div>
            </div>
            <div class="card-body p-3 p-lg-4">
                <form method="POST" action="{{ route('stok-opname.store-sisa') }}">
                    @csrf
                    <input type="hidden" name="tanggal" value="{{ $selectedTanggal }}">
                    <input type="hidden" name="cabang_id" value="{{ $selectedCabang }}">

                    <div class="table-responsive stok-table-wrap">
                        <table class="table table-hover align-middle mb-0 stok-table">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">No</th>
                                    <th>Nama Barang</th>
                                    <th style="width: 180px;">Dibawa (Pagi)</th>
                                    <th style="width: 220px;">Sisa (Malam)</th>
                                    <th style="width: 180px;">Terpakai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barangList as $index => $barang)
                                    @php
                                        $existingItem = $existingItemsByBarang->get($barang->id_barang);
                                        $jumlahBawa = (int) ($existingItem?->jumlah_bawa ?? 0);
                                        $jumlahSisa = (int) ($existingItem?->jumlah_sisa ?? 0);
                                    @endphp
                                    <tr>
                                        <td class="text-center text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $barang->nama_barang }}</div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control js-malam-bawa" value="{{ $jumlahBawa }}" readonly>
                                            <input type="hidden" name="sisa[{{ $index }}][barang_id]" value="{{ $barang->id_barang }}">
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

                    <div class="mt-4 d-flex flex-wrap gap-2 justify-content-end">
                        <button type="submit" class="btn btn-success px-4 py-2">
                            <i class="fas fa-save me-1"></i> Simpan Input Malam
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .stok-opname-page {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .content-header h1 {
            font-size: 1.55rem;
            line-height: 1.2;
            margin-bottom: 0.25rem;
        }

        .stok-hero {
            overflow: hidden;
            border-radius: 1rem;
            background:
                linear-gradient(135deg, rgba(227, 30, 36, 0.96), rgba(177, 23, 32, 0.96)),
                #e31e24;
            color: #fff;
            box-shadow: 0 12px 28px rgba(227, 30, 36, 0.16);
        }

        .stok-page-title {
            font-size: clamp(1.35rem, 2vw, 2rem);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.02em;
        }

        .stok-page-subtitle {
            max-width: 780px;
            color: rgba(255, 255, 255, 0.86);
        }

        .stok-breadcrumb .breadcrumb-item,
        .stok-breadcrumb .breadcrumb-item a {
            color: #111827 !important;
        }

        .stok-breadcrumb .breadcrumb-item.active {
            color: #6b7280 !important;
        }

        .stok-code-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 92px;
            padding: 0.55rem 0.85rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
            font-weight: 800;
            letter-spacing: 0.04em;
            backdrop-filter: blur(10px);
        }

        .stok-code-caption {
            color: rgba(255, 255, 255, 0.72);
            font-size: 0.9rem;
        }

        .section-chip {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.3rem 0.68rem;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .section-chip--light {
            background: rgba(255, 255, 255, 0.16);
            color: #fff;
        }

        .section-chip--accent {
            background: rgba(255, 237, 78, 0.95);
            color: #2d2d2d;
        }

        .section-chip--dark {
            background: rgba(17, 24, 39, 0.25);
            color: #fff;
        }

        .stok-date-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.65rem 0.85rem;
            border-radius: 0.9rem;
            background: rgba(255, 255, 255, 0.88);
            color: #1f2937;
            box-shadow: var(--shadow-soft);
        }

        .stok-date-label {
            color: #6b7280;
            font-size: 0.85rem;
        }

        .stok-summary-card,
        .stok-form-card {
            border-radius: 1rem;
            box-shadow: var(--shadow-soft);
            overflow: hidden;
            background: #fff;
        }

        .stok-section-header {
            border-bottom: 0;
            padding: 0.75rem 1rem;
            color: #fff;
        }

        .stok-section-header h5 {
            font-weight: 800;
            font-size: 1rem;
        }

        .stok-section-header--info {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
        }

        .stok-section-header--amber {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .stok-section-header--red {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .stok-section-header--green {
            background: linear-gradient(135deg, #16a34a, #15803d);
        }

        .stok-section-eyebrow {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            opacity: 0.82;
            margin-bottom: 0.1rem;
        }

        .alert-soft-info {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.08), rgba(37, 99, 235, 0.06));
            border: 1px solid rgba(37, 99, 235, 0.12);
            color: #1f2937;
            border-radius: 1rem;
        }

        .btn-soft-secondary {
            background: #fff;
            color: #374151;
            border: 1px solid rgba(148, 163, 184, 0.3);
            border-radius: 0.8rem;
            padding: 0.8rem 1rem;
            box-shadow: var(--shadow-soft);
        }

        .btn-soft-secondary:hover {
            background: #f8fafc;
            color: #111827;
        }

        .activity-item {
            padding: 0.8rem;
            border: 1px solid var(--border-soft);
            border-radius: 0.9rem;
            background: linear-gradient(180deg, #fff, #fbfdff);
        }

        .activity-item + .activity-item {
            margin-top: 0.85rem;
        }

        .activity-date {
            font-weight: 800;
            font-size: 0.95rem;
            color: #111827;
        }

        .activity-meta {
            color: #6b7280;
            font-size: 0.85rem;
            margin-top: 0.15rem;
        }

        .activity-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.6rem;
        }

        .activity-stats > div {
            border-radius: 0.8rem;
            padding: 0.65rem 0.7rem;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
        }

        .activity-stats span {
            display: block;
            font-size: 0.72rem;
            color: #6b7280;
        }

        .activity-stats strong {
            display: block;
            margin-top: 0.15rem;
            font-size: 0.92rem;
            color: #111827;
        }

        .stok-table-wrap {
            border: 1px solid var(--border-soft);
            border-radius: 0.9rem;
            overflow: hidden;
            background: #fff;
        }

        .stok-table {
            margin-bottom: 0;
        }

        .stok-table thead th {
            background: #f8fafc;
            color: #374151;
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-bottom: 1px solid #e5e7eb !important;
        }

        .stok-table tbody td {
            padding-top: 0.7rem;
            padding-bottom: 0.7rem;
            vertical-align: middle;
        }

        .stok-value-badge {
            min-width: 48px;
            padding-block: 0.4rem;
            font-size: 0.84rem;
            font-weight: 800;
        }

        .stok-table tbody tr:hover {
            background: rgba(239, 246, 255, 0.65);
        }

        .form-control-lg {
            border-radius: 0.8rem;
        }

        @media (max-width: 767.98px) {
            .activity-stats {
                grid-template-columns: 1fr;
            }

            .stok-code-badge {
                width: 100%;
            }
        }

        .stok-alert-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }
        .stok-alert-overlay.is-active {
            display: flex;
        }
        .stok-alert-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px 24px;
            width: 90%;
            max-width: 420px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }
        .stok-alert-title {
            font-weight: 700;
            margin-bottom: 8px;
        }
        .stok-alert-actions {
            margin-top: 16px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }
    </style>
    @endpush

    <div id="stokAlert" class="stok-alert-overlay" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="stok-alert-card">
            <div class="stok-alert-title">Peringatan</div>
            <div id="stokAlertMessage">Jumlah tidak boleh melebihi stok maksimal.</div>
            <div class="stok-alert-actions">
                <button type="button" class="btn btn-primary btn-sm" id="stokAlertClose">OK</button>
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

        document.querySelectorAll('.js-pagi-bawa').forEach(function (input) {
            const maxStok = parseInt(input.dataset.maxStok || '0', 10);
            const alertOverlay = document.getElementById('stokAlert');
            const alertMessage = document.getElementById('stokAlertMessage');

            input.addEventListener('input', function () {
                const value = parseInt(input.value || '0', 10);
                if (value > maxStok) {
                    alertMessage.textContent = 'Jumlah tidak boleh melebihi stok maksimal (' + maxStok + ').';
                    alertOverlay.classList.add('is-active');
                    alertOverlay.setAttribute('aria-hidden', 'false');
                }
            });
        });

        (function () {
            const alertOverlay = document.getElementById('stokAlert');
            const alertClose = document.getElementById('stokAlertClose');

            if (!alertOverlay || !alertClose) {
                return;
            }

            const closeAlert = function () {
                alertOverlay.classList.remove('is-active');
                alertOverlay.setAttribute('aria-hidden', 'true');
            };

            alertClose.addEventListener('click', closeAlert);
            alertOverlay.addEventListener('click', function (event) {
                if (event.target === alertOverlay) {
                    closeAlert();
                }
            });
        })();

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
