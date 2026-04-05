<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BrandResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_brand' => $this->nama_brand,
            'slug' => $this->slug,
            'kode' => $this->kode,
            'logo_url' => $this->logo_url ? Storage::disk('public')->url($this->logo_url) : null,
        ];
    }
}
