<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProfilePerusahaanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'nama_perusahaan' => $this->nama_perusahaan,
            'alamat_perusahaan' => $this->alamat_perusahaan,
            'email' => $this->email,
            'telepon' => $this->telepon,
            'npwp' => $this->npwp,
            'logo_url' => $this->logo_link ? Storage::disk('public')->url($this->logo_link) : null,
        ];
    }
}
