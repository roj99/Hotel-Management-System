<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'uuid', 'exists:users,id'],
            'shift_date' => ['required', 'date'],
            'shift_start' => ['required', 'date'],
            'shift_end' => ['required', 'date', 'after:shift_start'],
            'status' => ['nullable', 'in:scheduled,completed,missed'],
        ];
    }
}
