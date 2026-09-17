<?php

namespace App\Http\Resources\Staff;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'shift_date' => $this->shift_date,
            'shift_start' => $this->shift_start,
            'shift_end' => $this->shift_end,
            'status' => $this->status,
        ];
    }
}
