@extends('layouts.app')

@section('title', 'Approval Void')
@section('page-title', 'Approval Void Transaksi')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pending Void - Barang Masuk</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th>Barang</th>
                                    <th class="text-center">Jumlah</th>
                                    <th>Penginput</th>
                                    <th>Peminta Void</th>
                                    <th>Alasan Void</th>
                                    <th class="text-center">Waktu Request</th>
                                    <th class="text-center" style="width: 13%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingMasuk as $item)
                                    <tr>
                                        <td class="text-center">{{ ($pendingMasuk->currentPage() - 1) * $pendingMasuk->perPage() + $loop->iteration }}</td>
                                        <td>{{ $item->barang?->nama_barang ?? '-' }}</td>
                                        <td class="text-center">{{ $item->jumlah }}</td>
                                        <td>{{ $item->user?->name ?? '-' }}</td>
                                        <td>{{ $item->voidRequester?->name ?? '-' }}</td>
                                        <td>{{ $item->void_reason }}</td>
                                        <td class="text-center">{{ optional($item->void_requested_at)->format('d M Y H:i') }} WIB</td>
                                        <td class="text-center">
                                            <form method="POST" action="{{ route('barang-masuk.approve-void', $item->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Setujui void dan hapus transaksi ini?')">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Tidak ada request void untuk barang masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $pendingMasuk->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pending Void - Barang Keluar</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th>Barang</th>
                                    <th class="text-center">Jumlah</th>
                                    <th>Penginput</th>
                                    <th>Peminta Void</th>
                                    <th>Alasan Void</th>
                                    <th class="text-center">Waktu Request</th>
                                    <th class="text-center" style="width: 13%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingKeluar as $item)
                                    <tr>
                                        <td class="text-center">{{ ($pendingKeluar->currentPage() - 1) * $pendingKeluar->perPage() + $loop->iteration }}</td>
                                        <td>{{ $item->barang?->nama_barang ?? '-' }}</td>
                                        <td class="text-center">{{ $item->jumlah }}</td>
                                        <td>{{ $item->user?->name ?? '-' }}</td>
                                        <td>{{ $item->voidRequester?->name ?? '-' }}</td>
                                        <td>{{ $item->void_reason }}</td>
                                        <td class="text-center">{{ optional($item->void_requested_at)->format('d M Y H:i') }} WIB</td>
                                        <td class="text-center">
                                            <form method="POST" action="{{ route('barang-keluar.approve-void', $item->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Setujui void dan hapus transaksi ini?')">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Tidak ada request void untuk barang keluar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $pendingKeluar->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Void Disetujui - Barang Masuk</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th>Barang</th>
                                    <th class="text-center">Jumlah</th>
                                    <th>Penginput</th>
                                    <th>Peminta Void</th>
                                    <th>Approver</th>
                                    <th>Alasan Void</th>
                                    <th class="text-center">Waktu Approve</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($approvedMasuk as $item)
                                    <tr>
                                        <td class="text-center">{{ ($approvedMasuk->currentPage() - 1) * $approvedMasuk->perPage() + $loop->iteration }}</td>
                                        <td>{{ $item->barang?->nama_barang ?? '-' }}</td>
                                        <td class="text-center">{{ $item->jumlah }}</td>
                                        <td>{{ $item->user?->name ?? '-' }}</td>
                                        <td>{{ $item->voidRequester?->name ?? '-' }}</td>
                                        <td>{{ $item->voidApprover?->name ?? '-' }}</td>
                                        <td>{{ $item->void_reason ?? '-' }}</td>
                                        <td class="text-center">{{ optional($item->void_approved_at)->format('d M Y H:i') ?? '-' }} WIB</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Belum ada riwayat void disetujui untuk barang masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $approvedMasuk->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Void Disetujui - Barang Keluar</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th>Barang</th>
                                    <th class="text-center">Jumlah</th>
                                    <th>Penginput</th>
                                    <th>Peminta Void</th>
                                    <th>Approver</th>
                                    <th>Alasan Void</th>
                                    <th class="text-center">Waktu Approve</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($approvedKeluar as $item)
                                    <tr>
                                        <td class="text-center">{{ ($approvedKeluar->currentPage() - 1) * $approvedKeluar->perPage() + $loop->iteration }}</td>
                                        <td>{{ $item->barang?->nama_barang ?? '-' }}</td>
                                        <td class="text-center">{{ $item->jumlah }}</td>
                                        <td>{{ $item->user?->name ?? '-' }}</td>
                                        <td>{{ $item->voidRequester?->name ?? '-' }}</td>
                                        <td>{{ $item->voidApprover?->name ?? '-' }}</td>
                                        <td>{{ $item->void_reason ?? '-' }}</td>
                                        <td class="text-center">{{ optional($item->void_approved_at)->format('d M Y H:i') ?? '-' }} WIB</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Belum ada riwayat void disetujui untuk barang keluar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $approvedKeluar->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
