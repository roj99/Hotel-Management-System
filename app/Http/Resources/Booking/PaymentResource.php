<?php

namespace App\Http\Resources\Booking;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_id' => $this->booking_id,
            'invoice_id' => $this->invoice_id,
            'amount' => $this->amount,
            'type' => $this->type,
            'method' => $this->method,
            'paid_at' => $this->paid_at,
            'created_at' => $this->created_at,
        ];
    }
}
