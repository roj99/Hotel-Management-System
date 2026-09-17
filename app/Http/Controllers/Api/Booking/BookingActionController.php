<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingActionRequest;
use App\Http\Requests\Booking\UpdateBookingActionRequest;
use App\Http\Resources\Booking\BookingActionResource;
use App\Models\Booking\BookingAction;
use Illuminate\Http\JsonResponse;

class BookingActionController extends Controller
{
    public function index(): JsonResponse
    {
        $bookingActions = BookingAction::with(['booking', 'performedBy'])->get();

        return response()->json(BookingActionResource::collection($bookingActions));
    }

    public function store(StoreBookingActionRequest $request): JsonResponse
    {
        $bookingAction = BookingAction::create($request->validated());

        return response()->json(new BookingActionResource($bookingAction), 201);
    }

    public function show(BookingAction $bookingAction): JsonResponse
    {
        $bookingAction->load(['booking', 'performedBy']);

        return response()->json(new BookingActionResource($bookingAction));
    }

    public function update(UpdateBookingActionRequest $request, BookingAction $bookingAction): JsonResponse
    {
        $bookingAction->update($request->validated());

        return response()->json(new BookingActionResource($bookingAction));
    }

    public function destroy(BookingAction $bookingAction): JsonResponse
    {
        $bookingAction->delete();

        return response()->json(null, 204);
    }
}
