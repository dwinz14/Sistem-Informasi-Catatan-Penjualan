@extends('layouts.app') 
@section('content')
    <h1>Detail Transaksi</h1>
    <p>Kode Transaksi: {{ $transaksi->kode_transaksi }}</p>
    <p>Tanggal Transaksi: {{ $transaksi->tanggal_transaksi }}</p>
    <p>Total Harga: Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</p>
    <p>Keterangan: {{ $transaksi->keterangan }}</p>
    <h2>Detail Barang</h2>
    <table class="table">
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
    </table>
@endsection