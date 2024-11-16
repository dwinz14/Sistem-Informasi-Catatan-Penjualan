@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <div class="card mb-5">
        <div class="card-header">
            <h1 class="mb-0"><i class="bi bi-cart-plus-fill me-2"></i>Edit Transaksi</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="tanggal_transaksi">Tanggal Transaksi:</label>
                        <input type="date" class="form-control" id="tanggal_transaksi" name="tanggal_transaksi"
                            value="{{ $transaksi->tanggal_transaksi }}">
                    </div>
                    <div class="col-md-6">
                        <label for="keterangan">Keterangan:</label>
                        <textarea class="form-control" id="keterangan"
                            name="keterangan">{{ $transaksi->keterangan }}</textarea>
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
                            @foreach ($transaksi->detailTransaksi as $index => $detail)
                            <tr>
                                <td>
                                    <select class="form-control" name="detail[{{ $index }}][barang_id]">
                                        @foreach ($barang as $b)
                                        <option value="{{ $b->id }}" {{ $b->id == $detail->barang_id ? 'selected' : ''
                                            }}>{{ $b->nama }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" class="form-control kuantitas"
                                        name="detail[{{ $index }}][kuantitas]" min="1" value="{{ $detail->kuantitas }}">
                                </td>
                                <td><input type="number" class="form-control harga-jual"
                                        name="detail[{{ $index }}][harga_jual]" min="0" step="0.01"
                                        value="{{ number_format($detail->harga_jual, 0, '', '') }}"></td>
                                <td><span class="subtotal">{{ number_format($detail->kuantitas * $detail->harga_jual, 0,
                                        ',', '.') }}</span></td>
                                <td><button type="button" class="btn btn-danger hapus-baris">Hapus</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-primary mt-2 mb-4" id="tambah-baris">
                    <i class="bi bi-plus-circle me-2"></i>Tambah
                </button>
                <h3 id="total-harga-label" class="text-left">
                    Total Harga: Rp <span id="total-harga">{{ number_format($transaksi->total_harga, 0, ',', '.')
                        }}</span>
                </h3>
                <div class="d-flex justify-content-start gap-2 mt-4">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary me-2"><i
                            class="bi bi-x-circle me-1"></i>Batal</a>
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
    return new Intl.NumberFormat('id-ID').format(angka);
            }
        });
</script>
@endsection