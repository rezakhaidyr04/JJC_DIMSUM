@extends('layouts.app')

@section('title', 'Barang Keluar')
@section('page-title', 'Barang Keluar')

@section('content')
    @push('styles')
    <style>
        .barang-keluar-insights {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.75rem;
            margin-bottom: 0.95rem;
        }

        .barang-keluar-insight {
            background: linear-gradient(180deg, #fff 0%, #fff7f8 100%);
            border: 1px solid #f3cfd3;
            border-radius: 0.8rem;
            padding: 0.7rem 0.85rem;
            box-shadow: 0 10px 20px rgba(185, 23, 32, 0.08);
        }

        .barang-keluar-insight__label {
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

        .barang-keluar-insight__value {
            color: #7f1d1d;
            font-size: 1.1rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .barang-keluar-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-bottom: 0.95rem;
        }

        .barang-keluar-chip {
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

        .barang-keluar-table {
            border-radius: 0.82rem;
            overflow: hidden;
            border: 1px solid #f1d1d4;
            box-shadow: 0 12px 26px rgba(198, 40, 51, 0.1);
        }

        .barang-keluar-table .table thead th {
            background: linear-gradient(90deg, #cf202c 0%, #b91720 100%);
            color: #fff;
            border: none;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .barang-keluar-table .table tbody tr {
            transition: transform 0.14s ease, background-color 0.14s ease;
        }

        .barang-keluar-table .table tbody tr:hover {
            background-color: #fff6f6;
            transform: translateX(2px);
        }

        .barang-keluar-no {
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

        .barang-keluar-jumlah {
            background: linear-gradient(180deg, #dc2626 0%, #b91c1c 100%) !important;
            color: #fff;
            border: 1px solid rgba(185, 28, 28, 0.4);
            box-shadow: 0 4px 10px rgba(220, 38, 38, 0.2);
        }

        .barang-keluar-tanggal {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-weight: 600;
            color: #7f1d1d;
        }

        @media (max-width: 767px) {
            .barang-keluar-insights {
                grid-template-columns: 1fr;
                gap: 0.55rem;
                margin-bottom: 0.72rem;
            }

            .barang-keluar-insight {
                padding: 0.58rem 0.7rem;
            }

            .barang-keluar-insight__label {
                font-size: 0.7rem;
            }

            .barang-keluar-insight__value {
                font-size: 0.95rem;
            }

            .barang-keluar-meta {
                gap: 0.45rem;
                margin-bottom: 0.72rem;
            }

            .barang-keluar-chip {
                font-size: 0.74rem;
                padding: 0.28rem 0.55rem;
            }

            .barang-keluar-tanggal {
                font-size: 0.78rem;
                white-space: nowrap;
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

            .barang-keluar-chip {
                font-size: 0.7rem;
                padding: 0.24rem 0.48rem;
            }

            .barang-keluar-table .table th,
            .barang-keluar-table .table td {
                white-space: nowrap;
            }

            .barang-keluar-tanggal {
                font-size: 0.72rem;
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
                $jumlahHalamanIni = $barangKeluar->sum('jumlah');
                $terakhirInput = $barangKeluar->first();
            @endphp

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Barang Keluar</h3>
                    @if(Auth::user()->isOwner() || Auth::user()->isKaryawan())
                        <div class="card-tools">
                            <a href="{{ route('barang-keluar.create') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="barang-keluar-insights">
                        <div class="barang-keluar-insight">
                            <div class="barang-keluar-insight__label"><i class="fas fa-layer-group"></i> Total Entry</div>
                            <div class="barang-keluar-insight__value">{{ $barangKeluar->total() }}</div>
                        </div>
                        <div class="barang-keluar-insight">
                            <div class="barang-keluar-insight__label"><i class="fas fa-box-open"></i> Jumlah Halaman Ini</div>
                            <div class="barang-keluar-insight__value">{{ $jumlahHalamanIni }}</div>
                        </div>
                        <div class="barang-keluar-insight">
                            <div class="barang-keluar-insight__label"><i class="fas fa-history"></i> Input Terakhir</div>
                            <div class="barang-keluar-insight__value">{{ $terakhirInput ? $terakhirInput->created_at->format('d M Y H:i') . ' WIB' : '-' }}</div>
                        </div>
                    </div>

                    <div class="barang-keluar-meta">
                        <span class="barang-keluar-chip"><i class="fas fa-database"></i> Total Entry: {{ $barangKeluar->total() }}</span>
                        <span class="barang-keluar-chip"><i class="fas fa-clock"></i> Sinkron: {{ now()->format('d M Y H:i') }} WIB</span>
                    </div>

                    <div class="table-responsive barang-keluar-table">
                    <table class="table table-bordered table-striped table-hover table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Barang</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                @if(Auth::user()->isOwner())
                                    <th style="width: 20%">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if($barangKeluar->count() > 0)
                                @foreach($barangKeluar as $item)
                                    <tr>
                                        <td><span class="barang-keluar-no">{{ ($barangKeluar->currentPage() - 1) * $barangKeluar->perPage() + $loop->iteration }}</span></td>
                                        <td>{{ $item->barang->nama_barang }}</td>
                                        <td>
                                            <span class="badge barang-keluar-jumlah">{{ $item->jumlah }}</span>
                                        </td>
                                        <td><span class="barang-keluar-tanggal"><i class="far fa-calendar-alt"></i>{{ $item->tanggal->format('d M Y') }}</span></td>
                                        @if(Auth::user()->isOwner())
                                            <td>
                                                <div class="actions-inline">
                                                    <a href="{{ route('barang-keluar.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit" aria-hidden="true"></i>
                                                        <span class="action-label">Edit</span>
                                                    </a>
                                                    <form method="POST" action="{{ route('barang-keluar.destroy', $item->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus" aria-label="Hapus barang keluar">
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
                                    <td colspan="{{ Auth::user()->isOwner() ? 5 : 4 }}" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $barangKeluar->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
