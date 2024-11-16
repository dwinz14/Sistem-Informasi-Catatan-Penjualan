@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="bi bi-plus-circle-fill me-4"></i>Tambah Barang Baru</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('barang.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="nama" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Masukkan nama barang">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="4" placeholder="Masukkan keterangan tambahan">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-start">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary me-2"><i class="bi bi-x-circle me-1"></i>Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection