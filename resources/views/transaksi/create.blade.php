@extends('layouts.app') 
@section('content')
    <h1>Tambah Transaksi</h1>
    <form action="{{ route('transaksi.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="tanggal_transaksi">Tanggal Transaksi:</label>
            <input type="date" class="form-control" id="tanggal_transaksi" name="tanggal_transaksi" value="{{ date('Y-m-d') }}">
        </div>
        <div class="form-group">
            <label for="keterangan">Keterangan:</label>
            <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
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
                <tr>
                    <td>
                        <select class="form-control" name="detail[0][barang_id]">
                            @foreach ($barang as $b)
                                <option value="{{ $b->id }}">{{ $b->nama }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" class="form-control kuantitas" name="detail[0][kuantitas]" min="1" value="1"></td>
                    <td><input type="number" class="form-control harga-jual" name="detail[0][harga_jual]" min="0" step="0.01"></td>
                    <td><span class="subtotal">0</span></td>
                    <td><button type="button" class="btn btn-danger hapus-baris">Hapus</button></td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-primary" id="tambah-baris">Tambah Baris</button>
        <h3 id="total-harga-label">Total Harga: Rp <span id="total-harga"></span></h3>
        <button type="submit" class="btn btn-success">Simpan Transaksi</button>
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
    // Format subtotal dengan Rupiah
    baris.find('.subtotal').text(formatRupiah(subtotal)); 
    hitungTotal();
});
function hitungTotal() {
    let total = 0;
    $('.subtotal').each(function() {
        total += parseFloat($(this).text().replace(/\D/g, '')); // Hapus karakter non-digit sebelum menghitung
    });
    // Format total dengan Rupiah
    $('#total-harga').text(formatRupiah(total)); 
}
// Fungsi untuk memformat angka ke format Rupiah
function formatRupiah(angka){
    let reverse = angka.toString().split('').reverse().join(''),
    ribuan  = reverse.match(/\d{1,3}/g);
    ribuan  = ribuan.join('.').split('').reverse().join('');
    return ribuan;
}
        });
    </script>
@endsection