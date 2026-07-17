<?php

use App\Modules\Inventory\Http\Controllers\StockAdjustmentController;
use App\Modules\Inventory\Http\Controllers\StockOpnameController;
use App\Modules\Inventory\Http\Controllers\LowStockWidgetController;
use Illuminate\Support\Facades\Route;

// Inventory module routes
// Prefixed /app/modules/inventory by BaseModuleServiceProvider

// Stock Adjustment
Route::get('/stock-adjustment/create', [StockAdjustmentController::class, 'create'])->name('modules.inventory.stock-adjustment.create');
Route::post('/stock-adjustment', [StockAdjustmentController::class, 'store'])->name('modules.inventory.stock-adjustment.store');
Route::get('/stock-adjustment/{stockAdjustment}/edit', [StockAdjustmentController::class, 'edit'])->name('modules.inventory.stock-adjustment.edit');
Route::put('/stock-adjustment/{stockAdjustment}', [StockAdjustmentController::class, 'update'])->name('modules.inventory.stock-adjustment.update');
Route::delete('/stock-adjustment/{stockAdjustment}', [StockAdjustmentController::class, 'destroy'])->name('modules.inventory.stock-adjustment.destroy');
Route::post('/stock-adjustment/{stockAdjustment}/post', [StockAdjustmentController::class, 'post'])->name('modules.inventory.stock-adjustment.post');
Route::get('/stock-adjustment/{stockAdjustment}', [StockAdjustmentController::class, 'show'])->name('modules.inventory.stock-adjustment.show');
Route::get('/stock-adjustment', [StockAdjustmentController::class, 'index'])->name('modules.inventory.stock-adjustment');

// Stock Opname
Route::get('/stock-opname/create', [StockOpnameController::class, 'create'])->name('modules.inventory.stock-opname.create');
Route::post('/stock-opname', [StockOpnameController::class, 'store'])->name('modules.inventory.stock-opname.store');
Route::get('/stock-opname/{stockOpname}/edit', [StockOpnameController::class, 'edit'])->name('modules.inventory.stock-opname.edit');
Route::put('/stock-opname/{stockOpname}', [StockOpnameController::class, 'update'])->name('modules.inventory.stock-opname.update');
Route::delete('/stock-opname/{stockOpname}', [StockOpnameController::class, 'destroy'])->name('modules.inventory.stock-opname.destroy');
Route::post('/stock-opname/{stockOpname}/post', [StockOpnameController::class, 'post'])->name('modules.inventory.stock-opname.post');
Route::get('/stock-opname/{stockOpname}', [StockOpnameController::class, 'show'])->name('modules.inventory.stock-opname.show');
Route::get('/stock-opname', [StockOpnameController::class, 'index'])->name('modules.inventory.stock-opname');

// Dashboard widget API — fetched independently by LowStockWidget.vue
Route::get('/widgets/low-stock', LowStockWidgetController::class)->name('modules.inventory.widgets.low-stock');

