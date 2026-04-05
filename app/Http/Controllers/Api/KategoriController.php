<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\KategoriResource;
use App\Models\Kategori;
use Illuminate\Http\JsonResponse;

class KategoriController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $kategori = Kategori::all();

        return $this->sendResponse(
            KategoriResource::collection($kategori),
            'Kategori retrieved successfully.'
        );
    }
}
