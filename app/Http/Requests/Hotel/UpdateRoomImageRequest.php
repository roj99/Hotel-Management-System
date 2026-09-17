<?php

namespace App\Http\Requests\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_type_id' => ['sometimes', 'uuid', 'exists:rooms_type,id'],
            'image_path' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
