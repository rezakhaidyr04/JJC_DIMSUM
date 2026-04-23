<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/home', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Read routes for all authenticated users
    Route::resource('barang', BarangController::class)->only(['index']);
    Route::resource('barang-masuk', BarangMasukController::class)->only(['index']);
    Route::resource('barang-keluar', BarangKeluarController::class)->only(['index']);

    // Full access for owner
    Route::middleware('owner')->group(function () {
        Route::resource('barang', BarangController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('barang-masuk', BarangMasukController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('barang-keluar', BarangKeluarController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    });

    // Insert-only access for karyawan
    Route::middleware('karyawan')->group(function () {
        Route::resource('barang', BarangController::class)->only(['create', 'store']);
        Route::resource('barang-masuk', BarangMasukController::class)->only(['create', 'store']);
        Route::resource('barang-keluar', BarangKeluarController::class)->only(['create', 'store']);
    });

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});

require __DIR__.'/auth.php';

