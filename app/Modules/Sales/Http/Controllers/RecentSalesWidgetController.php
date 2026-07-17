<?php

namespace App\Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecentSalesWidgetController extends Controller
{
    /**
     * Returns the most recent sales for the dashboard widget.
     * Called independently by the RecentSalesWidget Vue component.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $dateFrom = $request->query('from');
        $dateTo   = $request->query('to');

        $query = Penjualan::with('member')
            ->where('status_pembayaran', 'lunas');

        if ($dateFrom && $dateTo) {
            $query->whereBetween('tanggal_penjualan', [$dateFrom, $dateTo]);
        }

        $items = $query
            ->orderBy('tanggal_penjualan', 'desc')
            ->limit(5)
            ->get()
            ->map(fn ($sale) => [
                'id'       => $sale->id,
                'customer' => $sale->member->nama_member ?? 'Guest',
                'nota'     => $sale->no_nota ?? 'Transaksi #' . $sale->id,
                'amount'   => 'Rp ' . number_format($sale->grand_total, 0, ',', '.'),
                'date'     => $sale->tanggal_penjualan->format('Y-m-d'),
            ]);

        return response()->json(['data' => $items]);
    }
}
