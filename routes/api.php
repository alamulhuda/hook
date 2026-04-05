<?php

use App\Http\Controllers\Api\AkunTransaksiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\JasaController;
use App\Http\Controllers\Api\KaryawanController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\PenjualanController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\StokController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);

    // Master Data Endpoints
    Route::apiResource('produk', ProdukController::class)->only(['index', 'show']);
    Route::apiResource('member', MemberController::class)->only(['index', 'show']);
    Route::apiResource('karyawan', KaryawanController::class)->only(['index', 'show']);
    Route::get('kategori', [KategoriController::class, 'index']);
    Route::get('brand', [BrandController::class, 'index']);
    Route::apiResource('jasa', JasaController::class)->only(['index', 'show']);
    Route::get('akun-transaksi', [AkunTransaksiController::class, 'index']);
    Route::get('profile', [ProfileController::class, 'index']);
    Route::get('produk/{produk}/stok', [StokController::class, 'show']);

    // Transaction Endpoints
    Route::apiResource('penjualan', PenjualanController::class)->only(['index', 'show', 'store']);
});
