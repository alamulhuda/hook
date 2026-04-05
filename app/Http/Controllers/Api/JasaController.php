<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\JasaResource;
use App\Models\Jasa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JasaController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Jasa::query()->where('is_active', true);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nama_jasa', 'like', "%{$search}%");
        }

        $jasa = $query->get();

        return $this->sendResponse(
            JasaResource::collection($jasa),
            'Jasa retrieved successfully.'
        );
    }

    public function show($id): JsonResponse
    {
        $jasa = Jasa::find($id);

        if (is_null($jasa)) {
            return $this->sendError('Jasa not found.');
        }

        return $this->sendResponse(new JasaResource($jasa), 'Jasa retrieved successfully.');
    }
}
