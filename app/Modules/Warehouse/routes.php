<?php

use App\Modules\Warehouse\Http\Controllers\GudangController;
use Illuminate\Support\Facades\Route;

// Prefixed /app/modules/warehouse by BaseModuleServiceProvider

Route::get('/gudang', [GudangController::class, 'index'])->name('modules.warehouse.gudang');
Route::post('/gudang', [GudangController::class, 'store'])->name('modules.warehouse.gudang.store');
Route::put('/gudang/{gudang}', [GudangController::class, 'update'])->name('modules.warehouse.gudang.update');
Route::delete('/gudang/{gudang}', [GudangController::class, 'destroy'])->name('modules.warehouse.gudang.destroy');
