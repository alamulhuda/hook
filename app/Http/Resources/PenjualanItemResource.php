<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PenjualanItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_penjualan_item,
            'qty' => (int) $this->qty,
            'harga_jual' => (float) $this->harga_jual,
            'kondisi' => $this->kondisi,
            'serials' => $this->serials,
            'produk' => $this->whenLoaded('produk', function () {
                return [
                    'id' => $this->produk->id,
                    'nama_produk' => $this->produk->nama_produk,
                    'sku' => $this->produk->sku,
                ];
            }),
        ];
    }
}
