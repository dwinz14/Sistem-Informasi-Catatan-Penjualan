@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="pagetitle">
        <h1>Detail Transaksi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transaksi</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Transaksi</h5>
                        <div class="row">
                            <div class="col-lg-6 col-md-4 label">Kode Transaksi</div>
                            <div class="col-lg-6 col-md-8">{{ $transaksi->kode_transaksi }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-4 label">Tanggal Transaksi</div>
                            <div class="col-lg-6 col-md-8">{{ $transaksi->tanggal_transaksi }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-4 label">Total Harga</div>
                            <div class="col-lg-6 col-md-8">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-4 label">Keterangan</div>
                            <div class="col-lg-6 col-md-8">{{ $transaksi->keterangan ?: 'Tidak ada keterangan' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ringkasan Transaksi</h5>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold">Total Item:</span>
                            <span class="badge bg-primary rounded-pill">{{ $transaksi->detailTransaksi->sum('kuantitas') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Total Harga:</span>
                            <span class="text-success fw-bold">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Detail Barang</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Kuantitas</th>
                                        <th>Harga Jual (Rp)</th>
                                        <th>Subtotal (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaksi->detailTransaksi as $detail)
                                    <tr>
                                        <td>{{ $detail->barang->nama }}</td>
                                        <td>{{ $detail->kuantitas }}</td>
                                        <td>Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($detail->kuantitas * $detail->harga_jual, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-primary">
                                        <td colspan="3" class="text-end fw-bold">Total:</td>
                                        <td class="fw-bold">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <a href="{{ route('transaksi.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Edit Transaksi</a>
            </div>
        </div>
    </section>
</div>
@endsection