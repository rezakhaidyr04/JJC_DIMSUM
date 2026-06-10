@extends('layouts.app')

@section('title', 'Approvals')
@section('page-title', 'Approval Void & Hapus')

@section('content')
    @push('styles')
    <style>
        .approvals-shell {
            margin-top: 1rem;
        }

        .approvals-shell .card {
            border-radius: 1rem;
            border: 1px solid #e8edf3;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(17, 24, 39, 0.08);
        }

        .approvals-tabs {
            border-bottom: 1px solid #dbe2ea;
            gap: 0.35rem;
        }

        .approvals-tabs .nav-link {
            color: #5b6472;
            border: 1px solid transparent;
            border-top-left-radius: 0.8rem;
            border-top-right-radius: 0.8rem;
            font-weight: 700;
            padding: 0.8rem 1rem;
        }

        .approvals-tabs .nav-link:hover {
            color: var(--accent-red);
            border-color: rgba(220, 38, 38, 0.12);
            background: rgba(220, 38, 38, 0.04);
        }

        .approvals-tabs .nav-link.active {
            color: var(--accent-red);
            background: #fff;
            border-color: #d9e1ea #d9e1ea #fff;
            box-shadow: 0 -2px 10px rgba(17, 24, 39, 0.04);
        }

        .approvals-alert {
            border: 1px solid rgba(13, 202, 240, 0.18);
            background: linear-gradient(135deg, #1aa6be 0%, #1f9bb3 100%);
            color: #fff;
            border-radius: 0.8rem;
            box-shadow: 0 8px 20px rgba(26, 166, 190, 0.18);
        }

        .approvals-alert strong {
            color: #fff;
        }

        .approvals-table thead th {
            background: linear-gradient(90deg, var(--accent-red) 0%, #B91720 100%);
            color: #fff;
            border: none;
        }

        .approvals-table tbody tr:nth-child(even) {
            background: #fcfcfd;
        }

        .approvals-table td,
        .approvals-table th {
            vertical-align: middle;
        }

        .approvals-table .btn {
            border-radius: 0.55rem;
            font-weight: 700;
        }

        .approvals-table .btn-success {
            background: var(--accent-red);
            border-color: var(--accent-red);
        }

        .approvals-table .btn-success:hover {
            background: #b91c1c;
            border-color: #b91c1c;
        }

        .approvals-panel {
            border: 1px solid #e8edf3;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(17, 24, 39, 0.08);
            background: #fff;
        }

        .approvals-panel .card-header {
            background: linear-gradient(90deg, var(--accent-red) 0%, #B91720 100%);
            color: #fff;
            border: none;
            font-weight: 700;
            padding: 0.9rem 1.1rem;
        }

        .approvals-panel .card-body {
            padding: 1rem;
        }

        @media (max-width: 767px) {
            .approvals-tabs {
                gap: 0.25rem;
            }

            .approvals-tabs .nav-link {
                padding: 0.65rem 0.8rem;
                font-size: 0.9rem;
            }
        }
    </style>
    @endpush

    <div class="card approvals-shell">
        <div class="card-body">
            <ul class="nav nav-tabs approvals-tabs mb-3" id="approvalsTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="hapus-tab" data-bs-toggle="tab" data-bs-target="#hapus" type="button" role="tab" aria-controls="hapus" aria-selected="true">Hapus Barang</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="void-tab" data-bs-toggle="tab" data-bs-target="#void" type="button" role="tab" aria-controls="void" aria-selected="false">Void Pending</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="riwayat-masuk-tab" data-bs-toggle="tab" data-bs-target="#riwayat-masuk" type="button" role="tab" aria-controls="riwayat-masuk" aria-selected="false">Riwayat Void — Masuk</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="riwayat-keluar-tab" data-bs-toggle="tab" data-bs-target="#riwayat-keluar" type="button" role="tab" aria-controls="riwayat-keluar" aria-selected="false">Riwayat Void — Keluar</button>
                </li>
            </ul>

            <div class="tab-content" id="approvalsTabContent">
                {{-- Hapus Barang tab --}}
                <div class="tab-pane fade show active" id="hapus" role="tabpanel" aria-labelledby="hapus-tab">
                    <div class="mb-3 alert approvals-alert small">
                        Pengajuan dari karyawan akan muncul di sini. Owner pilih <strong>Approve</strong> untuk menghapus atau <strong>Reject</strong> bila belum sesuai.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-hover approvals-table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:4%">#</th>
                                    <th>Barang / Kode</th>
                                    <th style="width:18%">Pengaju</th>
                                    <th>Alasan</th>
                                    <th style="width:10%">Tanggal</th>
                                    <th style="width:10%">Status</th>
                                    <th style="width:12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deleteRequests as $r)
                                    <tr>
                                        <td>{{ ($deleteRequests->currentPage() - 1) * $deleteRequests->perPage() + $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $r->barang_nama ?? ($r->barang?->nama_barang ?: '—') }}</div>
                                            <div class="text-muted small">{{ $r->barang_kode ?? ($r->barang?->kode_barang ?: '—') }}</div>
                                        </td>
                                        <td class="small">{{ $r->user?->name ?? '—' }}</td>
                                        <td class="small text-truncate" style="max-width:260px">{{ $r->reason }}</td>
                                        <td class="small">{{ $r->created_at->format('d M Y') }}</td>
                                        <td>
                                            @if($r->status === 'pending')
                                                <span class="badge bg-warning text-dark">pending</span>
                                            @elseif($r->status === 'approved')
                                                <span class="badge bg-success">approved</span>
                                            @else
                                                <span class="badge bg-danger">rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($r->status === 'pending')
                                                <form method="POST" action="{{ route('barang-delete-requests.approve', $r->id) }}" style="display:inline-block">@csrf<button class="btn btn-sm btn-success">Approve</button></form>
                                                <form method="POST" action="{{ route('barang-delete-requests.reject', $r->id) }}" style="display:inline-block">@csrf<button class="btn btn-sm btn-outline-danger">Reject</button></form>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">Belum ada pengajuan hapus barang.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $deleteRequests->links() }}
                    </div>
                </div>

                {{-- Void Pending tab --}}
                <div class="tab-pane fade show" id="void" role="tabpanel" aria-labelledby="void-tab">
                    {{-- Pending Void - each full-width so tables are readable --}}
                    <div class="mb-3">
                        <div class="card mb-3 approvals-panel">
                            <div class="card-header py-2"><strong>Pending Void — Masuk</strong></div>
                            <div class="card-body p-2">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0 approvals-table">
                                        <thead class="table-light small">
                                            <tr>
                                                <th style="width:4%">#</th>
                                                <th>Barang</th>
                                                <th style="width:10%">Jumlah</th>
                                                <th style="width:18%">Peminta</th>
                                                <th style="width:12%">Waktu</th>
                                                <th style="width:12%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="small">
                                            @forelse($pendingMasuk as $item)
                                                <tr>
                                                    <td>{{ ($pendingMasuk->currentPage() - 1) * $pendingMasuk->perPage() + $loop->iteration }}</td>
                                                    <td>{{ $item->barang?->nama_barang ?? '-' }}</td>
                                                    <td>{{ $item->jumlah }}</td>
                                                    <td>{{ $item->voidRequester?->name ?? '-' }}</td>
                                                    <td>{{ optional($item->void_requested_at)->format('d M Y H:i') }} WIB</td>
                                                    <td>
                                                        <form method="POST" action="{{ route('barang-masuk.approve-void', $item->getKey()) }}">@csrf<button class="btn btn-sm btn-danger">Approve</button></form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="6" class="text-center text-muted">Tidak ada request void untuk barang masuk.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-center mt-2">{{ $pendingMasuk->links() }}</div>
                            </div>
                        </div>

                        <div class="card mb-3 approvals-panel">
                            <div class="card-header py-2"><strong>Pending Void — Keluar</strong></div>
                            <div class="card-body p-2">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0 approvals-table">
                                        <thead class="table-light small">
                                            <tr>
                                                <th style="width:4%">#</th>
                                                <th>Barang</th>
                                                <th style="width:10%">Jumlah</th>
                                                <th style="width:18%">Peminta</th>
                                                <th style="width:12%">Waktu</th>
                                                <th style="width:12%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="small">
                                            @forelse($pendingKeluar as $item)
                                                <tr>
                                                    <td>{{ ($pendingKeluar->currentPage() - 1) * $pendingKeluar->perPage() + $loop->iteration }}</td>
                                                    <td>{{ $item->barang?->nama_barang ?? '-' }}</td>
                                                    <td>{{ $item->jumlah }}</td>
                                                    <td>{{ $item->voidRequester?->name ?? '-' }}</td>
                                                    <td>{{ optional($item->void_requested_at)->format('d M Y H:i') }} WIB</td>
                                                    <td>
                                                        <form method="POST" action="{{ route('barang-keluar.approve-void', $item->getKey()) }}">@csrf<button class="btn btn-sm btn-danger">Approve</button></form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="6" class="text-center text-muted">Tidak ada request void untuk barang keluar.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-center mt-2">{{ $pendingKeluar->links() }}</div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Riwayat Void Masuk tab --}}
                <div class="tab-pane fade" id="riwayat-masuk" role="tabpanel" aria-labelledby="riwayat-masuk-tab">
                    <div class="card approvals-panel">
                        <div class="card-header py-2"><strong>Riwayat Void — Masuk</strong></div>
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0 approvals-table">
                                    <thead class="table-light small">
                                        <tr>
                                            <th style="width:4%">#</th>
                                            <th>Barang</th>
                                            <th style="width:10%">Jumlah</th>
                                            <th style="width:18%">Approver</th>
                                            <th style="width:12%">Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small">
                                        @forelse($approvedMasuk as $item)
                                            <tr>
                                                <td>{{ ($approvedMasuk->currentPage() - 1) * $approvedMasuk->perPage() + $loop->iteration }}</td>
                                                <td>{{ $item->barang?->nama_barang ?? '-' }}</td>
                                                <td>{{ $item->jumlah }}</td>
                                                <td>{{ $item->voidApprover?->name ?? '-' }}</td>
                                                <td>{{ optional($item->void_approved_at)->format('d M Y H:i') ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-center text-muted">Belum ada riwayat void disetujui untuk barang masuk.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-2">{{ $approvedMasuk->links() }}</div>
                        </div>
                    </div>
                </div>

                {{-- Riwayat Void Keluar tab --}}
                <div class="tab-pane fade" id="riwayat-keluar" role="tabpanel" aria-labelledby="riwayat-keluar-tab">
                    <div class="card approvals-panel">
                        <div class="card-header py-2"><strong>Riwayat Void — Keluar</strong></div>
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0 approvals-table">
                                    <thead class="table-light small">
                                        <tr>
                                            <th style="width:4%">#</th>
                                            <th>Barang</th>
                                            <th style="width:10%">Jumlah</th>
                                            <th style="width:18%">Approver</th>
                                            <th style="width:12%">Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small">
                                        @forelse($approvedKeluar as $item)
                                            <tr>
                                                <td>{{ ($approvedKeluar->currentPage() - 1) * $approvedKeluar->perPage() + $loop->iteration }}</td>
                                                <td>{{ $item->barang?->nama_barang ?? '-' }}</td>
                                                <td>{{ $item->jumlah }}</td>
                                                <td>{{ $item->voidApprover?->name ?? '-' }}</td>
                                                <td>{{ optional($item->void_approved_at)->format('d M Y H:i') ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-center text-muted">Belum ada riwayat void disetujui untuk barang keluar.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-2">{{ $approvedKeluar->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
