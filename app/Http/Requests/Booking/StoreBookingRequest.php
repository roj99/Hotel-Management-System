<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'uuid', 'exists:users,id'],
            'check_in_date' => ['required', 'date'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'status' => ['nullable', 'in:pending,confirmed,checked_in,checked_out,cancelled'],
            'total_price' => ['required', 'numeric', 'min:0'],
            'deposit_amount' => ['nullable', 'numeric', 'min:0'],
            'id_document_type' => ['nullable', 'in:passport,national_id'],
            'id_document_number' => ['nullable', 'string', 'max:50'],
            'guests_count' => ['required', 'integer', 'min:1'],
            //ارسال رقم الغرفة بشكل مصفوفة
            'room_ids' => ['required', 'array', 'min:1'],
            //فحص كل غرفة داخل المصفوفة
            'room_ids.*' => ['uuid', 'exists:rooms,id'],
        ];
    }
}
