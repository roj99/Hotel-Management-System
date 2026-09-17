<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Booking\Booking;
use App\Models\Review;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function index(): JsonResponse
    {
        $reviews = Review::with(['booking', 'user'])->get();

        return response()->json(ReviewResource::collection($reviews));
    }

    /**
     * Any logged-in guest can leave a general review. If a booking_id IS
     * supplied (e.g. from the "Leave a Review" link on a finished stay),
     * we still enforce that the stay is actually checked_out before
     * accepting it - you can't rate a stay that hasn't happened yet.
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        if ($request->filled('booking_id')) {
            $booking = Booking::find($request->booking_id);

            if (! $booking || $booking->status !== 'checked_out') {
                return response()->json([
                    'message' => 'A review can only be submitted for a completed stay.',
                ], 422);
            }
        }

        $review = Review::create($request->validated());

        return response()->json(new ReviewResource($review), 201);
    }

    public function show(Review $review): JsonResponse
    {
        $review->load(['booking', 'user']);

        return response()->json(new ReviewResource($review));
    }

    public function update(UpdateReviewRequest $request, Review $review): JsonResponse
    {
        $review->update($request->validated());

        return response()->json(new ReviewResource($review));
    }

    public function destroy(Review $review): JsonResponse
    {
        $review->delete();

        return response()->json(null, 204);
    }
}
