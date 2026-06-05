@extends('layouts.app')

@section('title', 'Approval Hapus Barang')
@section('page-title', 'Approval Hapus Barang')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pengajuan Hapus Barang</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                Pengajuan dari karyawan akan muncul di sini. Owner yang memutuskan approve lalu barang dihapus, atau reject bila belum sesuai.
            </div>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Barang</th>
                        <th>Pengaju</th>
                        <th>Alasan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $r)
                        <tr>
                            <td>{{ $r->id }}</td>
                            <td>
                                <div class="fw-semibold">
                                    {{ $r->barang_nama ?? ($r->barang ? $r->barang->nama_barang : '—') }}
                                </div>
                                <div class="text-muted small">
                                    {{ $r->barang_kode ?? ($r->barang ? $r->barang->kode_barang : '—') }}
                                </div>
                            </td>
                            <td>{{ $r->user ? $r->user->name : '—' }}</td>
                            <td>{{ $r->reason }}</td>
                            <td>{{ $r->created_at->format('d M Y H:i') }}</td>
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
                                    <form method="POST" action="{{ route('barang-delete-requests.reject', $r->id) }}" style="display:inline-block">@csrf<button class="btn btn-sm btn-danger">Reject</button></form>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
