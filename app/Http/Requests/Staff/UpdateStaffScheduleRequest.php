<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shift_date' => ['sometimes', 'date'],
            'shift_start' => ['sometimes', 'date'],
            'shift_end' => ['sometimes', 'date', 'after:shift_start'],
            'status' => ['sometimes', 'in:scheduled,completed,missed'],
        ];
    }
}
