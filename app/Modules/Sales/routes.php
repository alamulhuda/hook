<?php

use App\Modules\Sales\Http\Controllers\RecentSalesWidgetController;
use Illuminate\Support\Facades\Route;

// Sales module routes
// Prefixed /app/modules/sales by BaseModuleServiceProvider

// Dashboard widget API — fetched independently by RecentSalesWidget.vue
Route::get('/widgets/recent-sales', RecentSalesWidgetController::class)->name('modules.sales.widgets.recent-sales');
