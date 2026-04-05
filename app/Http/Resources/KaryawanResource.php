<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class KaryawanResource extends JsonResource
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
            'nama_karyawan' => $this->nama_karyawan,
            'slug' => $this->slug,
            'telepon' => $this->telepon,
            'alamat' => $this->alamat,
            'provinsi' => $this->provinsi,
            'kota' => $this->kota,
            'kecamatan' => $this->kecamatan,
            'kelurahan' => $this->kelurahan,
            'image_url' => $this->image_url ? Storage::disk('public')->url($this->image_url) : null,
            'is_active' => $this->is_active,
            'role' => $this->whenLoaded('role', fn() => $this->role->name),
            'gudang' => $this->whenLoaded('gudang', fn() => $this->gudang->nama_gudang),
        ];
    }
}
