<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_id' => ['required', 'uuid', 'exists:rooms,id'],
            'reported_by' => ['required', 'uuid', 'exists:users,id'],
            'issue_description' => ['required', 'string'],
            'severity' => ['nullable', 'in:low,medium,high,critical'],
            'status' => ['nullable', 'in:open,in_progress,resolved'],
            'resolved_by' => ['nullable', 'uuid', 'exists:users,id'],
            'resolved_at' => ['nullable', 'date'],
        ];
    }
}
