@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Barang</h1>
    <form action="{{ route('barang.update', $barang->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nama">Nama Barang</label>
            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $barang->nama) }}" required>
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="jenis_barang_id">Jenis Barang</label>
            <select class="form-control @error('jenis_barang_id') is-invalid @enderror" id="jenis_barang_id" name="jenis_barang_id" required>
                <option value="">Pilih Jenis Barang</option>
                @foreach($jenisBarang as $jenis)
                    <option value="{{ $jenis->id }}" {{ old('jenis_barang_id', $barang->jenis_barang_id) == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama }}</option>
                @endforeach
            </select>
            @error('jenis_barang_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="stock_awal">Stock Awal</label>
            <input type="number" class="form-control @error('stock_awal') is-invalid @enderror" id="stock_awal" name="stock_awal" value="{{ old('stock_awal', $barang->stock_awal) }}">
            @error('stock_awal')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan">{{ old('keterangan', $barang->keterangan) }}</textarea>
            @error('keterangan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection