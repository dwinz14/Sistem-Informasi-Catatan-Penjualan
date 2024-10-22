<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ForecastingController;

// Route::get('/', function () {
//     return view('dashboard');
// });


Auth::routes();

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    // Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    // Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('jenis_barang', JenisBarangController::class);
    Route::resource('barang', BarangController::class);
    Route::resource('transaksi', TransaksiController::class);
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    // Route::get('forecasting', [ForecastingController::class, 'index'])->name('forecasting.index');
    // Route::post('forecasting', [ForecastingController::class, 'forecast'])->name('forecasting.calculate');
    Route::get('/forecasting', [ForecastingController::class, 'index'])->name('forecasting.index');
    Route::post('/forecasting/forecast', [ForecastingController::class, 'forecast'])->name('forecasting.forecast');
    // Tambahkan rute lain yang memerlukan autentikasi di sini
});

