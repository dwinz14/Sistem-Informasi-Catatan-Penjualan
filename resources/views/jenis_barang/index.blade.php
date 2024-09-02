@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Jenis Barang</h1>
    <a href="{{ route('jenis_barang.create') }}" class="btn btn-primary mb-3">Tambah Jenis Barang</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jenisBarang as $jb)
            <tr>
                <td>{{ $jb->id }}</td>
                <td>{{ $jb->nama }}</td>
                <td>{{ $jb->deskripsi }}</td>
                <td>
                    <a href="{{ route('jenis_barang.edit', $jb->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('jenis_barang.destroy', $jb->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection