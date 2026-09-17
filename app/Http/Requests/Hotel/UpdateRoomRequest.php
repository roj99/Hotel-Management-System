<?php

namespace App\Http\Requests\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_type_id' => ['sometimes', 'uuid', 'exists:rooms_type,id'],
            'room_number' => ['sometimes', 'string', 'max:20', 'unique:rooms,room_number,' . $this->route('room')],
            'status' => ['sometimes', 'in:available,occupied,maintenance,cleaning'],
            'floor' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
