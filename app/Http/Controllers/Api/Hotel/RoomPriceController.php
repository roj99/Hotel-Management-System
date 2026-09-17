<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hotel\StoreRoomPriceRequest;
use App\Http\Requests\Hotel\UpdateRoomPriceRequest;
use App\Http\Resources\Hotel\RoomPriceResource;
use App\Models\Hotel\RoomPrice;
use Illuminate\Http\JsonResponse;

class RoomPriceController extends Controller
{
    public function index(): JsonResponse
    {
        $roomPrices = RoomPrice::all();

        return response()->json(RoomPriceResource::collection($roomPrices));
    }

    public function store(StoreRoomPriceRequest $request): JsonResponse
    {
        $roomPrice = RoomPrice::create($request->validated());

        return response()->json(new RoomPriceResource($roomPrice), 201);
    }

    public function show(RoomPrice $roomPrice): JsonResponse
    {
        return response()->json(new RoomPriceResource($roomPrice));
    }

    public function update(UpdateRoomPriceRequest $request, RoomPrice $roomPrice): JsonResponse
    {
        $roomPrice->update($request->validated());

        return response()->json(new RoomPriceResource($roomPrice));
    }

    public function destroy(RoomPrice $roomPrice): JsonResponse
    {
        $roomPrice->delete();

        return response()->json(null, 204);
    }
}
