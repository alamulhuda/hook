<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AkunTransaksiResource;
use App\Models\AkunTransaksi;
use Illuminate\Http\JsonResponse;

class AkunTransaksiController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $akun = AkunTransaksi::where('is_active', true)->get();

        return $this->sendResponse(
            AkunTransaksiResource::collection($akun),
            'Akun transaksi retrieved successfully.'
        );
    }
}
