@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="pagetitle">
        <h1>Laporan Transaksi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Laporan transaksi</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Laporan Transaksi</h5>

                        <ul class="nav nav-tabs nav-tabs-bordered" id="laporanTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab == 'harian' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#harian" type="button" role="tab">Harian</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab == 'bulanan' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#bulanan" type="button" role="tab">Bulanan</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab == 'tahunan' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#tahunan" type="button" role="tab">Tahunan</button>
                            </li>
                        </ul>

                        <div class="tab-content pt-2" id="laporanTabsContent">
                            <div class="tab-pane fade {{ $activeTab == 'harian' ? 'show active' : '' }}" id="harian" role="tabpanel">
                                <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 mb-3">
                                    <input type="hidden" name="activeTab" value="harian">
                                    <div class="col-md-4">
                                        <label for="tanggal" class="form-label">Tanggal</label>
                                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $tanggalHarian }}">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                                    </div>
                                </form>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-secondary" onclick="printReport()">Cetak</button>
                                </div>

                                <div class="table-responsive">
                                    <h6 class="mt-4">Laporan Harian - {{ $tanggalHarian }}</h6>
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Periode</th>
                                                <th>Total Penjualan</th>
                                                <th>Jumlah Transaksi</th>
                                                <th>Jumlah Kuantitas</th>
                                                <th>Rata-rata Penjualan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $tanggalHarian }}</td>
                                                <td>Rp {{ number_format($totalPenjualanHarian, 0, ',', '.') }}</td>
                                                <td>{{ $jumlahTransaksiHarian }}</td>
                                                <td>{{ $totalKuantitasHarian }}</td>
                                                <td>Rp {{ number_format($rataRataPenjualanHarian, 0, ',', '.') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <h6 class="mt-4">Detail Transaksi</h6>
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Kode Transaksi</th>
                                                <th>Tanggal</th>
                                                <th>Total Harga</th>
                                                <th>Jumlah Kuantitas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($detailTransaksiHarian as $detail)
                                                <tr>
                                                    <td>{{ $detail['kode_transaksi'] }}</td>
                                                    <td>{{ $detail['tanggal'] }}</td>
                                                    <td>Rp {{ number_format($detail['total_harga'], 0, ',', '.') }}</td>
                                                    <td>{{ $detail['kuantitas'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade {{ $activeTab == 'bulanan' ? 'show active' : '' }}" id="bulanan" role="tabpanel">
                                <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 mb-3">
                                    <input type="hidden" name="activeTab" value="bulanan">
                                    <div class="col-md-3">
                                        <label for="bulan" class="form-label">Bulan</label>
                                        <select name="bulan" id="bulan" class="form-select">
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ $bulanBulanan == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="tahun" class="form-label">Tahun</label>
                                        <input type="number" name="tahun" id="tahun" class="form-control" value="{{ $tahunBulanan }}">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                                    </div>
                                </form>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-secondary" onclick="printReport()">Cetak</button>
                                </div>

                                <div class="table-responsive">
                                    <h6 class="mt-4">Laporan Bulanan - {{ date('F', mktime(0, 0, 0, $bulanBulanan, 1)) }}, {{ $tahunBulanan }}</h6>
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Periode</th>
                                                <th>Total Penjualan</th>
                                                <th>Jumlah Transaksi</th>
                                                <th>Jumlah Kuantitas</th>
                                                <th>Rata-rata Penjualan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ date('F', mktime(0, 0, 0, $bulanBulanan, 1)) }}</td>
                                                <td>Rp {{ number_format($totalPenjualanBulanan, 0, ',', '.') }}</td>
                                                <td>{{ $jumlahTransaksiBulanan }}</td>
                                                <td>{{ $totalKuantitasBulanan }}</td>
                                                <td>Rp {{ number_format($rataRataPenjualanBulanan, 0, ',', '.') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <h6 class="mt-4">Detail Transaksi</h6>
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Kode Transaksi</th>
                                                <th>Tanggal</th>
                                                <th>Total Harga</th>
                                                <th>Jumlah Kuantitas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($detailTransaksiBulanan as $detail)
                                                <tr>
                                                    <td>{{ $detail['kode_transaksi'] }}</td>
                                                    <td>{{ $detail['tanggal'] }}</td>
                                                    <td>Rp {{ number_format($detail['total_harga'], 0, ',', '.') }}</td>
                                                    <td>{{ $detail['kuantitas'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade {{ $activeTab == 'tahunan' ? 'show active' : '' }}" id="tahunan" role="tabpanel">
                                <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 mb-3">
                                    <input type="hidden" name="activeTab" value="tahunan">
                                    <div class="col-md-4">
                                        <label for="tahun" class="form-label">Tahun</label>
                                        <input type="number" name="tahun" id="tahun" class="form-control" value="{{ $tahunTahunan }}">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                                    </div>
                                </form>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-secondary" onclick="printReport()">Cetak</button>
                                </div>

                                <div class="table-responsive">
                                    <h6 class="mt-4">Laporan Tahunan - {{ $tahunTahunan }}</h6>
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Periode</th>
                                                <th>Total Penjualan</th>
                                                <th>Jumlah Transaksi</th>
                                                <th>Jumlah Kuantitas</th>
                                                <th>Rata-rata Penjualan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $tahunTahunan }}</td>
                                                <td>Rp {{ number_format($totalPenjualanTahunan, 0, ',', '.') }}</td>
                                                <td>{{ $jumlahTransaksiTahunan }}</td>
                                                <td>{{ $totalKuantitasTahunan }}</td>
                                                <td>Rp {{ number_format($rataRataPenjualanTahunan, 0, ',', '.') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <h6 class="mt-4">Detail Transaksi</h6>
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Kode Transaksi</th>
                                                <th>Tanggal</th>
                                                <th>Total Harga</th>
                                                <th>Jumlah Kuantitas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($detailTransaksiTahunan as $detail)
                                                <tr>
                                                    <td>{{ $detail['kode_transaksi'] }}</td>
                                                    <td>{{ $detail['tanggal'] }}</td>
                                                    <td>Rp {{ number_format($detail['total_harga'], 0, ',', '.') }}</td>
                                                    <td>{{ $detail['kuantitas'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var triggerTabList = [].slice.call(document.querySelectorAll('#laporanTabs button'))
        triggerTabList.forEach(function (triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl)
            triggerEl.addEventListener('click', function (event) {
                event.preventDefault()
                tabTrigger.show()
            })
        })
    });

    function printReport() {
        // Ambil konten tabel dari tab aktif
        var printContents = document.querySelector('.tab-pane.show.active .table-responsive').innerHTML; 
        var originalContents = document.body.innerHTML; // Simpan konten asli

        // Ganti konten dengan yang ingin dicetak
        document.body.innerHTML = printContents; 
        window.print(); // Buka dialog cetak
        document.body.innerHTML = originalContents; // Kembalikan konten asli
    }
</script>
@endpush