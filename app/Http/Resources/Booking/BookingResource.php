<?php

namespace App\Http\Resources\Booking;

use App\Http\Resources\Hotel\RoomResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            //رجع مستخدم واحد
            'user' => new UserResource($this->whenLoaded('user')),
            //رجع اكتر من غرفة
            'rooms' => RoomResource::collection($this->whenLoaded('rooms')),
            'check_in_date' => $this->check_in_date,
            'check_out_date' => $this->check_out_date,
            'status' => $this->status,
            'total_price' => $this->total_price,
            'deposit_amount' => $this->deposit_amount,
            'id_document_type' => $this->id_document_type,
            'id_document_number' => $this->id_document_number,
            'guests_count' => $this->guests_count,
            'guests' => GuestResource::collection($this->whenLoaded('guests')),
            'actions' => BookingActionResource::collection($this->whenLoaded('actions')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
