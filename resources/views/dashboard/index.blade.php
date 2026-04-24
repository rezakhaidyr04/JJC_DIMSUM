@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    @push('styles')
    <style>
        :root {
            --brand-yellow: #ffd400;
            --brand-red: #c62833;
            --brand-red-dark: #8f1b24;
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Dashboard Container */
        .row.g-3 {
            margin-bottom: 2rem;
        }

        /* Modern Stat Card */
        .dashboard-stat {
            border-radius: 1.2rem;
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
            border: 1.5px solid rgba(255, 212, 0, 0.1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04), 0 8px 24px rgba(0, 0, 0, 0.08);
            height: 100%;
            overflow: hidden;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .dashboard-stat::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--brand-yellow), var(--brand-red));
        }

        .dashboard-stat:hover {
            transform: translateY(-6px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08), 0 16px 32px rgba(0, 0, 0, 0.12);
            border-color: rgba(255, 212, 0, 0.2);
        }

        .dashboard-stat__content {
            padding: 1.5rem 1.5rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

        .dashboard-stat__title {
            margin: 0 0 0.5rem;
            font-size: 0.85rem;
            color: #9ca3af;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .dashboard-stat__value {
            margin: 0;
            font-size: 2.2rem;
            font-weight: 800;
            color: #111827;
            line-height: 1;
            background: linear-gradient(135deg, #111827, #374151);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .dashboard-stat__icon {
            width: 56px;
            height: 56px;
            border-radius: 1rem;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 1.4rem;
            flex-shrink: 0;
            transition: var(--transition-smooth);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .dashboard-stat:hover .dashboard-stat__icon {
            transform: scale(1.08) rotate(5deg);
        }

        .stat-red { background: linear-gradient(135deg, var(--brand-red), var(--brand-red-dark)); }
        .stat-blue { background: linear-gradient(135deg, var(--brand-yellow), #ffcd00); }
        .stat-green { background: linear-gradient(135deg, var(--brand-red-dark), var(--brand-red)); }
        .stat-amber { background: linear-gradient(135deg, var(--brand-yellow), #ffc400); }

        .dashboard-stat__footer {
            border-top: 1px solid rgba(249, 250, 251, 0.8);
            padding: 0.9rem 1.5rem;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.5) 0%, rgba(250, 250, 250, 0.8) 100%);
            transition: var(--transition-smooth);
        }

        .dashboard-stat:hover .dashboard-stat__footer {
            background: linear-gradient(135deg, rgba(255, 212, 0, 0.05) 0%, rgba(198, 40, 51, 0.03) 100%);
        }

        .dashboard-stat__footer a {
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--brand-red);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition-smooth);
        }

        .dashboard-stat__footer a:hover {
            color: var(--brand-red-dark);
            gap: 0.75rem;
        }

        /* Chart Card */
        .card {
            border-radius: 1.2rem;
            border: 1.5px solid rgba(255, 212, 0, 0.1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04), 0 8px 24px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
            transition: var(--transition-smooth);
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08), 0 16px 32px rgba(0, 0, 0, 0.12);
            border-color: rgba(255, 212, 0, 0.2);
        }

        .card-header {
            background: linear-gradient(135deg, var(--brand-yellow) 0%, #ffcd00 100%) !important;
            color: var(--brand-red-dark) !important;
            border: none !important;
            padding: 1.5rem !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header .card-title {
            color: var(--brand-red-dark) !important;
            font-weight: 800 !important;
            font-size: 1.1rem !important;
            margin: 0 !important;
            letter-spacing: 0.3px;
        }

        .card-body {
            padding: 2rem 1.5rem;
            background: linear-gradient(135deg, #ffffff 0%, rgba(255, 212, 0, 0.02) 100%);
        }

        .activity-log-list {
            display: grid;
            gap: 0.7rem;
        }

        .activity-log-item {
            border: 1px solid #f0d6d8;
            border-radius: 0.85rem;
            padding: 0.75rem 0.9rem;
            background: linear-gradient(180deg, #fff 0%, #fff8f8 100%);
        }

        .activity-log-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.65rem;
            margin-bottom: 0.2rem;
        }

        .activity-log-user {
            font-weight: 700;
            color: #8f1b24;
        }

        .activity-log-time {
            font-size: 0.82rem;
            color: #6b7280;
            white-space: nowrap;
        }

        .activity-log-desc {
            margin: 0;
            color: #374151;
            font-weight: 600;
            font-size: 0.92rem;
        }

        /* Smooth animations */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dashboard-stat {
            animation: slideInUp 0.6s ease-out forwards;
        }

        .dashboard-stat:nth-child(1) { animation-delay: 0.1s; }
        .dashboard-stat:nth-child(2) { animation-delay: 0.2s; }
        .dashboard-stat:nth-child(3) { animation-delay: 0.3s; }
        .dashboard-stat:nth-child(4) { animation-delay: 0.4s; }

        .card {
            animation: slideInUp 0.6s ease-out 0.5s forwards;
            opacity: 0;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .dashboard-stat__value {
                font-size: 1.8rem;
            }

            .dashboard-stat__icon {
                width: 48px;
                height: 48px;
                font-size: 1.2rem;
            }

            .dashboard-stat__content {
                padding: 1.2rem 1.2rem 1rem;
            }
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

    @if(Auth::user()->isOwner())
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Activity Log Dashboard (5 Input Terakhir Karyawan)</h3>
                        <span class="badge bg-light text-dark">Auto refresh 30 detik</span>
                    </div>
                    <div class="card-body">
                        <div class="activity-log-list">
                            @forelse($recentActivities as $activity)
                                <div class="activity-log-item">
                                    <div class="activity-log-head">
                                        <div>
                                            <span class="activity-log-user">{{ $activity->penginput }}</span>
                                            @if($activity->tipe === 'masuk')
                                                <span class="badge bg-success ms-1">Masuk</span>
                                            @else
                                                <span class="badge bg-danger ms-1">Keluar</span>
                                            @endif
                                        </div>
                                        <span class="activity-log-time">{{ \Carbon\Carbon::parse($activity->created_at)->format('d M Y H:i') }} WIB</span>
                                    </div>
                                    <p class="activity-log-desc mb-0">
                                        {{ $activity->nama_barang }} - Qty {{ $activity->jumlah }}
                                    </p>
                                </div>
                            @empty
                                <div class="text-muted">Belum ada aktivitas input dari karyawan.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
                        borderColor: '#ffd400',
                        backgroundColor: 'rgba(255, 212, 0, 0.12)',
                        borderWidth: 3.5,
                        tension: 0.45,
                        fill: true,
                        pointRadius: 5,
                        pointBackgroundColor: '#ffd400',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2.5,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#ffd400',
                        pointHoverBorderWidth: 3
                    },
                    {
                        label: 'Barang Keluar',
                        data: @json($chartData['keluarData']),
                        borderColor: '#c62833',
                        backgroundColor: 'rgba(198, 40, 51, 0.12)',
                        borderWidth: 3.5,
                        tension: 0.45,
                        fill: true,
                        pointRadius: 5,
                        pointBackgroundColor: '#c62833',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2.5,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#c62833',
                        pointHoverBorderWidth: 3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20,
                            font: {
                                size: 13,
                                weight: 600
                            },
                            color: '#374151'
                        }
                    },
                    filler: {
                        propagate: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: {
                                size: 12,
                                weight: 500
                            },
                            color: '#9ca3af',
                            padding: 10
                        },
                        grid: {
                            color: 'rgba(229, 231, 235, 0.5)',
                            drawBorder: false,
                            lineWidth: 1
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12,
                                weight: 500
                            },
                            color: '#9ca3af',
                            padding: 8
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });

        @if(Auth::user()->isOwner())
            setInterval(function () {
                window.location.reload();
            }, 30000);
        @endif
    </script>
    @endpush
@endsection
