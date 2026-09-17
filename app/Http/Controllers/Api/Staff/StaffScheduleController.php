<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreStaffScheduleRequest;
use App\Http\Requests\Staff\UpdateStaffScheduleRequest;
use App\Http\Resources\Staff\StaffScheduleResource;
use App\Models\Staff\StaffSchedule;
use Illuminate\Http\JsonResponse;

class StaffScheduleController extends Controller
{
    public function index(): JsonResponse
    {
        $staffSchedules = StaffSchedule::with('user')->get();

        return response()->json(StaffScheduleResource::collection($staffSchedules));
    }

    public function store(StoreStaffScheduleRequest $request): JsonResponse
    {
        $staffSchedule = StaffSchedule::create($request->validated());

        return response()->json(new StaffScheduleResource($staffSchedule), 201);
    }

    public function show(StaffSchedule $staffSchedule): JsonResponse
    {
        $staffSchedule->load('user');

        return response()->json(new StaffScheduleResource($staffSchedule));
    }

    public function update(UpdateStaffScheduleRequest $request, StaffSchedule $staffSchedule): JsonResponse
    {
        $staffSchedule->update($request->validated());

        return response()->json(new StaffScheduleResource($staffSchedule));
    }

    public function destroy(StaffSchedule $staffSchedule): JsonResponse
    {
        $staffSchedule->delete();

        return response()->json(null, 204);
    }
}
