<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProdukResource;
use App\Models\Produk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProdukController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Produk::query()->with(['kategori', 'brand']);

        // Optional filtering by search term
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
        }

        $produk = $query->paginate($request->input('per_page', 15));

        return $this->sendResponse(
            ProdukResource::collection($produk)->response()->getData(true),
            'Produk retrieved successfully.'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $produk = Produk::with(['kategori', 'brand'])->find($id);

        if (is_null($produk)) {
            return $this->sendError('Produk not found.');
        }

        return $this->sendResponse(new ProdukResource($produk), 'Produk retrieved successfully.');
    }
}
