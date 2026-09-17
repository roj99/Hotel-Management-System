<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_id' => ['required', 'uuid', 'exists:bookings,id'],
            'invoice_id' => ['nullable', 'uuid', 'exists:invoices,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'type' => ['required', 'in:deposit,full_payment,refund'],
            'method' => ['required', 'in:cash,card,bank_transfer'],
        ];
    }
}
