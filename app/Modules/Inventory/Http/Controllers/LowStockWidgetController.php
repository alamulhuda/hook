<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\JsonResponse;

class LowStockWidgetController extends Controller
{
    /**
     * Returns products with stock below their minimum threshold.
     * Called independently by the LowStockWidget Vue component.
     */
    public function __invoke(): JsonResponse
    {
        // Stock is the sum of qty_sisa from PembelianItem for each product.
        // We use withSum to calculate it on the fly and filter products with 5 or less.
        $items = Produk::select('id', 'nama_produk', 'sku')
            ->withSum('pembelianItems', 'qty_sisa')
            ->havingRaw('COALESCE(pembelian_items_sum_qty_sisa, 0) <= 5')
            ->orderBy('pembelian_items_sum_qty_sisa', 'asc')
            ->limit(10)
            ->get()
            ->map(fn ($item) => [
                'id'      => $item->id,
                'name'    => $item->nama_produk,
                'sku'     => $item->sku ?? '-',
                'stock'   => (int) $item->pembelian_items_sum_qty_sisa,
                'minimum' => 5,
            ]);

        return response()->json(['data' => $items]);
    }
}
