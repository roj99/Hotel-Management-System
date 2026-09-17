<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hotel\StoreRoomImageRequest;
use App\Http\Requests\Hotel\UpdateRoomImageRequest;
use App\Http\Resources\Hotel\RoomImageResource;
use App\Models\Hotel\RoomImage;
use Illuminate\Http\JsonResponse;

class RoomImageController extends Controller
{
    public function index(): JsonResponse
    {
        $roomImages = RoomImage::all();

        return response()->json(RoomImageResource::collection($roomImages));
    }

    public function store(StoreRoomImageRequest $request): JsonResponse
    {
        $roomImage = RoomImage::create($request->validated());

        return response()->json(new RoomImageResource($roomImage), 201);
    }

    public function show(RoomImage $roomImage): JsonResponse
    {
        return response()->json(new RoomImageResource($roomImage));
    }

    public function update(UpdateRoomImageRequest $request, RoomImage $roomImage): JsonResponse
    {
        $roomImage->update($request->validated());

        return response()->json(new RoomImageResource($roomImage));
    }

    public function destroy(RoomImage $roomImage): JsonResponse
    {
        $roomImage->delete();

        return response()->json(null, 204);
    }
}
