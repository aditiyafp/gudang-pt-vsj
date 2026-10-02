<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Barang
    Route::resource('barang', BarangController::class);

    // Kategori & Satuan
    Route::resource('kategori', KategoriController::class)->except(['create', 'edit', 'show']);
    Route::resource('satuan', SatuanController::class)->except(['create', 'edit', 'show']);

    // Supplier
    Route::resource('supplier', SupplierController::class)->except(['create', 'edit', 'show']);

    // Transaksi Barang Masuk
    Route::prefix('transaksi/masuk')->name('transaksi.masuk.')->group(function () {
        Route::get('/', [BarangMasukController::class, 'index'])->name('index');
        Route::get('/create', [BarangMasukController::class, 'create'])->name('create');
        Route::post('/', [BarangMasukController::class, 'store'])->name('store');
        Route::get('/{barangMasuk}', [BarangMasukController::class, 'show'])->name('show');
        Route::delete('/{barangMasuk}', [BarangMasukController::class, 'destroy'])->name('destroy');
    });

    // Transaksi Barang Keluar (Surat Jalan)
    Route::prefix('transaksi/keluar')->name('transaksi.keluar.')->group(function () {
        Route::get('/', [BarangKeluarController::class, 'index'])->name('index');
        Route::get('/create', [BarangKeluarController::class, 'create'])->name('create');
        Route::post('/', [BarangKeluarController::class, 'store'])->name('store');
        Route::get('/{barangKeluar}', [BarangKeluarController::class, 'show'])->name('show');
        Route::get('/{barangKeluar}/print', [BarangKeluarController::class, 'print'])->name('print');
        Route::delete('/{barangKeluar}', [BarangKeluarController::class, 'destroy'])->name('destroy');
    });

    // Stok
    Route::prefix('stok')->name('stok.')->group(function () {
        Route::get('/tersedia', [StokController::class, 'tersedia'])->name('tersedia');
        Route::get('/opname', [StokController::class, 'opnameIndex'])->name('opname');
        Route::post('/opname', [StokController::class, 'opnameStore'])->name('opname.store');
    });

    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/transaksi', [LaporanController::class, 'transaksi'])->name('transaksi');
        Route::get('/stok', [LaporanController::class, 'stok'])->name('stok');
        Route::post('/stok/tutup', [LaporanController::class, 'closeMonthlyReport'])->name('stok.tutup');
        Route::post('/stok/buka', [LaporanController::class, 'reopenMonthlyReport'])->name('stok.buka');
    });

    // Pengguna
    Route::resource('pengguna', PenggunaController::class)->except(['create', 'edit', 'show']);

    // Backup Database
    Route::get('/backup', [\App\Http\Controllers\BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup', [\App\Http\Controllers\BackupController::class, 'create'])->name('backup.create');
    Route::get('/backup/download/{filename}', [\App\Http\Controllers\BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/{filename}', [\App\Http\Controllers\BackupController::class, 'destroy'])->name('backup.destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
