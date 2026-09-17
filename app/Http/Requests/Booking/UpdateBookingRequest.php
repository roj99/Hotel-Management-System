<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'check_in_date' => ['sometimes', 'date'],
            'check_out_date' => ['sometimes', 'date', 'after:check_in_date'],
            'status' => ['sometimes', 'in:pending,confirmed,checked_in,checked_out,cancelled'],
            'total_price' => ['sometimes', 'numeric', 'min:0'],
            'deposit_amount' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'id_document_type' => ['sometimes', 'nullable', 'in:passport,national_id'],
            'id_document_number' => ['sometimes', 'nullable', 'string', 'max:50'],
            'guests_count' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
