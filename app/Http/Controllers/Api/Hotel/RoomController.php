<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hotel\StoreRoomRequest;
use App\Http\Requests\Hotel\UpdateRoomRequest;
use App\Http\Resources\Hotel\RoomResource;
use App\Models\Hotel\Room;
use Illuminate\Http\JsonResponse;

class RoomController extends Controller
{
    public function index(): JsonResponse
    {
        $rooms = Room::with('roomType')->get();

        return response()->json(RoomResource::collection($rooms));
    }

    public function store(StoreRoomRequest $request): JsonResponse
    {
        $room = Room::create($request->validated());

        return response()->json(new RoomResource($room), 201);
    }

    public function show(Room $room): JsonResponse
    {
        $room->load('roomType');

        return response()->json(new RoomResource($room));
    }

    public function update(UpdateRoomRequest $request, Room $room): JsonResponse
    {
        $room->update($request->validated());

        return response()->json(new RoomResource($room));
    }

    public function destroy(Room $room): JsonResponse
    {
        $room->delete();

        return response()->json(null, 204);
    }
}
