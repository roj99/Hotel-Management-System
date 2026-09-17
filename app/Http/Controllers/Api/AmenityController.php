<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAmenityRequest;
use App\Http\Requests\UpdateAmenityRequest;
use App\Http\Resources\AmenityResource;
use App\Models\Amenity;
use Illuminate\Http\JsonResponse;

class AmenityController extends Controller
{
    public function index(): JsonResponse
    {
        $amenities = Amenity::all();

        return response()->json(AmenityResource::collection($amenities));
    }

    public function store(StoreAmenityRequest $request): JsonResponse
    {
        $amenity = Amenity::create($request->validated());

        return response()->json(new AmenityResource($amenity), 201);
    }

    public function show(Amenity $amenity): JsonResponse
    {
        return response()->json(new AmenityResource($amenity));
    }

    public function update(UpdateAmenityRequest $request, Amenity $amenity): JsonResponse
    {
        $amenity->update($request->validated());

        return response()->json(new AmenityResource($amenity));
    }

    public function destroy(Amenity $amenity): JsonResponse
    {
        $amenity->delete();

        return response()->json(null, 204);
    }
}
