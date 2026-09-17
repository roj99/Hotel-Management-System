<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hotel\StoreRoomServiceRequest;
use App\Http\Requests\Hotel\UpdateRoomServiceRequest;
use App\Http\Resources\Hotel\RoomServiceResource;
use App\Models\Hotel\RoomService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomServiceController extends Controller
{
    public function index(): JsonResponse
    {
        $roomServices = RoomService::with(['booking', 'handledBy'])->get();

        return response()->json(RoomServiceResource::collection($roomServices));
    }

    /**
     * A staff member creates the order the moment the guest calls it in -
     * it lands in the queue as "pending" immediately.
     */
    public function store(StoreRoomServiceRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['status'] = $data['status'] ?? 'pending';
        $data['ordered_at'] = now();

        $roomService = RoomService::create($data);

        return response()->json(new RoomServiceResource($roomService), 201);
    }

    public function show(RoomService $roomService): JsonResponse
    {
        $roomService->load(['booking', 'handledBy']);

        return response()->json(new RoomServiceResource($roomService));
    }

    public function update(UpdateRoomServiceRequest $request, RoomService $roomService): JsonResponse
    {
        $roomService->update($request->validated());

        return response()->json(new RoomServiceResource($roomService));
    }

    public function destroy(RoomService $roomService): JsonResponse
    {
        $roomService->delete();

        return response()->json(null, 204);
    }

    /**
     * Staff moves the order along the flow: pending -> preparing -> delivered.
     * The moment it's marked "delivered", its cost is settled - it will be
     * picked up automatically by checkOut() when the booking's invoice is
     * generated, without the receptionist doing anything manually.
     */
    public function updateStatus(Request $request, RoomService $roomService): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'in:pending,preparing,delivered,cancelled'],
        ]);

        $roomService->update([
            'status' => $request->status,
            'delivered_at' => $request->status === 'delivered' ? now() : $roomService->delivered_at,
        ]);

        return response()->json(new RoomServiceResource($roomService));
    }
}
