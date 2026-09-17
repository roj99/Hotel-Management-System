<?php

namespace App\Http\Resources\Booking;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingActionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_id' => $this->booking_id,
            'action_type' => $this->action_type,
            'performed_by' => new UserResource($this->whenLoaded('performedBy')),
            'reason' => $this->reason,
            'performed_at' => $this->performed_at,
        ];
    }
}
