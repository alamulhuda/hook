<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\KaryawanResource;
use App\Models\Karyawan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KaryawanController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Karyawan::query()->with(['role', 'gudang']);

        // Optional filtering by search term
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nama_karyawan', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%");
        }

        $karyawans = $query->paginate($request->input('per_page', 15));

        return $this->sendResponse(
            KaryawanResource::collection($karyawans)->response()->getData(true),
            'Karyawans retrieved successfully.'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $karyawan = Karyawan::with(['role', 'gudang'])->find($id);

        if (is_null($karyawan)) {
            return $this->sendError('Karyawan not found.');
        }

        return $this->sendResponse(new KaryawanResource($karyawan), 'Karyawan retrieved successfully.');
    }
}
