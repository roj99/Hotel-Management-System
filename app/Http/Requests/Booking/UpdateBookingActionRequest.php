<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action_type' => ['sometimes', 'in:reviewed,cancelled'],
            'performed_by' => ['sometimes', 'nullable', 'uuid', 'exists:users,id'],
            'reason' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
