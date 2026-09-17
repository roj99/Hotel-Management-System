<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_id' => ['required', 'uuid', 'exists:bookings,id'],
            'full_name' => ['required', 'string', 'max:255'],
            'id_document_type' => ['nullable', 'in:passport,national_id'],
            'id_document_number' => ['nullable', 'string', 'max:50'],
        ];
    }
}
