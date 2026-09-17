<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // اختياري الآن: لو موجود، لازم الحجز يكون فعلاً خلص (checked_out) -
            // هاد التحقق موجود بـ ReviewController::store(). لو مش موجود،
            // الـ review بيصير عام (غير مرتبط بإقامة محددة).
            'booking_id' => ['nullable', 'uuid', 'exists:bookings,id'],
            'user_id' => ['required', 'uuid', 'exists:users,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string'],
        ];
    }
}
