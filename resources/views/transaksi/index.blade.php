@extends('layouts.app') 
@section('content')
    <h1>Daftar Transaksi</h1>
    <a href="{{ route('transaksi.create') }}" class="btn btn-primary">Tambah Transaksi</a>
    <table class="table">
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Tanggal Transaksi</th>
                <th>Total Harga</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi as $t)
            <tr>
                <td>{{ $t->kode_transaksi }}</td>
                <td>{{ $t->tanggal_transaksi }}</td>
                <td>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                <td>{{ $t->keterangan }}</td>
                <td>
                    <a href="{{ route('transaksi.show', $t->id) }}" class="btn btn-info">Detail</a>
                    <a href="{{ route('transaksi.edit', $t->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('transaksi.destroy', $t->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection