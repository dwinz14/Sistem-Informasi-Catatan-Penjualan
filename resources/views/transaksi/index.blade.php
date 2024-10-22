@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="pagetitle">
        <h1>Daftar Transaksi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Transaksi</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Transaksi</h5>
                            <a href="{{ route('transaksi.create') }}" class="btn btn-primary btn-sm rounded-pill">
                                <i class="bi bi-plus-circle"></i> Tambah Transaksi
                            </a>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="GET" action="{{ route('transaksi.index') }}" class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari transaksi..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <a href="{{ route('transaksi.index', ['sort_field' => 'kode_transaksi', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                                Kode Transaksi
                                                <i class="bi {{ request('sort_field') == 'kode_transaksi' ? (request('sort_order') == 'asc' ? 'bi-sort-alpha-down' : 'bi-sort-alpha-up') : 'bi-sort-alpha-down' }}"></i>
                                            </a>
                                        </th>
                                        <th scope="col">
                                            <a href="{{ route('transaksi.index', ['sort_field' => 'tanggal_transaksi', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                                Tanggal Transaksi
                                                <i class="bi {{ request('sort_field') == 'tanggal_transaksi' ? (request('sort_order') == 'asc' ? 'bi-sort-numeric-down' : 'bi-sort-numeric-up') : 'bi-sort-numeric-down' }}"></i>
                                            </a>
                                        </th>
                                        <th scope="col">
                                            <a href="{{ route('transaksi.index', ['sort_field' => 'total_harga', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                                Total Harga
                                                <i class="bi {{ request('sort_field') == 'total_harga' ? (request('sort_order') == 'asc' ? 'bi-sort-numeric-down' : 'bi-sort-numeric-up') : 'bi-sort-numeric-down' }}"></i>
                                            </a>
                                        </th>
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
                                        <td>{{ Str::limit($t->keterangan, 30) }}</td>
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

                        <div class="d-flex justify-content-end mt-3">
                            {{ $transaksi->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection