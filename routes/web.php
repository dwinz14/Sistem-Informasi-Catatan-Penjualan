<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;

Route::get('/', function () {
    return view('dashboard');
});

Route::resource('jenis_barang', JenisBarangController::class);
Route::resource('barang', BarangController::class);
Route::resource('transaksi', TransaksiController::class);