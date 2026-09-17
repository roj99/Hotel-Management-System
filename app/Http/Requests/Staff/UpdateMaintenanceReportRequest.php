<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaintenanceReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'issue_description' => ['sometimes', 'string'],
            'severity' => ['sometimes', 'in:low,medium,high,critical'],
            'status' => ['sometimes', 'in:open,in_progress,resolved'],
            'resolved_by' => ['sometimes', 'nullable', 'uuid', 'exists:users,id'],
            'resolved_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
