<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreGuestRequest;
use App\Http\Requests\Booking\UpdateGuestRequest;
use App\Http\Resources\Booking\GuestResource;
use App\Models\Booking\Guest;
use Illuminate\Http\JsonResponse;

class GuestController extends Controller
{
    public function index(): JsonResponse
    {
        $guests = Guest::all();

        return response()->json(GuestResource::collection($guests));
    }

    public function store(StoreGuestRequest $request): JsonResponse
    {
        $guest = Guest::create($request->validated());

        return response()->json(new GuestResource($guest), 201);
    }

    public function show(Guest $guest): JsonResponse
    {
        return response()->json(new GuestResource($guest));
    }

    public function update(UpdateGuestRequest $request, Guest $guest): JsonResponse
    {
        $guest->update($request->validated());

        return response()->json(new GuestResource($guest));
    }

    public function destroy(Guest $guest): JsonResponse
    {
        $guest->delete();

        return response()->json(null, 204);
    }
}
