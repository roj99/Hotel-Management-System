<?php

namespace App\Http\Resources\Hotel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomPriceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'room_type_id' => $this->room_type_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'price_per_night' => $this->price_per_night,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
