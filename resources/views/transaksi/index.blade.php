@extends('layouts.app') 
@section('content')
<div class="container">
    <h1>Daftar Transaksi</h1>
    <a href="{{ route('transaksi.create') }}" class="btn btn mb-4 mb-md-2 btn-primary rounded-pill">Tambah Transaksi</a>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title mb-4">Daftar Transaksi</h5>
                <div class="table-responsive">
                    <table class="table table-hover table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">Kode Transaksi</th>
                                <th scope="col">Tanggal Transaksi</th>
                                <th scope="col">Total Harga</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi as $t)
                            <tr>
                                <td>{{ $t->kode_transaksi }}</td>
                                <td>{{ $t->tanggal_transaksi }}</td>
                                <td>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                                <td>{{ $t->keterangan }}</td>
                                <td class="text-center">
                                    <a href="{{ route('transaksi.show', $t->id) }}" class="btn btn-action btn-edit me-2">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('transaksi.edit', $t->id) }}" class="btn btn-action btn-edit me-2">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('transaksi.destroy', $t->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection