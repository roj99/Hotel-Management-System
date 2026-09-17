<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLostFoundItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_description' => ['sometimes', 'string'],
            'storage_location' => ['sometimes', 'nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'in:stored,returned,disposed'],
            'returned_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
