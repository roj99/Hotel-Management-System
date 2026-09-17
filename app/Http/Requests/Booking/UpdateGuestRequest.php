<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGuestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['sometimes', 'string', 'max:255'],
            'id_document_type' => ['sometimes', 'nullable', 'in:passport,national_id'],
            'id_document_number' => ['sometimes', 'nullable', 'string', 'max:50'],
        ];
    }
}
