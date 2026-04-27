@extends('layouts.app')

@section('title', 'Riwayat Transaksi Barang')
@section('page-title', 'Riwayat Transaksi Barang')

@section('content')
    @push('styles')
    <style>
        .transaction-card {
            border-left: 4px solid;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .transaction-card.masuk {
            border-left-color: #22c55e;
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.08), rgba(34, 197, 94, 0.02));
        }

        .transaction-card.keluar {
            border-left-color: #ef4444;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.08), rgba(239, 68, 68, 0.02));
        }

        .transaction-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 0.75rem;
        }

        .transaction-type {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .transaction-type.masuk {
            background-color: #dcfce7;
            color: #166534;
        }

        .transaction-type.keluar {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .transaction-date {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
        }

        .fifo-detail {
            background-color: rgba(59, 130, 246, 0.05);
            border-left: 3px solid #3b82f6;
            padding: 0.75rem;
            border-radius: 0.375rem;
            margin-top: 0.75rem;
            font-size: 0.875rem;
        }

        .fifo-label {
            color: #3b82f6;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .transaction-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 0.75rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .detail-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
        }

        .detail-value {
            font-size: 0.95rem;
            color: #1f2937;
            font-weight: 600;
        }

        .quantity-badge {
            background-color: #fef3c7;
            color: #92400e;
            padding: 0.25rem 0.75rem;
            border-radius: 0.375rem;
            font-weight: 700;
            font-size: 0.875rem;
        }

        .user-badge {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 0.25rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #9ca3af;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .filter-section {
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
            border: 1.5px solid rgba(255, 212, 0, 0.1);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .filter-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .form-control {
            border-radius: 0.5rem;
            border: 1px solid #d6dbe3;
        }

        .btn-filter {
            background-color: #c62833;
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.625rem 1.25rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-filter:hover {
            background-color: #8f1b24;
        }

        .btn-reset {
            background-color: #e5e7eb;
            color: #374151;
            border: none;
            border-radius: 0.5rem;
            padding: 0.625rem 1.25rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-reset:hover {
            background-color: #d1d5db;
        }
    </style>
    @endpush

    <div class="filter-section">
        <form method="GET" class="filter-group">
            <div>
                <label class="form-label">Barang</label>
                <select name="barang_id" class="form-control">
                    <option value="">Semua Barang</option>
                    @foreach($barangList as $barang)
                        <option value="{{ $barang->id }}" {{ request('barang_id') == $barang->id ? 'selected' : '' }}>
                            {{ $barang->nama_barang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Cabang</label>
                <select name="cabang_id" class="form-control">
                    <option value="">Semua Cabang</option>
                    @foreach($cabangList as $cabang)
                        <option value="{{ $cabang->id }}" {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>
                            {{ $cabang->nama_cabang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Tipe Transaksi</label>
                <select name="tipe" class="form-control">
                    <option value="">Semua Tipe</option>
                    <option value="masuk" {{ request('tipe') == 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
                    <option value="keluar" {{ request('tipe') == 'keluar' ? 'selected' : '' }}>Barang Keluar</option>
                </select>
            </div>

            <div>
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="dari_tanggal" class="form-control" value="{{ request('dari_tanggal') }}">
            </div>

            <div>
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="sampai_tanggal" class="form-control" value="{{ request('sampai_tanggal') }}">
            </div>

            <div style="display: flex; align-items: flex-end; gap: 0.5rem;">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('riwayat-transaksi') }}" class="btn-reset">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>

    @if($transactions->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Total: {{ $transactions->count() }} Transaksi</h3>
                    </div>
                    <div class="card-body">
                        @foreach($transactions as $transaction)
                            @if($transaction['tipe'] === 'masuk')
                                <div class="transaction-card masuk">
                                    <div class="transaction-header">
                                        <div>
                                            <span class="transaction-type masuk">
                                                <i class="fas fa-arrow-down"></i> Masuk
                                            </span>
                                        </div>
                                        <span class="transaction-date">
                                            {{ $transaction['tanggal']->format('d M Y H:i') }}
                                        </span>
                                    </div>

                                    <div class="transaction-details">
                                        <div class="detail-item">
                                            <span class="detail-label">Barang</span>
                                            <span class="detail-value">{{ $transaction['barang_nama'] }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Cabang</span>
                                            <span class="detail-value">{{ $transaction['cabang_nama'] }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Lokasi</span>
                                            <span class="detail-value">{{ $transaction['lokasi_nama'] }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Jumlah</span>
                                            <span class="quantity-badge">{{ $transaction['jumlah'] }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Input Oleh</span>
                                            <span class="user-badge">{{ $transaction['user_name'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="transaction-card keluar">
                                    <div class="transaction-header">
                                        <div>
                                            <span class="transaction-type keluar">
                                                <i class="fas fa-arrow-up"></i> Keluar
                                            </span>
                                        </div>
                                        <span class="transaction-date">
                                            {{ $transaction['tanggal']->format('d M Y H:i') }}
                                        </span>
                                    </div>

                                    <div class="transaction-details">
                                        <div class="detail-item">
                                            <span class="detail-label">Barang</span>
                                            <span class="detail-value">{{ $transaction['barang_nama'] }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Cabang</span>
                                            <span class="detail-value">{{ $transaction['cabang_nama'] }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Lokasi</span>
                                            <span class="detail-value">{{ $transaction['lokasi_nama'] }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Jumlah</span>
                                            <span class="quantity-badge">{{ $transaction['jumlah'] }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Output Oleh</span>
                                            <span class="user-badge">{{ $transaction['user_name'] }}</span>
                                        </div>
                                    </div>

                                    @if($transaction['fifo_info'])
                                        <div class="fifo-detail">
                                            <div class="fifo-label">
                                                <i class="fas fa-history"></i> Info FIFO
                                            </div>
                                            <div style="font-size: 0.85rem; line-height: 1.6;">
                                                <div><strong>Barang Masuk:</strong> {{ $transaction['fifo_info']['tanggal_masuk'] }}</div>
                                                <div><strong>Umur Stok:</strong> {{ $transaction['fifo_info']['umur_hari'] }} hari</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Pagination --}}
                <div style="margin-top: 2rem;">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <p><strong>Tidak ada transaksi</strong></p>
                <p style="font-size: 0.9rem;">Belum ada data barang masuk atau barang keluar yang sesuai dengan filter.</p>
            </div>
        </div>
    @endif
@endsection
