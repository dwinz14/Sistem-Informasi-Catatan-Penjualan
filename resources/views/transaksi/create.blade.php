@extends('layouts.app') 
@section('content')
<div class="container mt-5 shadow-sm">
    <div class="card mb-5">
        <div class="card-header">
            <h1 class="mb-0"><i class="bi bi-cart-plus-fill me-2"></i>Tambah Transaksi</h1>
        </div>
        <div class="card-body shadow">
            <form action="{{ route('transaksi.store') }}" method="POST">
                @csrf
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="tanggal_transaksi" class="form-label">Tanggal Transaksi:</label>
                        <input type="date" class="form-control" id="tanggal_transaksi" name="tanggal_transaksi" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label">Keterangan:</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="1"></textarea>
                    </div>
                </div>
                
                <h2 class="mb-3"><i class="bi bi-list-ul me-2"></i>Detail Transaksi</h2>
                <div class="table-responsive">
                    <table class="table table-striped" id="detail-table">
                        <thead>
                            <tr>
                                <th style="width: 30%">Barang</th>
                                <th style="width: 10%">Kuantitas</th>
                                <th style="width: 25%">Harga Jual Satuan (Rp)</th>
                                <th style="width: 25%">Subtotal (Rp)</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select class="form-select" name="detail[0][barang_id]">
                                        @foreach ($barang as $b)
                                            <option value="{{ $b->id }}">{{ $b->nama }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" class="form-control kuantitas" name="detail[0][kuantitas]" min="1" value="1"></td>
                                <td><input type="number" class="form-control harga-jual" name="detail[0][harga_jual]" min="0" step="0.01"></td>
                                <td><span class="subtotal">0</span></td>
                                <td><button type="button" class="btn btn-danger hapus-baris"><i class="bi bi-trash"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary mt-2 mb-4" id="tambah-baris">
                        <i class="bi bi-plus-circle me-2"></i>Tambah
                    </button>
                </div>
                <h3 id="total-harga-label" class="text-left">
                    Total Harga: Rp <span id="total-harga">0</span>
                </h3>
                <div class="d-flex justify-content-start gap-2 mt-4">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary me-2"><i class="bi bi-x-circle me-1"></i>Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let barisCount = 1;
        $('#tambah-baris').click(function() {
            barisCount++;
            let newRow = `
                <tr>
                    <td>
                        <select class="form-select" name="detail[${barisCount}][barang_id]">
                            @foreach ($barang as $b)
                                <option value="{{ $b->id }}">{{ $b->nama }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" class="form-control kuantitas" name="detail[${barisCount}][kuantitas]" min="1" value="1"></td>
                    <td><input type="number" class="form-control harga-jual" name="detail[${barisCount}][harga_jual]" min="0" step="0.01"></td>
                    <td><span class="subtotal">0</span></td>
                    <td><button type="button" class="btn btn-danger hapus-baris"><i class="bi bi-trash"></i></button></td>
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
            baris.find('.subtotal').text(formatRupiah(subtotal)); 
            hitungTotal();
        });
        function hitungTotal() {
            let total = 0;
            $('.subtotal').each(function() {
                total += parseFloat($(this).text().replace(/\D/g, '')); 
            });
            $('#total-harga').text(formatRupiah(total)); 
        }
        function formatRupiah(angka){
            let reverse = angka.toString().split('').reverse().join(''),
            ribuan  = reverse.match(/\d{1,3}/g);
            ribuan  = ribuan.join('.').split('').reverse().join('');
            return ribuan;
        }
    });
</script>
@endsection