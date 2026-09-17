<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_id' => ['required', 'uuid', 'exists:bookings,id'],
            'action_type' => ['required', 'in:reviewed,cancelled'],
            'performed_by' => ['nullable', 'uuid', 'exists:users,id'],
            'reason' => ['nullable', 'string', 'required_if:action_type,cancelled'],
        ];
    }
}
