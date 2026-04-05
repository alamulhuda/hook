<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class JasaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_jasa' => $this->nama_jasa,
            'sku' => $this->sku,
            'harga' => (float) $this->harga,
            'estimasi_waktu_jam' => $this->estimasi_waktu_jam,
            'deskripsi' => $this->deskripsi,
            'image_url' => $this->image_url ? Storage::disk('public')->url($this->image_url) : null,
            'is_active' => $this->is_active,
        ];
    }
}
