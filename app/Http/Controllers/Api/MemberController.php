<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\MemberResource;
use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MemberController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Member::query();

        // Optional filtering by search term
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nama_member', 'like', "%{$search}%")
                  ->orWhere('kode_member', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
        }

        $members = $query->paginate($request->input('per_page', 15));

        return $this->sendResponse(
            MemberResource::collection($members)->response()->getData(true),
            'Members retrieved successfully.'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $member = Member::find($id);

        if (is_null($member)) {
            return $this->sendError('Member not found.');
        }

        return $this->sendResponse(new MemberResource($member), 'Member retrieved successfully.');
    }
}
