<?php

namespace App\Http\Resources;

use App\Http\Resources\Booking\BookingResource;
use App\Http\Resources\Booking\PaymentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking' => new BookingResource($this->whenLoaded('booking')),
            'room_charge' => $this->room_charge,
            'services_charge' => $this->services_charge,
            'total_amount' => $this->total_amount,
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            'issued_at' => $this->issued_at,
            'created_at' => $this->created_at,
        ];
    }
}
