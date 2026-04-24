@extends('layouts.app')

@section('title', 'Barang Masuk')
@section('page-title', 'Barang Masuk')

@section('content')
    @push('styles')
    <style>
        .barang-masuk-insights {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.75rem;
            margin-bottom: 0.95rem;
        }

        .barang-masuk-insight {
            background: linear-gradient(180deg, #fff 0%, #fff7f8 100%);
            border: 1px solid #f3cfd3;
            border-radius: 0.8rem;
            padding: 0.7rem 0.85rem;
            box-shadow: 0 10px 20px rgba(185, 23, 32, 0.08);
        }

        .barang-masuk-insight__label {
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

        .barang-masuk-insight__value {
            color: #7f1d1d;
            font-size: 1.1rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .barang-masuk-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-bottom: 0.95rem;
        }

        .barang-masuk-chip {
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

        .barang-masuk-table {
            border-radius: 0.82rem;
            overflow: hidden;
            border: 1px solid #f1d1d4;
            box-shadow: 0 12px 26px rgba(198, 40, 51, 0.1);
        }

        .barang-masuk-table .table thead th {
            background: linear-gradient(90deg, #cf202c 0%, #b91720 100%);
            color: #fff;
            border: none;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .barang-masuk-table .table tbody tr {
            transition: transform 0.14s ease, background-color 0.14s ease;
        }

        .barang-masuk-table .table tbody tr:hover {
            background-color: #fff6f6;
            transform: translateX(2px);
        }

        .barang-masuk-tanggal {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-weight: 600;
            color: #7f1d1d;
        }

        .barang-masuk-no {
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

        .barang-masuk-jumlah {
            background: linear-gradient(180deg, #16a34a 0%, #15803d 100%) !important;
            color: #fff;
            border: 1px solid rgba(21, 128, 61, 0.4);
            box-shadow: 0 4px 10px rgba(22, 163, 74, 0.2);
        }

        @media (max-width: 767px) {
            .barang-masuk-insights {
                grid-template-columns: 1fr;
                gap: 0.55rem;
                margin-bottom: 0.72rem;
            }

            .barang-masuk-insight {
                padding: 0.58rem 0.7rem;
            }

            .barang-masuk-insight__label {
                font-size: 0.7rem;
            }

            .barang-masuk-insight__value {
                font-size: 0.95rem;
            }

            .barang-masuk-meta {
                gap: 0.45rem;
                margin-bottom: 0.72rem;
            }

            .barang-masuk-chip {
                font-size: 0.74rem;
                padding: 0.28rem 0.55rem;
            }

            .barang-masuk-tanggal {
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

            .barang-masuk-chip {
                font-size: 0.7rem;
                padding: 0.24rem 0.48rem;
            }

            .barang-masuk-table .table th,
            .barang-masuk-table .table td {
                white-space: nowrap;
            }

            .barang-masuk-tanggal {
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
                $jumlahHalamanIni = $barangMasuk->sum('jumlah');
                $terakhirInput = $barangMasuk->first();
            @endphp

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Barang Masuk</h3>
                    @if(Auth::user()->isOwner() || Auth::user()->isKaryawan())
                        <div class="card-tools">
                            <a href="{{ route('barang-masuk.create') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="barang-masuk-insights">
                        <div class="barang-masuk-insight">
                            <div class="barang-masuk-insight__label"><i class="fas fa-layer-group"></i> Total Entry</div>
                            <div class="barang-masuk-insight__value">{{ $barangMasuk->total() }}</div>
                        </div>
                        <div class="barang-masuk-insight">
                            <div class="barang-masuk-insight__label"><i class="fas fa-box-open"></i> Jumlah Halaman Ini</div>
                            <div class="barang-masuk-insight__value">{{ $jumlahHalamanIni }}</div>
                        </div>
                        <div class="barang-masuk-insight">
                            <div class="barang-masuk-insight__label"><i class="fas fa-history"></i> Input Terakhir</div>
                            <div class="barang-masuk-insight__value">{{ $terakhirInput ? $terakhirInput->created_at->format('d M Y H:i') . ' WIB' : '-' }}</div>
                        </div>
                    </div>

                    <div class="barang-masuk-meta">
                        <span class="barang-masuk-chip"><i class="fas fa-database"></i> Total Entry: {{ $barangMasuk->total() }}</span>
                        <span class="barang-masuk-chip"><i class="fas fa-clock"></i> Sinkron: {{ now()->format('d M Y H:i') }} WIB</span>
                    </div>

                    <div class="table-responsive barang-masuk-table">
                    <table class="table table-bordered table-striped table-hover table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Barang</th>
                                <th>Jumlah</th>
                                <th>Tanggal Masuk</th>
                                <th>Penginput</th>
                                <th>Status Void</th>
                                @if(Auth::user()->isOwner() || Auth::user()->isKaryawan())
                                    <th style="width: 20%">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if($barangMasuk->count() > 0)
                                @foreach($barangMasuk as $item)
                                    <tr>
                                        <td>
                                            <span class="barang-masuk-no">{{ ($barangMasuk->currentPage() - 1) * $barangMasuk->perPage() + $loop->iteration }}</span>
                                        </td>
                                        <td>{{ $item->barang->nama_barang }}</td>
                                        <td>
                                            <span class="badge barang-masuk-jumlah">{{ $item->jumlah }}</span>
                                        </td>
                                        <td>
                                            <span class="barang-masuk-tanggal">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ $item->created_at->format('d M Y H:i') }} WIB
                                            </span>
                                        </td>
                                        <td>{{ $item->user?->name ?? '-' }}</td>
                                        <td>
                                            @if($item->void_status === 'pending')
                                                <span class="badge bg-warning text-dark">Pending Void</span>
                                            @elseif($item->void_status === 'approved')
                                                <span class="badge bg-danger">Voided</span>
                                            @else
                                                <span class="badge bg-success">Normal</span>
                                            @endif
                                        </td>
                                        @if(Auth::user()->isOwner() || Auth::user()->isKaryawan())
                                            <td>
                                                <div class="actions-inline">
                                                    @if(Auth::user()->isOwner())
                                                        @if($item->void_status === 'pending')
                                                            <form method="POST" action="{{ route('barang-masuk.approve-void', $item->id) }}">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Setujui void dan hapus data ini?')">
                                                                    <i class="fas fa-check"></i>
                                                                    <span class="action-label">Approve Void</span>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <a href="{{ route('barang-masuk.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                                                <span class="action-label">Edit</span>
                                                            </a>
                                                            <form method="POST" action="{{ route('barang-masuk.destroy', $item->id) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus" aria-label="Hapus barang masuk">
                                                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                                                    <span class="action-label">Hapus</span>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @elseif(Auth::user()->isKaryawan())
                                                        @if($item->void_status === 'pending')
                                                            <span class="badge bg-warning text-dark">Menunggu approval owner</span>
                                                        @else
                                                            <form method="POST" action="{{ route('barang-masuk.request-void', $item->id) }}" class="void-request-form">
                                                                @csrf
                                                                <input type="hidden" name="void_reason" value="">
                                                                <button type="button" class="btn btn-outline-danger btn-sm js-btn-void">
                                                                    <i class="fas fa-ban"></i>
                                                                    <span class="action-label">Request Void</span>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                </div>
                                                @if($item->void_status === 'pending' && $item->void_reason)
                                                    <small class="text-muted d-block mt-1">Alasan: {{ $item->void_reason }}</small>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="{{ Auth::user()->isOwner() || Auth::user()->isKaryawan() ? 7 : 6 }}" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $barangMasuk->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('.void-request-form .js-btn-void').forEach(function (button) {
            button.addEventListener('click', function () {
                const form = button.closest('form');
                const reason = window.prompt('Masukkan alasan request void (minimal 10 karakter):');

                if (!reason) {
                    return;
                }

                if (reason.trim().length < 10) {
                    alert('Alasan minimal 10 karakter.');
                    return;
                }

                form.querySelector('input[name="void_reason"]').value = reason.trim();
                form.submit();
            });
        });
    </script>
    @endpush
@endsection
