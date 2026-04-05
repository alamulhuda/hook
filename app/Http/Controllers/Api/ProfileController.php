<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProfilePerusahaanResource;
use App\Models\ProfilePerusahaan;
use Illuminate\Http\JsonResponse;

class ProfileController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $profile = ProfilePerusahaan::first();

        if (is_null($profile)) {
            return $this->sendError('Profile perusahaan not found.');
        }

        return $this->sendResponse(
            new ProfilePerusahaanResource($profile),
            'Profile perusahaan retrieved successfully.'
        );
    }
}
