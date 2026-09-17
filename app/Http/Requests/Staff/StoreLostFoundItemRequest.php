<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class StoreLostFoundItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_id' => ['required', 'uuid', 'exists:rooms,id'],
            'found_by' => ['required', 'uuid', 'exists:users,id'],
            'item_description' => ['required', 'string'],
            'storage_location' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:stored,returned,disposed'],
            'returned_at' => ['nullable', 'date'],
        ];
    }
}
