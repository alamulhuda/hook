<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProdukResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_produk' => $this->nama_produk,
            'kategori' => $this->whenLoaded('kategori', fn() => $this->kategori->nama_kategori),
            'brand' => $this->whenLoaded('brand', fn() => $this->brand->nama_brand),
            'sku' => $this->sku,
            'sn' => $this->sn,
            'garansi' => $this->garansi,
            'berat' => (float) $this->berat,
            'panjang' => (float) $this->panjang,
            'lebar' => (float) $this->lebar,
            'tinggi' => (float) $this->tinggi,
            'deskripsi' => $this->deskripsi,
            'image_url' => $this->image_url ? Storage::disk('public')->url($this->image_url) : null,
        ];
    }
}
