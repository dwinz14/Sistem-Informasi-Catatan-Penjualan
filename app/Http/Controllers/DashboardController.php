<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Barang;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTransaksi = Transaksi::count();
        $transaksiHariIni = Transaksi::whereDate('tanggal_transaksi', Carbon::today())->count();
        $totalPendapatan = Transaksi::sum('total_harga');
        $pendapatanHariIni = Transaksi::whereDate('tanggal_transaksi', Carbon::today())->sum('total_harga');
        $totalBarang = Barang::count();
        $transaksiTerbaru = Transaksi::orderBy('tanggal_transaksi', 'desc')->take(5)->get();

        // Data untuk grafik penjualan per bulan
        $salesData = Transaksi::selectRaw('YEAR(tanggal_transaksi) as year, MONTH(tanggal_transaksi) as month, COUNT(*) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'year' => $item->year,
                    'month' => $item->month,
                    'total' => (int) $item->total
                ];
            });

        return view('dashboard', compact(
            'totalTransaksi', 'transaksiHariIni', 'totalPendapatan', 'pendapatanHariIni',
            'totalBarang', 'transaksiTerbaru', 'salesData'
        ));
    }
}
