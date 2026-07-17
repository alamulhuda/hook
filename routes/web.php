<?php

use App\Http\Controllers\Mcp\McpSseController;
use App\Filament\Pages\AppDashboard;
use App\Http\Controllers\App\AkunTransaksiController;
use App\Http\Controllers\App\BrandController;
use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\App\GudangController;
use App\Http\Controllers\App\JasaController;
use App\Http\Controllers\App\MemberController;
use App\Http\Controllers\App\ProdukController;
use App\Http\Controllers\App\InventoryProductController;
use App\Http\Controllers\App\SupplierController;
use App\Http\Controllers\App\UserController;
use App\Http\Controllers\App\RoleController;
use App\Http\Controllers\App\PermissionController;
use App\Http\Controllers\App\ModuleManagementController;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Mcp\Facades\Mcp;
use App\Mcp\Servers\ArabicaServer;

// MCP SSE Endpoint untuk Opencode - harus didaftarkan SEBELUM Mcp::web()
Route::get('/mcp/arabica', McpSseController::class)->name('mcp.arabica.sse');

// Register MCP endpoints
Mcp::web('/mcp/arabica-json', ArabicaServer::class);
Mcp::local('arabica-server', ArabicaServer::class);

Route::get('/test-auth', function () {
    $user = Auth::user();

    return response()->json([
        'is_logged_in' => Auth::check(),
        'user_id' => $user->id ?? null,
        'user_name' => $user->name ?? null,
        'roles' => $user ? $user->getRoleNames() : [],
        'can_access_panel' => $user ? $user->canAccessPanel(Filament::getPanel('admin')) : false,
    ]);
});

// Login route for auth redirects
Route::get('/login', fn() => redirect()->route('filament.admin.auth.login'))->name('login');

// TEMP TEST ROUTE - REMOVE LATER
Route::get('/test-inertia', function () {
    return Inertia::render('core/dashboard', ['test' => 'hello from inertia']);
});

Route::get('/test-minimal', function () {
    return Inertia::render('core/minimal-test', ['test' => 'minimal test']);
});

Route::get('/', function () {
    if (! Auth::check()) {
        return redirect()->route('filament.admin.auth.login');
    }

    // Redirect to Inertia app instead of Filament
    return redirect()->route('app.dashboard');
})->name('home');

// Inertia App Routes
Route::prefix('app')->middleware(['auth'])->group(function () {
    // NOTE: /app/login is handled by inertia.php (no middleware)
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('app.dashboard');
    
    Route::prefix('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('app.users');
        Route::post('/users', [UserController::class, 'store'])->name('app.users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('app.users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('app.users.destroy');

        Route::get('/roles', [RoleController::class, 'index'])->name('app.roles');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('app.roles.edit');
        Route::post('/roles', [RoleController::class, 'store'])->name('app.roles.store');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('app.roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('app.roles.destroy');

        Route::get('/permissions', [PermissionController::class, 'index'])->name('app.permissions');
        Route::post('/permissions', [PermissionController::class, 'store'])->name('app.permissions.store');
        Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('app.permissions.update');
        Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('app.permissions.destroy');
        
        Route::prefix('master-data')->group(function () {
            Route::get('/product-data/search', [ProdukController::class, 'search'])->name('app.product-data.search');
            Route::get('/product-data/{produk}', [ProdukController::class, 'show'])->name('app.product-data.show');
            Route::get('/product-data', [ProdukController::class, 'index'])->name('app.product-data');
            Route::post('/product-data', [ProdukController::class, 'store'])->name('app.product-data.store');
            Route::match(['put', 'post'], '/product-data/{produk}', [ProdukController::class, 'update'])->name('app.product-data.update');
            Route::delete('/product-data/{produk}', [ProdukController::class, 'destroy'])->name('app.product-data.destroy');
            
            Route::get('/brand', [BrandController::class, 'index'])->name('app.brand');
            Route::post('/brand', [BrandController::class, 'store'])->name('app.brand.store');
            Route::put('/brand/{brand}', [BrandController::class, 'update'])->name('app.brand.update');
            Route::delete('/brand/{brand}', [BrandController::class, 'destroy'])->name('app.brand.destroy');
            
            Route::get('/kategori', [App\Http\Controllers\App\KategoriController::class, 'index'])->name('app.kategori');
            Route::post('/kategori', [App\Http\Controllers\App\KategoriController::class, 'store'])->name('app.kategori.store');
            Route::put('/kategori/{kategori}', [App\Http\Controllers\App\KategoriController::class, 'update'])->name('app.kategori.update');
            Route::delete('/kategori/{kategori}', [App\Http\Controllers\App\KategoriController::class, 'destroy'])->name('app.kategori.destroy');
            
            Route::get('/supplier', [SupplierController::class, 'index'])->name('app.supplier');
            Route::post('/supplier', [SupplierController::class, 'store'])->name('app.supplier.store');
            Route::put('/supplier/{supplier}', [SupplierController::class, 'update'])->name('app.supplier.update');
            Route::delete('/supplier/{supplier}', [SupplierController::class, 'destroy'])->name('app.supplier.destroy');
            
            Route::get('/member', [MemberController::class, 'index'])->name('app.member');
            Route::post('/member', [MemberController::class, 'store'])->name('app.member.store');
            Route::put('/member/{member}', [MemberController::class, 'update'])->name('app.member.update');
            Route::delete('/member/{member}', [MemberController::class, 'destroy'])->name('app.member.destroy');
            
            Route::get('/jasa', [JasaController::class, 'index'])->name('app.jasa');
            Route::post('/jasa', [JasaController::class, 'store'])->name('app.jasa.store');
            Route::put('/jasa/{jasa}', [JasaController::class, 'update'])->name('app.jasa.update');
            Route::delete('/jasa/{jasa}', [JasaController::class, 'destroy'])->name('app.jasa.destroy');
            
            Route::get('/gudang', [GudangController::class, 'index'])->name('app.gudang');
            Route::post('/gudang', [GudangController::class, 'store'])->name('app.gudang.store');
            Route::put('/gudang/{gudang}', [GudangController::class, 'update'])->name('app.gudang.update');
            Route::delete('/gudang/{gudang}', [GudangController::class, 'destroy'])->name('app.gudang.destroy');
            
            Route::get('/akun-transaksi', [AkunTransaksiController::class, 'index'])->name('app.akun-transaksi');
            Route::post('/akun-transaksi', [AkunTransaksiController::class, 'store'])->name('app.akun-transaksi.store');
            Route::put('/akun-transaksi/{akunTransaksi}', [AkunTransaksiController::class, 'update'])->name('app.akun-transaksi.update');
            Route::delete('/akun-transaksi/{akunTransaksi}', [AkunTransaksiController::class, 'destroy'])->name('app.akun-transaksi.destroy');
        });
        
        Route::prefix('inventory')->group(function () {
            // Inventory Products — Core: view-only product stock summary
            Route::get('/products', [InventoryProductController::class, 'index'])->name('app.inventory.products');
            // StockAdjustment & StockOpname moved to Modules\Inventory (app/Modules/Inventory/routes.php)
        });
    });
    
    // Akunting routes moved to Modules\Akunting (app/Modules/Akunting/routes.php)
    // URL prefix: /app/modules/akunting/

    Route::get('/settings', function () { return Inertia::render('core/settings/Index'); })->name('app.settings');
    
    // Module Management
    Route::get('/settings/modules', [ModuleManagementController::class, 'index'])->name('app.settings.modules');
    Route::post('/settings/modules/{id}/toggle', [ModuleManagementController::class, 'toggle'])->name('app.settings.modules.toggle');
    Route::post('/settings/modules/{id}/update', [ModuleManagementController::class, 'updateModule'])->name('app.settings.modules.update');
    Route::delete('/settings/modules/{id}', [ModuleManagementController::class, 'destroy'])->name('app.settings.modules.destroy');
});

// POS receipt preview/print
Route::get('/pos/receipt/{penjualan}', function (\App\Models\Penjualan $penjualan) {
    return view('pos.receipt', [
        'penjualan' => $penjualan->load(['items.produk', 'items.pembelianItem', 'karyawan']),
    ]);
})->name('pos.receipt');

Route::get('/penjualan/invoice/{penjualan}', function (\App\Models\Penjualan $penjualan) {
    return view('penjualan.invoice', [
        'penjualan' => $penjualan->load([
            'items.produk',
            'items.pembelianItem.pembelian',
            'jasaItems.jasa',
            'member',
            'karyawan',
            'akunTransaksi',
            'pembayaran.akunTransaksi',
        ]),
        'profile' => \App\Models\ProfilePerusahaan::first(),
    ]);
})->name('penjualan.invoice');

Route::get('/penjualan/invoice-simple/{penjualan}', function (\App\Models\Penjualan $penjualan) {
    return view('penjualan.invoice-simple', [
        'penjualan' => $penjualan->load([
            'items.produk',
            'jasaItems.jasa',
            'member',
            'karyawan',
            'pembayaran.akunTransaksi',
        ]),
        'profile' => \App\Models\ProfilePerusahaan::first(),
    ]);
})->name('penjualan.invoice.simple');

Route::get('/penjadwalan-service/print/{record}', function (\App\Models\PenjadwalanService $record) {
    return view('filament.resources.penjadwalan-service.print', [
        'record' => $record->load(['member', 'technician', 'jasa']),
        'profile' => \App\Models\ProfilePerusahaan::first(),
    ]);
})->name('penjadwalan-service.print');

Route::get('/penjadwalan-service/invoice-simple/{record}', function (\App\Models\PenjadwalanService $record) {
    return view('filament.resources.penjadwalan-service.invoice-simple', [
        'record' => $record->load(['member', 'technician', 'jasa']),
        'profile' => \App\Models\ProfilePerusahaan::first(),
    ]);
})->name('penjadwalan-service.invoice.simple');

Route::get('/tukar-tambah/invoice/{tukarTambah}', function (\App\Models\TukarTambah $tukarTambah) {
    return view('tukar-tambah.invoice', [
        'tukarTambah' => $tukarTambah->load([
            'karyawan',
            'penjualan.items.produk',
            'penjualan.jasaItems.jasa',
            'penjualan.member',
            'penjualan.karyawan',
            'penjualan.pembayaran.akunTransaksi',
            'pembelian.items.produk',
            'pembelian.supplier',
            'pembelian.karyawan',
        ]),
        'profile' => \App\Models\ProfilePerusahaan::first(),
    ]);
})->name('tukar-tambah.invoice');

Route::get('/tukar-tambah/invoice-simple/{tukarTambah}', function (\App\Models\TukarTambah $tukarTambah) {
    return view('tukar-tambah.invoice-simple', [
        'tukarTambah' => $tukarTambah->load([
            'karyawan',
            'penjualan.items.produk',
            'penjualan.jasaItems.jasa',
            'penjualan.member',
            'penjualan.karyawan',
            'penjualan.pembayaran.akunTransaksi',
            'pembelian.items.produk',
            'pembelian.supplier',
            'pembelian.karyawan',
        ]),
        'profile' => \App\Models\ProfilePerusahaan::first(),
    ]);
})->name('tukar-tambah.invoice.simple');

Route::get('/penjadwalan-service/print-crosscheck/{record}', function (\App\Models\PenjadwalanService $record) {
    return view('filament.resources.penjadwalan-service.print-crosscheck', [
        'record' => $record->load(['member', 'technician', 'jasa', 'crosschecks', 'listAplikasis', 'listGames', 'listOs']),
        'profile' => \App\Models\ProfilePerusahaan::first(),
    ]);
})->name('penjadwalan-service.print-crosscheck');

// PWA offline fallback route
Route::get('/offline', function () {
    return view('vendor.laravelpwa.offline');
})->name('offline');
