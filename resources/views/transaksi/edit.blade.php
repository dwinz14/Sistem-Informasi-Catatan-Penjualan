@extends('layouts.app') 
@section('content')
    <h1>Edit Transaksi</h1>
    <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="tanggal_transaksi">Tanggal Transaksi:</label>
            <input type="date" class="form-control" id="tanggal_transaksi" name="tanggal_transaksi" value="{{ $transaksi->tanggal_transaksi }}">
        </div>
        <div class="form-group">
            <label for="keterangan">Keterangan:</label>
            <textarea class="form-control" id="keterangan" name="keterangan">{{ $transaksi->keterangan }}</textarea>
        </div>
        <h2>Detail Transaksi</h2>
        <table class="table" id="detail-table">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Kuantitas</th>
                    <th>Harga Jual (Rp)</th>
                    <th>Subtotal (Rp)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi->detailTransaksi as $index => $detail)
                <tr>
                    <td>
                        <select class="form-control" name="detail[{{ $index }}][barang_id]">
                            @foreach ($barang as $b)
                                <option value="{{ $b->id }}" {{ $b->id == $detail->barang_id ? 'selected' : '' }}>{{ $b->nama }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" class="form-control kuantitas" name="detail[{{ $index }}][kuantitas]" min="1" value="{{ $detail->kuantitas }}"></td>
                    <td><input type="number" class="form-control harga-jual" name="detail[{{ $index }}][harga_jual]" min="0" step="0.01" value="{{ $detail->harga_jual }}"></td>
                    <td><span class="subtotal">{{ number_format($detail->kuantitas * $detail->harga_jual, 2) }}</span></td>
                    <td><button type="button" class="btn btn-danger hapus-baris">Hapus</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button type="button" class="btn btn-primary" id="tambah-baris">Tambah Baris</button>
        <h3 id="total-harga-label">Total Harga: Rp <span id="total-harga">{{ number_format($transaksi->total_harga, 2) }}</span></h3>
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </form>
    <script>
        $(document).ready(function() {
            let barisCount = 1;
            $('#tambah-baris').click(function() {
                barisCount++;
                let newRow = `
                    <tr>
                        <td>
                            <select class="form-control" name="detail[${barisCount}][barang_id]">
                                @foreach ($barang as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" class="form-control kuantitas" name="detail[${barisCount}][kuantitas]" min="1" value="1"></td>
                        <td><input type="number" class="form-control harga-jual" name="detail[${barisCount}][harga_jual]" min="0" step="0.01"></td>
                        <td><span class="subtotal">0</span></td>
                        <td><button type="button" class="btn btn-danger hapus-baris">Hapus</button></td>
                    </tr>
                `;
                $('#detail-table tbody').append(newRow);
            });
            $(document).on('click', '.hapus-baris', function() {
                $(this).closest('tr').remove();
                hitungTotal();
            });
            $(document).on('input', '.kuantitas, .harga-jual', function() {
                let baris = $(this).closest('tr');
                let kuantitas = parseInt(baris.find('.kuantitas').val());
                let hargaJual = parseFloat(baris.find('.harga-jual').val());
                let subtotal = kuantitas * hargaJual;
                baris.find('.subtotal').text(subtotal.toFixed(2));
                hitungTotal();
            });
            function hitungTotal() {
                let total = 0;
                $('.subtotal').each(function() {
                    total += parseFloat($(this).text());
                });
                $('#total-harga').text(total.toFixed(2));
            }
        });
    </script>
@endsection