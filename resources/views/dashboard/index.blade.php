@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    @push('styles')
    <style>
        .dashboard-stat {
            border-radius: 0.85rem;
            background: #fff;
            border: 1px solid #f1f1f1;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            height: 100%;
            overflow: hidden;
        }

        .dashboard-stat__content {
            padding: 1rem 1rem 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .dashboard-stat__title {
            margin: 0;
            font-size: 0.88rem;
            color: #6b7280;
            font-weight: 600;
        }

        .dashboard-stat__value {
            margin: 0.25rem 0 0;
            font-size: 1.9rem;
            font-weight: 700;
            color: #111827;
            line-height: 1;
        }

        .dashboard-stat__icon {
            width: 44px;
            height: 44px;
            border-radius: 0.7rem;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 1.1rem;
        }

        .stat-red { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .stat-blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .stat-green { background: linear-gradient(135deg, #22c55e, #16a34a); }
        .stat-amber { background: linear-gradient(135deg, #f59e0b, #d97706); }

        .dashboard-stat__footer {
            border-top: 1px solid #f3f4f6;
            padding: 0.55rem 1rem 0.7rem;
            background: #fcfcfc;
        }

        .dashboard-stat__footer a {
            text-decoration: none;
            font-size: 0.86rem;
            font-weight: 600;
            color: #b91c1c;
        }

        .dashboard-stat__footer a:hover {
            color: #991b1b;
        }
    </style>
    @endpush

    <div class="row g-3">
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-stat">
                <div class="dashboard-stat__content">
                    <div>
                        <p class="dashboard-stat__title">Total Barang</p>
                        <p class="dashboard-stat__value">{{ $totalBarang }}</p>
                    </div>
                    <div class="dashboard-stat__icon stat-blue"><i class="fas fa-cube"></i></div>
                </div>
                <div class="dashboard-stat__footer">
                    <a href="{{ route('barang.index') }}">Lihat detail <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-stat">
                <div class="dashboard-stat__content">
                    <div>
                        <p class="dashboard-stat__title">Total Barang Masuk</p>
                        <p class="dashboard-stat__value">{{ $totalMasuk }}</p>
                    </div>
                    <div class="dashboard-stat__icon stat-green"><i class="fas fa-arrow-down"></i></div>
                </div>
                <div class="dashboard-stat__footer">
                    <a href="{{ route('barang-masuk.index') }}">Lihat detail <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-stat">
                <div class="dashboard-stat__content">
                    <div>
                        <p class="dashboard-stat__title">Total Barang Keluar</p>
                        <p class="dashboard-stat__value">{{ $totalKeluar }}</p>
                    </div>
                    <div class="dashboard-stat__icon stat-amber"><i class="fas fa-arrow-up"></i></div>
                </div>
                <div class="dashboard-stat__footer">
                    <a href="{{ route('barang-keluar.index') }}">Lihat detail <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-stat">
                <div class="dashboard-stat__content">
                    <div>
                        <p class="dashboard-stat__title">Total Stok</p>
                        <p class="dashboard-stat__value">{{ $totalStok }}</p>
                    </div>
                    <div class="dashboard-stat__icon stat-red"><i class="fas fa-boxes"></i></div>
                </div>
                <div class="dashboard-stat__footer">
                    <a href="{{ route('laporan.index') }}">Lihat detail <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aktivitas Stok (7 Hari Terakhir)</h3>
                </div>
                <div class="card-body">
                    <canvas id="stockChart" style="height: 380px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const ctx = document.getElementById('stockChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartData['labels']),
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: @json($chartData['masukData']),
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(34, 197, 94, 0.18)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Barang Keluar',
                        data: @json($chartData['keluarData']),
                        borderColor: '#dc2626',
                        backgroundColor: 'rgba(239, 68, 68, 0.14)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            boxWidth: 14,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.18)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
    @endpush
@endsection
