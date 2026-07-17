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
        // Stock is tracked via StockAdjustment / StockOpname.
        // We query Produk and check against the stok_minimum field if it exists,
        // falling back to a threshold of 5 if none is set.
        $items = Produk::select('id', 'nama_produk', 'sku', 'stok', 'stok_minimum')
            ->whereRaw('stok <= COALESCE(stok_minimum, 5)')
            ->orderBy('stok', 'asc')
            ->limit(10)
            ->get()
            ->map(fn ($item) => [
                'id'      => $item->id,
                'name'    => $item->nama_produk,
                'sku'     => $item->sku ?? '-',
                'stock'   => $item->stok ?? 0,
                'minimum' => $item->stok_minimum ?? 5,
            ]);

        return response()->json(['data' => $items]);
    }
}
