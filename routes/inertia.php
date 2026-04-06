<?php

use App\Http\Controllers\App\BrandController;
use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\App\PembelianController;
use App\Http\Controllers\App\PenjualanController;
use App\Http\Controllers\App\ProdukController;
use App\Http\Controllers\App\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('app')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('app.dashboard');
    
    Route::prefix('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('app.users');
        Route::post('/users', [UserController::class, 'store'])->name('app.users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('app.users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('app.users.destroy');
        
        Route::prefix('master-data')->group(function () {
            Route::get('/produk', [ProdukController::class, 'index'])->name('app.produk');
            Route::post('/produk', [ProdukController::class, 'store'])->name('app.produk.store');
            Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('app.produk.update');
            Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('app.produk.destroy');
            
            Route::get('/brand', [BrandController::class, 'index'])->name('app.brand');
            Route::post('/brand', [BrandController::class, 'store'])->name('app.brand.store');
            Route::put('/brand/{brand}', [BrandController::class, 'update'])->name('app.brand.update');
            Route::delete('/brand/{brand}', [BrandController::class, 'destroy'])->name('app.brand.destroy');
            
            Route::get('/kategori', [BrandController::class, 'index'])->name('app.kategori');
            Route::get('/supplier', [BrandController::class, 'index'])->name('app.supplier');
            Route::get('/member', [BrandController::class, 'index'])->name('app.member');
            Route::get('/jasa', [BrandController::class, 'index'])->name('app.jasa');
            Route::get('/gudang', [BrandController::class, 'index'])->name('app.gudang');
            Route::get('/akun-transaksi', [BrandController::class, 'index'])->name('app.akun-transaksi');
        });
        
        Route::prefix('inventory')->group(function () {
            Route::get('/stock-adjustment', function () { return Inertia::render('app/admin/inventory/stock-adjustment/Index'); })->name('app.stock-adjustment');
            Route::get('/stock-opname', function () { return Inertia::render('app/admin/inventory/stock-opname/Index'); })->name('app.stock-opname');
        });
        
        Route::prefix('transactions')->group(function () {
            // Penjualan
            Route::get('/penjualan', [PenjualanController::class, 'index'])->name('app.penjualan');
            Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('app.penjualan.create');
            Route::post('/penjualan', [PenjualanController::class, 'store'])->name('app.penjualan.store');
            Route::get('/penjualan/{penjualan}', [PenjualanController::class, 'show'])->name('app.penjualan.show');
            Route::get('/penjualan/{penjualan}/edit', [PenjualanController::class, 'edit'])->name('app.penjualan.edit');
            Route::put('/penjualan/{penjualan}', [PenjualanController::class, 'update'])->name('app.penjualan.update');
            Route::delete('/penjualan/{penjualan}', [PenjualanController::class, 'destroy'])->name('app.penjualan.destroy');
            
            // Pembelian
            Route::get('/pembelian', [PembelianController::class, 'index'])->name('app.pembelian');
            Route::get('/pembelian/create', [PembelianController::class, 'create'])->name('app.pembelian.create');
            Route::post('/pembelian', [PembelianController::class, 'store'])->name('app.pembelian.store');
            Route::get('/pembelian/{pembelian}', [PembelianController::class, 'show'])->name('app.pembelian.show');
            Route::get('/pembelian/{pembelian}/edit', [PembelianController::class, 'edit'])->name('app.pembelian.edit');
            Route::put('/pembelian/{pembelian}', [PembelianController::class, 'update'])->name('app.pembelian.update');
            Route::delete('/pembelian/{pembelian}', [PembelianController::class, 'destroy'])->name('app.pembelian.destroy');
        });
    });
    
    Route::prefix('akunting')->group(function () {
        Route::get('/chart-of-accounts', function () { return Inertia::render('app/akunting/chart-of-accounts/Index'); })->name('app.chart-of-accounts');
        Route::get('/input-transaksi', function () { return Inertia::render('app/akunting/input-transaksi/Index'); })->name('app.input-transaksi');
        Route::get('/laporan-laba-rugi', function () { return Inertia::render('app/akunting/laporan-laba-rugi/Index'); })->name('app.laporan-laba-rugi');
        Route::get('/laporan-neraca', function () { return Inertia::render('app/akunting/laporan-neraca/Index'); })->name('app.laporan-neraca');
    });
    
    Route::get('/settings', function () { return Inertia::render('app/settings/Index'); })->name('app.settings');
});

Route::get('/', function () {
    return Inertia::render('app/dashboard');
})->middleware(['auth']);
