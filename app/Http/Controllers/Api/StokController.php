<?php

namespace App\Http\Controllers\Api;

use App\Models\PembelianItem;
use App\Models\Produk;
use Illuminate\Http\JsonResponse;

class StokController extends BaseApiController
{
    public function show($produkId): JsonResponse
    {
        $produk = Produk::find($produkId);

        if (is_null($produk)) {
            return $this->sendError('Produk not found.');
        }

        $productFk = PembelianItem::productForeignKey();
        $qtySisaCol = PembelianItem::qtySisaColumn();

        $batches = PembelianItem::where($productFk, $produkId)
            ->where($qtySisaCol, '>', 0)
            ->get()
            ->map(function ($batch) use ($qtySisaCol) {
                return [
                    'id_pembelian_item' => $batch->id_pembelian_item,
                    'qty_sisa' => (int) $batch->{$qtySisaCol},
                    'harga_jual' => (float) $batch->harga_jual,
                    'hpp' => (float) $batch->hpp,
                    'kondisi' => $batch->kondisi,
                    'serials' => $batch->serials,
                ];
            });

        $totalStok = $batches->sum('qty_sisa');

        return $this->sendResponse([
            'produk_id' => (int) $produkId,
            'nama_produk' => $produk->nama_produk,
            'total_stok' => $totalStok,
            'batches' => $batches->values(),
        ], 'Stok retrieved successfully.');
    }
}
