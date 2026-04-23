@extends('layouts.app')

@section('title', 'Barang Keluar')
@section('page-title', 'Barang Keluar')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Barang Keluar</h3>
                    @if(Auth::user()->isKaryawan())
                        <div class="card-tools">
                            <a href="{{ route('barang-keluar.create') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Barang</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                @if(Auth::user()->isKaryawan())
                                    <th style="width: 20%">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if($barangKeluar->count() > 0)
                                @foreach($barangKeluar as $item)
                                    <tr>
                                        <td>{{ ($barangKeluar->currentPage() - 1) * $barangKeluar->perPage() + $loop->iteration }}</td>
                                        <td>{{ $item->barang->nama_barang }}</td>
                                        <td>
                                            <span class="badge bg-danger">{{ $item->jumlah }}</span>
                                        </td>
                                        <td>{{ $item->tanggal->format('d-m-Y') }}</td>
                                        @if(Auth::user()->isKaryawan())
                                            <td>
                                                <div class="actions-inline">
                                                    <a href="{{ route('barang-keluar.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <form method="POST" action="{{ route('barang-keluar.destroy', $item->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="{{ Auth::user()->isKaryawan() ? 5 : 4 }}" class="text-center text-muted">Tidak ada data</td>
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
