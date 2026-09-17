<?php

namespace App\Http\Resources\Staff;

use App\Http\Resources\Hotel\RoomResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LostFoundItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'room' => new RoomResource($this->whenLoaded('room')),
            'found_by' => new UserResource($this->whenLoaded('foundBy')),
            'item_description' => $this->item_description,
            'storage_location' => $this->storage_location,
            'status' => $this->status,
            'found_at' => $this->found_at,
            'returned_at' => $this->returned_at,
        ];
    }
}
