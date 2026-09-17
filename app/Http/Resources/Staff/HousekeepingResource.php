<?php

namespace App\Http\Resources\Staff;

use App\Http\Resources\Hotel\RoomResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HousekeepingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'room' => new RoomResource($this->whenLoaded('room')),
            'staff' => new UserResource($this->whenLoaded('staff')),
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
        ];
    }
}
