<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Akunting module routes
// Prefixed /app/modules/akunting by BaseModuleServiceProvider

Route::get('/chart-of-accounts', function () {
    return Inertia::render('modules/akunting/chart-of-accounts/Index');
})->name('modules.akunting.chart-of-accounts');

Route::get('/input-transaksi', function () {
    return Inertia::render('modules/akunting/input-transaksi/Index');
})->name('modules.akunting.input-transaksi');

Route::get('/laporan-laba-rugi', function () {
    return Inertia::render('modules/akunting/laporan-laba-rugi/Index');
})->name('modules.akunting.laporan-laba-rugi');

Route::get('/laporan-neraca', function () {
    return Inertia::render('modules/akunting/laporan-neraca/Index');
})->name('modules.akunting.laporan-neraca');
