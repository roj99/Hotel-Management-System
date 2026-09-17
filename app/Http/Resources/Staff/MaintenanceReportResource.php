<?php

namespace App\Http\Resources\Staff;

use App\Http\Resources\Hotel\RoomResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'room' => new RoomResource($this->whenLoaded('room')),
            'reported_by' => new UserResource($this->whenLoaded('reportedBy')),
            'issue_description' => $this->issue_description,
            'severity' => $this->severity,
            'status' => $this->status,
            'resolved_by' => new UserResource($this->whenLoaded('resolvedBy')),
            'reported_at' => $this->reported_at,
            'resolved_at' => $this->resolved_at,
        ];
    }
}
