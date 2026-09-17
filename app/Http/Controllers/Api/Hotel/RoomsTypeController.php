<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hotel\StoreRoomsTypeRequest;
use App\Http\Requests\Hotel\UpdateRoomsTypeRequest;
use App\Http\Resources\Hotel\RoomsTypeResource;
use App\Models\Hotel\RoomsType;
use Illuminate\Http\JsonResponse;

class RoomsTypeController extends Controller
{
    public function index(): JsonResponse
    {
        $roomsTypes = RoomsType::with(['images', 'amenities', 'prices'])->get();

        return response()->json(RoomsTypeResource::collection($roomsTypes));
    }

    public function store(StoreRoomsTypeRequest $request): JsonResponse
    {
        $roomsType = RoomsType::create($request->validated());

        return response()->json(new RoomsTypeResource($roomsType), 201);
    }

    public function show(RoomsType $roomsType): JsonResponse
    {
        $roomsType->load(['images', 'amenities', 'prices']);

        return response()->json(new RoomsTypeResource($roomsType));
    }

    public function update(UpdateRoomsTypeRequest $request, RoomsType $roomsType): JsonResponse
    {
        $roomsType->update($request->validated());

        return response()->json(new RoomsTypeResource($roomsType));
    }

    public function destroy(RoomsType $roomsType): JsonResponse
    {
        $roomsType->delete();

        return response()->json(null, 204);
    }
}
