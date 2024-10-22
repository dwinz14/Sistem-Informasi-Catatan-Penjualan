<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Barang;
use Illuminate\Http\Request;
use App\Repositories\LaporanRepositoryInterface;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Mendapatkan input untuk tab yang aktif dan tanggal dari request
        $activeTab = $request->input('activeTab', 'harian');
        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());
        
        // Menghitung total penjualan, jumlah transaksi, dan rata-rata penjualan untuk laporan harian
        $transaksiHarian = Transaksi::whereDate('tanggal_transaksi', $tanggal)->get();
        $totalPenjualanHarian = $transaksiHarian->sum('total_harga');
        $jumlahTransaksiHarian = $transaksiHarian->count();
        $rataRataPenjualanHarian = $jumlahTransaksiHarian > 0 ? $totalPenjualanHarian / $jumlahTransaksiHarian : 0;
        
        // Menghitung total kuantitas untuk laporan harian
        $totalKuantitasHarian = 0;
        foreach ($transaksiHarian as $transaksi) {
            foreach ($transaksi->detailTransaksi as $detail) {
                $totalKuantitasHarian += $detail->kuantitas;
            }
        }
        
        // Mendapatkan input untuk bulan dan tahun untuk laporan bulanan
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahunBulanan = $request->input('tahun', Carbon::now()->year);
        
        // Menghitung total penjualan, jumlah transaksi, dan rata-rata penjualan untuk laporan bulanan
        $transaksiBulanan = Transaksi::whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahunBulanan)
            ->get();
        $totalPenjualanBulanan = $transaksiBulanan->sum('total_harga');
        $jumlahTransaksiBulanan = $transaksiBulanan->count();
        $rataRataPenjualanBulanan = $jumlahTransaksiBulanan > 0 ? $totalPenjualanBulanan / $jumlahTransaksiBulanan : 0;
        
        // Menghitung total kuantitas untuk laporan bulanan
        $totalKuantitasBulanan = 0;
        foreach ($transaksiBulanan as $transaksi) {
            foreach ($transaksi->detailTransaksi as $detail) {
                $totalKuantitasBulanan += $detail->kuantitas;
            }
        }
        
        // Mendapatkan input untuk tahun untuk laporan tahunan
        $tahunTahunan = $request->input('tahun', Carbon::now()->year);
        
        // Menghitung total penjualan, jumlah transaksi, dan rata-rata penjualan untuk laporan tahunan
        $transaksiTahunan = Transaksi::whereYear('tanggal_transaksi', $tahunTahunan)->get();
        $totalPenjualanTahunan = $transaksiTahunan->sum('total_harga');
        $jumlahTransaksiTahunan = $transaksiTahunan->count();
        $rataRataPenjualanTahunan = $jumlahTransaksiTahunan > 0 ? $totalPenjualanTahunan / $jumlahTransaksiTahunan : 0;
        
        // Menghitung total kuantitas untuk laporan tahunan
        $totalKuantitasTahunan = 0;
        foreach ($transaksiTahunan as $transaksi) {
            foreach ($transaksi->detailTransaksi as $detail) {
                $totalKuantitasTahunan += $detail->kuantitas;
            }
        }
        
        // Menghitung penjualan per bulan untuk tahun ini
        $penjualanPerBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $penjualanPerBulan[] = Transaksi::whereMonth('tanggal_transaksi', $i)
                ->whereYear('tanggal_transaksi', date('Y'))
                ->sum('total_harga');
        }
        
        // Menambahkan data detail transaksi untuk laporan harian, bulanan, dan tahunan
        $detailTransaksiHarian = $transaksiHarian->map(function($transaksi) {
            return $transaksi->detailTransaksi->map(function($detail) use ($transaksi) {
                return [
                    'kode_transaksi' => $transaksi->kode_transaksi,
                    'tanggal' => $transaksi->tanggal_transaksi,
                    'total_harga' => $transaksi->total_harga,
                    'kuantitas' => $detail->kuantitas,
                ];
            });
        })->flatten(1); 

        $detailTransaksiBulanan = $transaksiBulanan->map(function($transaksi) {
            return $transaksi->detailTransaksi->map(function($detail) use ($transaksi) {
                return [
                    'kode_transaksi' => $transaksi->kode_transaksi,
                    'tanggal' => $transaksi->tanggal_transaksi,
                    'total_harga' => $transaksi->total_harga,
                    'kuantitas' => $detail->kuantitas,
                ];
            });
        })->flatten(1); 

        $detailTransaksiTahunan = $transaksiTahunan->map(function($transaksi) {
            return $transaksi->detailTransaksi->map(function($detail) use ($transaksi) {
                return [
                    'kode_transaksi' => $transaksi->kode_transaksi,
                    'tanggal' => $transaksi->tanggal_transaksi,
                    'total_harga' => $transaksi->total_harga,
                    'kuantitas' => $detail->kuantitas,
                ];
            });
        })->flatten(1); 

        // Mengembalikan view dengan data yang dihitung
        return view('laporan.index', [
            'activeTab' => $activeTab,
            'transaksiHarian' => $transaksiHarian,
            'tanggalHarian' => $tanggal,
            'totalPenjualanHarian' => $totalPenjualanHarian,
            'jumlahTransaksiHarian' => $jumlahTransaksiHarian,
            'rataRataPenjualanHarian' => $rataRataPenjualanHarian,
            'totalKuantitasHarian' => $totalKuantitasHarian,
            'transaksiBulanan' => $transaksiBulanan,
            'bulanBulanan' => $bulan,
            'tahunBulanan' => $tahunBulanan,
            'totalPenjualanBulanan' => $totalPenjualanBulanan,
            'jumlahTransaksiBulanan' => $jumlahTransaksiBulanan,
            'rataRataPenjualanBulanan' => $rataRataPenjualanBulanan,
            'totalKuantitasBulanan' => $totalKuantitasBulanan,
            'transaksiTahunan' => $transaksiTahunan,
            'tahunTahunan' => $tahunTahunan,
            'totalPenjualanTahunan' => $totalPenjualanTahunan,
            'jumlahTransaksiTahunan' => $jumlahTransaksiTahunan,
            'rataRataPenjualanTahunan' => $rataRataPenjualanTahunan,
            'totalKuantitasTahunan' => $totalKuantitasTahunan,
            'penjualanPerBulan' => $penjualanPerBulan,
            'detailTransaksiHarian' => $detailTransaksiHarian,
            'detailTransaksiBulanan' => $detailTransaksiBulanan,
            'detailTransaksiTahunan' => $detailTransaksiTahunan,
        ]);
    }
}
