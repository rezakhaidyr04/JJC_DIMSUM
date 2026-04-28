@extends('layouts.app')

@section('title', 'Rekap Bulanan Per Cabang')
@section('page-title', 'Rekap Bulanan Per Cabang')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Rekap</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('stok-opname.rekap') }}" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label for="bulan" class="form-label">Bulan</label>
                            <input type="month" id="bulan" name="bulan" class="form-control" value="{{ $bulan }}">
                        </div>
                        <div class="col-md-4">
                            <label for="cabang_id" class="form-label">Cabang</label>
                            <select id="cabang_id" name="cabang_id" class="form-select">
                                <option value="">Semua Cabang</option>
                                @foreach($cabangList as $cabang)
                                    <option value="{{ $cabang->id }}" {{ (string) $selectedCabang === (string) $cabang->id ? 'selected' : '' }}>{{ $cabang->nama_cabang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Terapkan
                            </button>
                            <a href="{{ route('stok-opname.export-pdf', ['bulan' => $bulan, 'cabang_id' => $selectedCabang]) }}" class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                        </div>
                    </form>
                    <div class="mt-2 text-muted">
                        Periode: {{ \Carbon\Carbon::parse($periodeMulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($periodeSelesai)->format('d M Y') }}
                    </div>
                    <!-- Section: Pilih Cabang (klikable cards) -->
                    <div class="mt-3">
                        <div class="card">
                            <div class="card-header bg-danger text-white">
                                <h5 class="card-title mb-0">Daftar Cabang (Satu Section Per Cabang)</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Klik tombol "Lihat" pada setiap section untuk menampilkan rekap cabang tersebut.</p>

                                @php
                                    // Use orderedCabangs if available (controller provides it). Otherwise fall back to cabangList.
                                    $sections = $orderedCabangs ?? collect();
                                @endphp

                                @if($sections->isNotEmpty())
                                    @foreach($sections as $entry)
                                        @php
                                            $prefName = $entry['preferred_name'];
                                            $model = $entry['model'];
                                            $summary = null;
                                            if ($model) {
                                                $summary = $summaryByCabang[$model->nama_cabang] ?? null;
                                            }
                                        @endphp

                                        <div class="mb-3">
                                            <div class="card shadow-sm">
                                                <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                                                    <div>
                                                        <h5 class="mb-1">{{ $prefName }}</h5>
                                                        @if($model)
                                                            <div class="text-muted small">ID: {{ $model->id }} • {{ $model->kode_cabang ?? '' }}</div>
                                                            @if($summary)
                                                                <div class="mt-2">
                                                                    <span class="me-3"><strong>Dibawa:</strong> {{ $summary['total_bawa'] }}</span>
                                                                    <span class="me-3"><strong>Sisa:</strong> {{ $summary['total_sisa'] }}</span>
                                                                    <span class="me-3"><strong>Terpakai:</strong> {{ $summary['total_terpakai'] }}</span>
                                                                    <span class="me-3"><strong>Input:</strong> {{ $summary['total_transaksi'] }}</span>
                                                                </div>
                                                            @else
                                                                <div class="mt-2 text-muted">Belum ada data untuk periode ini.</div>
                                                            @endif
                                                        @else
                                                            <div class="text-muted small">Cabang ini belum terdaftar di database.</div>
                                                        @endif
                                                    </div>

                                                    <div class="mt-3 mt-md-0">
                                                        @if($model)
                                                            <a href="{{ route('stok-opname.rekap', ['bulan' => $bulan, 'cabang_id' => $model->id]) }}" class="btn btn-outline-primary me-2">Lihat</a>
                                                            <a href="{{ route('stok-opname.export-pdf', ['bulan' => $bulan, 'cabang_id' => $model->id]) }}" class="btn btn-danger">Export PDF</a>
                                                        @else
                                                            <button class="btn btn-secondary" disabled>Belum Tersedia</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-muted">Tidak ada cabang untuk ditampilkan.</div>
                                @endif

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
                    <h3 class="card-title">Grafik Konsumsi Barang (Total Terpakai)</h3>
                </div>
                <div class="card-body">
                    <canvas id="konsumsiBarangChart" style="height: 320px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ringkasan Per Cabang</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Cabang</th>
                                    <th class="text-center">Total Dibawa</th>
                                    <th class="text-center">Total Sisa</th>
                                    <th class="text-center">Total Terpakai</th>
                                    <th class="text-center">Jumlah Input</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($summaryByCabang as $namaCabang => $summary)
                                    <tr>
                                        <td>{{ $namaCabang }}</td>
                                        <td class="text-center">{{ $summary['total_bawa'] }}</td>
                                        <td class="text-center">{{ $summary['total_sisa'] }}</td>
                                        <td class="text-center">{{ $summary['total_terpakai'] }}</td>
                                        <td class="text-center">{{ $summary['total_transaksi'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Belum ada data pada tanggal ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Input Operasional</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 4%;">No</th>
                                    <th>Tanggal</th>
                                    <th>Cabang</th>
                                    <th>Penginput</th>
                                    <th>Barang</th>
                                    <th class="text-center">Dibawa</th>
                                    <th class="text-center">Sisa</th>
                                    <th class="text-center">Terpakai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @forelse($records as $record)
                                    @foreach($record->items as $item)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td>{{ $record->tanggal->format('d-m-Y') }}</td>
                                            <td>{{ $record->cabang?->nama_cabang ?? '-' }}</td>
                                            <td>{{ $record->user?->name ?? '-' }}</td>
                                            <td>{{ $item->barang?->nama_barang ?? '-' }}</td>
                                            <td class="text-center">{{ $item->jumlah_bawa }}</td>
                                            <td class="text-center">{{ $item->jumlah_sisa }}</td>
                                            <td class="text-center">{{ $item->jumlah_terpakai }}</td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Belum ada input operasional pada tanggal ini.</td>
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

@push('scripts')
<script>
    const konsumsiLabels = @json($konsumsiBarang->keys()->values());
    const konsumsiValues = @json($konsumsiBarang->values());

    const chartElement = document.getElementById('konsumsiBarangChart');

    if (chartElement) {
        new Chart(chartElement.getContext('2d'), {
            type: 'bar',
            data: {
                labels: konsumsiLabels,
                datasets: [{
                    label: 'Total Terpakai',
                    data: konsumsiValues,
                    borderColor: '#c62833',
                    backgroundColor: 'rgba(198, 40, 51, 0.2)',
                    borderWidth: 2,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
