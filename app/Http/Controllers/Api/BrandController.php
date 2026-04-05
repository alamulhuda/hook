<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;

class BrandController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $brands = Brand::all();

        return $this->sendResponse(
            BrandResource::collection($brands),
            'Brand retrieved successfully.'
        );
    }
}
