<?php

namespace App\Http\Requests\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_type_id' => ['required', 'uuid', 'exists:rooms_type,id'],
            'room_number' => ['required', 'string', 'max:20', 'unique:rooms,room_number'],
            'status' => ['nullable', 'in:available,occupied,maintenance,cleaning'],
            'floor' => ['required', 'integer', 'min:1'],
        ];
    }
}
