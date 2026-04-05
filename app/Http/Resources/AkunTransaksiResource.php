<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AkunTransaksiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_akun' => $this->nama_akun,
            'kode_akun' => $this->kode_akun,
            'jenis' => $this->jenis,
            'nama_bank' => $this->nama_bank,
        ];
    }
}
