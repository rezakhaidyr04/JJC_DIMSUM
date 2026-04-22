@extends('layouts.app')

@section('title', 'Data Barang')
@section('page-title', 'Data Barang')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Barang</h3>
                    @if(Auth::user()->isKaryawan())
                        <div class="card-tools">
                            <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Barang
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Nama Barang</th>
                                <th style="width: 15%">Stok</th>
                                @if(Auth::user()->isKaryawan())
                                    <th style="width: 20%">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if($barang->count() > 0)
                                @foreach($barang as $item)
                                    <tr>
                                        <td>{{ ($barang->currentPage() - 1) * $barang->perPage() + $loop->iteration }}</td>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $item->stok }}</span>
                                        </td>
                                        @if(Auth::user()->isKaryawan())
                                            <td>
                                                <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-warning btn-xs">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form method="POST" action="{{ route('barang.destroy', $item->id) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Yakin ingin menghapus?')">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="{{ Auth::user()->isKaryawan() ? 4 : 3 }}" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $barang->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
