<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Date range filter
        $dateFrom = $request->query('from');
        $dateTo = $request->query('to');

        // Base query with date filter
        $penjualanQuery = Penjualan::where('status_pembayaran', 'lunas');
        
        if ($dateFrom && $dateTo) {
            $penjualanQuery->whereBetween('tanggal_penjualan', [$dateFrom, $dateTo]);
        }

        // Stats
        $totalRevenue = $penjualanQuery->sum('grand_total') ?? 0;
        $totalSales = $penjualanQuery->count() ?? 0;
        $totalProducts = Produk::count() ?? 0;
        $totalCustomers = Member::count() ?? 0;

        // Recent Sales (last 5) - also filtered by date if set
        $recentSalesQuery = Penjualan::with('member')
            ->where('status_pembayaran', 'lunas');
        
        if ($dateFrom && $dateTo) {
            $recentSalesQuery->whereBetween('tanggal_penjualan', [$dateFrom, $dateTo]);
        }

        $recentSales = $recentSalesQuery
            ->orderBy('tanggal_penjualan', 'desc')
            ->limit(5)
            ->get()
            ->map(fn ($sale) => [
                'id' => $sale->id,
                'customer' => $sale->member->nama_member ?? 'Guest',
                'product' => $sale->no_nota ?? 'Transaksi #' . $sale->id,
                'amount' => 'Rp ' . number_format($sale->grand_total, 0, ',', '.'),
                'date' => $sale->tanggal_penjualan->format('Y-m-d'),
            ]);

        // Low Stock Items - get from StockOpname latest data
        // Stock is managed via StockOpname/StockAdjustment, not direct on Produk
        // For now, show recent products as placeholder
        $lowStockItems = Produk::orderBy('id', 'desc')
            ->limit(5)
            ->get()
            ->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->nama_produk,
                'sku' => $item->sku ?? '-',
                'stock' => 'N/A',
                'min' => 'N/A',
            ]);

        // Calculate trends (compare with last month)
        $currentMonthRevenue = Penjualan::where('status_pembayaran', 'lunas')
            ->whereMonth('tanggal_penjualan', now()->month)
            ->whereYear('tanggal_penjualan', now()->year)
            ->sum('grand_total') ?? 0;
        
        $lastMonthRevenue = Penjualan::where('status_pembayaran', 'lunas')
            ->whereMonth('tanggal_penjualan', now()->subMonth()->month)
            ->whereYear('tanggal_penjualan', now()->subMonth()->year)
            ->sum('grand_total') ?? 0;

        $revenueChange = $lastMonthRevenue > 0 
            ? round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        $currentMonthSales = Penjualan::where('status_pembayaran', 'lunas')
            ->whereMonth('tanggal_penjualan', now()->month)
            ->whereYear('tanggal_penjualan', now()->year)
            ->count();
        
        $lastMonthSales = Penjualan::where('status_pembayaran', 'lunas')
            ->whereMonth('tanggal_penjualan', now()->subMonth()->month)
            ->whereYear('tanggal_penjualan', now()->subMonth()->year)
            ->count();

        $salesChange = $lastMonthSales > 0 
            ? round((($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100, 1)
            : 0;

        $stats = [
            [
                'title' => 'Total Revenue',
                'value' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
                'change' => ($revenueChange >= 0 ? '+' : '') . $revenueChange . '%',
                'trend' => $revenueChange >= 0 ? 'up' : 'down',
                'icon' => 'DollarSign',
            ],
            [
                'title' => 'Total Sales',
                'value' => number_format($totalSales, 0, ',', '.'),
                'change' => ($salesChange >= 0 ? '+' : '') . $salesChange . '%',
                'trend' => $salesChange >= 0 ? 'up' : 'down',
                'icon' => 'ShoppingCart',
            ],
            [
                'title' => 'Products',
                'value' => number_format($totalProducts, 0, ',', '.'),
                'change' => '0%',
                'trend' => 'up',
                'icon' => 'Package',
            ],
            [
                'title' => 'Customers',
                'value' => number_format($totalCustomers, 0, ',', '.'),
                'change' => '0%',
                'trend' => 'up',
                'icon' => 'Users',
            ],
        ];

        return Inertia::render('app/dashboard', [
            'user' => auth()->user(),
            'stats' => $stats,
            'recentSales' => $recentSales,
            'lowStockItems' => $lowStockItems,
        ]);
    }
}
