<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreLostFoundItemRequest;
use App\Http\Requests\Staff\UpdateLostFoundItemRequest;
use App\Http\Resources\Staff\LostFoundItemResource;
use App\Models\Staff\LostFoundItem;
use Illuminate\Http\JsonResponse;

class LostFoundItemController extends Controller
{
    public function index(): JsonResponse
    {
        $lostFoundItems = LostFoundItem::with(['room', 'foundBy'])->get();

        return response()->json(LostFoundItemResource::collection($lostFoundItems));
    }

    public function store(StoreLostFoundItemRequest $request): JsonResponse
    {
        $lostFoundItem = LostFoundItem::create($request->validated());

        return response()->json(new LostFoundItemResource($lostFoundItem), 201);
    }

    public function show(LostFoundItem $lostFoundItem): JsonResponse
    {
        $lostFoundItem->load(['room', 'foundBy']);

        return response()->json(new LostFoundItemResource($lostFoundItem));
    }

    public function update(UpdateLostFoundItemRequest $request, LostFoundItem $lostFoundItem): JsonResponse
    {
        $lostFoundItem->update($request->validated());

        return response()->json(new LostFoundItemResource($lostFoundItem));
    }

    public function destroy(LostFoundItem $lostFoundItem): JsonResponse
    {
        $lostFoundItem->delete();

        return response()->json(null, 204);
    }
}
