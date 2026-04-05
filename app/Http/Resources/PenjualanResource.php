<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PenjualanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_penjualan,
            'no_nota' => $this->no_nota,
            'tanggal_penjualan' => $this->tanggal_penjualan ? $this->tanggal_penjualan->format('Y-m-d') : null,
            'catatan' => $this->catatan,
            'total' => (float) $this->total,
            'diskon_total' => (float) $this->diskon_total,
            'grand_total' => (float) $this->grand_total,
            'tunai_diterima' => (float) $this->tunai_diterima,
            'kembalian' => (float) $this->kembalian,
            'metode_bayar' => $this->metode_bayar?->value,
            'status_pembayaran' => $this->status_pembayaran,
            'sumber_transaksi' => $this->sumber_transaksi,
            'is_nerfed' => $this->is_nerfed,
            'foto_dokumen' => collect($this->foto_dokumen)->map(fn($path) => Storage::disk('public')->url($path))->toArray(),
            'created_at' => $this->created_at,
            
            // Relations
            'karyawan' => $this->whenLoaded('karyawan', fn() => new KaryawanResource($this->karyawan)),
            'member' => $this->whenLoaded('member', fn() => new MemberResource($this->member)),
            'items' => PenjualanItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
