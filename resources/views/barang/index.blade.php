@extends('layouts.app')

@section('title', 'Data Barang')
@section('page-title', 'Data Barang')

@section('content')
    @push('styles')
    <style>
        .barang-insights {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.75rem;
            margin-bottom: 0.95rem;
        }

        .barang-insight {
            background: linear-gradient(180deg, #fff 0%, #fff8f8 100%);
            border: 1px solid #f3cfd3;
            border-radius: 0.8rem;
            padding: 0.7rem 0.85rem;
            box-shadow: 0 10px 20px rgba(185, 23, 32, 0.08);
        }

        .barang-insight__label {
            font-size: 0.76rem;
            color: #9f1d28;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.2rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .barang-insight__value {
            color: #7f1d1d;
            font-size: 1.1rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .barang-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-bottom: 0.95rem;
        }

        .barang-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.38rem;
            border: 1px solid #f6c8cc;
            background: linear-gradient(180deg, #fffafa 0%, #fff3f4 100%);
            color: #8f1b24;
            border-radius: 999px;
            padding: 0.36rem 0.7rem;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .barang-table {
            border-radius: 0.82rem;
            overflow: hidden;
            border: 1px solid #f1d1d4;
            box-shadow: 0 12px 26px rgba(198, 40, 51, 0.1);
        }

        .barang-table .table thead th {
            background: linear-gradient(90deg, #cf202c 0%, #b91720 100%);
            color: #fff;
            border: none;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .barang-table .table tbody tr {
            transition: transform 0.14s ease, background-color 0.14s ease;
        }

        .barang-table .table tbody tr:hover {
            background-color: #fff6f6;
            transform: translateX(2px);
        }

        .barang-no {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 1.8rem;
            height: 1.8rem;
            border-radius: 999px;
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #9f1239;
            font-weight: 700;
            font-size: 0.78rem;
        }

        .barang-stok {
            background: linear-gradient(180deg, #2563eb 0%, #1d4ed8 100%) !important;
            color: #fff;
            border: 1px solid rgba(29, 78, 216, 0.45);
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
        }

        @media (max-width: 767px) {
            .barang-insights {
                grid-template-columns: 1fr;
                gap: 0.55rem;
                margin-bottom: 0.72rem;
            }

            .barang-insight {
                padding: 0.58rem 0.7rem;
            }

            .barang-insight__label {
                font-size: 0.7rem;
            }

            .barang-insight__value {
                font-size: 0.95rem;
            }

            .barang-meta {
                gap: 0.45rem;
                margin-bottom: 0.72rem;
            }

            .barang-chip {
                font-size: 0.74rem;
                padding: 0.28rem 0.55rem;
            }
        }

        @media (max-width: 480px) {
            .card-header {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 0.45rem;
            }

            .card-tools {
                width: 100%;
            }

            .card-tools .btn {
                width: 100%;
            }

            .barang-chip {
                font-size: 0.7rem;
                padding: 0.24rem 0.48rem;
            }

            .barang-table .table th,
            .barang-table .table td {
                white-space: nowrap;
            }

            .actions-inline {
                justify-content: center;
            }

            .actions-inline .btn {
                min-width: 2rem;
                min-height: 2rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.28rem;
            }

            .actions-inline .action-label {
                display: none;
            }
        }
    </style>
    @endpush

    <div class="row">
        <div class="col-md-12">
            @php
                $totalStokHalaman = $barang->sum('stok');
                $barangStokTertinggi = $barang->sortByDesc('stok')->first();
            @endphp

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Barang</h3>
                    @if(Auth::user()->isOwner() || Auth::user()->isKaryawan())
                        <div class="card-tools">
                            <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Barang
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="barang-insights">
                        <div class="barang-insight">
                            <div class="barang-insight__label"><i class="fas fa-layer-group"></i> Total Barang</div>
                            <div class="barang-insight__value">{{ $barang->total() }}</div>
                        </div>
                        <div class="barang-insight">
                            <div class="barang-insight__label"><i class="fas fa-boxes"></i> Total Stok Halaman Ini</div>
                            <div class="barang-insight__value">{{ $totalStokHalaman }}</div>
                        </div>
                        <div class="barang-insight">
                            <div class="barang-insight__label"><i class="fas fa-trophy"></i> Stok Tertinggi</div>
                            <div class="barang-insight__value">{{ $barangStokTertinggi ? $barangStokTertinggi->nama_barang . ' (' . $barangStokTertinggi->stok . ')' : '-' }}</div>
                        </div>
                    </div>

                    <div class="barang-meta">
                        <span class="barang-chip"><i class="fas fa-database"></i> Total Barang: {{ $barang->total() }}</span>
                        <span class="barang-chip"><i class="fas fa-clock"></i> Sinkron: {{ now()->format('d M Y H:i') }} WIB</span>
                    </div>

                    <div class="table-responsive barang-table">
                    <table class="table table-bordered table-striped table-hover table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Nama Barang</th>
                                <th style="width: 15%">Stok</th>
                                @if(Auth::user()->isOwner())
                                    <th style="width: 20%">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if($barang->count() > 0)
                                @foreach($barang as $item)
                                    <tr>
                                        <td><span class="barang-no">{{ ($barang->currentPage() - 1) * $barang->perPage() + $loop->iteration }}</span></td>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td>
                                            <span class="badge barang-stok">{{ $item->stok }}</span>
                                        </td>
                                        @if(Auth::user()->isOwner())
                                            <td>
                                                <div class="actions-inline">
                                                    <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit" aria-hidden="true"></i>
                                                        <span class="action-label">Edit</span>
                                                    </a>
                                                    <form method="POST" action="{{ route('barang.destroy', $item->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus" aria-label="Hapus data barang">
                                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                                            <span class="action-label">Hapus</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="{{ Auth::user()->isOwner() ? 4 : 3 }}" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $barang->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
