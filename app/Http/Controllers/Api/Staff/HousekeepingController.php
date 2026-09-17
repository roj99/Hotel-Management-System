<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreHousekeepingRequest;
use App\Http\Requests\Staff\UpdateHousekeepingRequest;
use App\Http\Resources\Staff\HousekeepingResource;
use App\Models\Staff\Housekeeping;
use Illuminate\Http\JsonResponse;

class HousekeepingController extends Controller
{
    /**
     * Task list, ordered so rooms whose guests left today show up first.
     */
    public function index(): JsonResponse
    {
        $housekeepingTasks = Housekeeping::with(['room', 'staff'])
            ->orderBy('started_at')
            ->get();

        return response()->json(HousekeepingResource::collection($housekeepingTasks));
    }

    /**
     * Housekeeping staff starts a task - the room moves to "cleaning" so the
     * front desk can see someone is already on it.
     */
    public function store(StoreHousekeepingRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['started_at'] = $data['started_at'] ?? now();

        $housekeeping = Housekeeping::create($data);
        $housekeeping->room()->update(['status' => 'cleaning']);

        return response()->json(new HousekeepingResource($housekeeping), 201);
    }

    public function show(Housekeeping $housekeeping): JsonResponse
    {
        $housekeeping->load(['room', 'staff']);

        return response()->json(new HousekeepingResource($housekeeping));
    }

    public function update(UpdateHousekeepingRequest $request, Housekeeping $housekeeping): JsonResponse
    {
        $housekeeping->update($request->validated());

        return response()->json(new HousekeepingResource($housekeeping));
    }

    public function destroy(Housekeeping $housekeeping): JsonResponse
    {
        $housekeeping->delete();

        return response()->json(null, 204);
    }

    /**
     * Housekeeping staff marks the task finished - the room becomes
     * "available" again, ready for the front desk to check a new guest in.
     */
    public function finish(Housekeeping $housekeeping): JsonResponse
    {
        $housekeeping->update(['finished_at' => now()]);
        $housekeeping->room()->update(['status' => 'available']);

        return response()->json(new HousekeepingResource($housekeeping));
    }
}
