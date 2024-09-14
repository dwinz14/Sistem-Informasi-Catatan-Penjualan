@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Jenis Barang</h1>
    <a href="{{ route('jenis_barang.create') }}" class="btn btn mb-4 mb-md-2 btn-primary rounded-pill">Tambah Barang</a>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Daftar Jenis Barang</h5>
                <div class="table-responsive">
                    <table class="table table-hover table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">Nama</th>
                                <th scope="col">Deskripsi</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jenisBarang as $jb)
                            <tr>
                                <td>{{ $jb->nama }}</td>
                                <td>{{ $jb->deskripsi }}</td>
                                <td class="text-center">
                                    <a href="{{ route('jenis_barang.edit', $jb->id) }}" class="btn btn-action btn-edit me-2">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('jenis_barang.destroy', $jb->id) }}" method="POST" class="d-inline">
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