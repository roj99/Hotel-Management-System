<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_id' => ['sometimes', 'nullable', 'uuid', 'exists:invoices,id'],
            'amount' => ['sometimes', 'numeric', 'min:0'],
            'type' => ['sometimes', 'in:deposit,full_payment,refund'],
            'method' => ['sometimes', 'in:cash,card,bank_transfer'],
        ];
    }
}
