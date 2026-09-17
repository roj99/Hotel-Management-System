<?php

namespace App\Http\Resources\Hotel;

use App\Http\Resources\Booking\BookingResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking' => new BookingResource($this->whenLoaded('booking')),
            'handled_by' => new UserResource($this->whenLoaded('handledBy')),
            'item_description' => $this->item_description,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'status' => $this->status,
            'ordered_at' => $this->ordered_at,
            'delivered_at' => $this->delivered_at,
        ];
    }
}
