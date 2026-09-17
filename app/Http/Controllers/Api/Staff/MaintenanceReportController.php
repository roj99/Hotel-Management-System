<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreMaintenanceReportRequest;
use App\Http\Requests\Staff\UpdateMaintenanceReportRequest;
use App\Http\Resources\Staff\MaintenanceReportResource;
use App\Models\Staff\MaintenanceReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaintenanceReportController extends Controller
{
    public function index(): JsonResponse
    {
        $maintenanceReports = MaintenanceReport::with(['room', 'reportedBy', 'resolvedBy'])->get();

        return response()->json(MaintenanceReportResource::collection($maintenanceReports));
    }

    /**
     * Any staff member reports an issue - the room is immediately taken
     * out of the available pool by moving it to "maintenance", so the
     * front desk can no longer check a guest into it.
     */
    public function store(StoreMaintenanceReportRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['reported_at'] = now();
        $data['status'] = $data['status'] ?? 'open';

        $maintenanceReport = MaintenanceReport::create($data);
        $maintenanceReport->room()->update(['status' => 'maintenance']);

        return response()->json(new MaintenanceReportResource($maintenanceReport), 201);
    }

    public function show(MaintenanceReport $maintenanceReport): JsonResponse
    {
        $maintenanceReport->load(['room', 'reportedBy', 'resolvedBy']);

        return response()->json(new MaintenanceReportResource($maintenanceReport));
    }

    public function update(UpdateMaintenanceReportRequest $request, MaintenanceReport $maintenanceReport): JsonResponse
    {
        $maintenanceReport->update($request->validated());

        return response()->json(new MaintenanceReportResource($maintenanceReport));
    }

    public function destroy(MaintenanceReport $maintenanceReport): JsonResponse
    {
        $maintenanceReport->delete();

        return response()->json(null, 204);
    }

    /**
     * Maintenance staff marks the issue resolved - the room becomes
     * available again for the front desk to use.
     */
    public function resolve(Request $request, MaintenanceReport $maintenanceReport): JsonResponse
    {
        $maintenanceReport->update([
            'status' => 'resolved',
            'resolved_by' => $request->input('resolved_by', auth()->id()),
            'resolved_at' => now(),
        ]);

        $maintenanceReport->room()->update(['status' => 'available']);

        return response()->json(new MaintenanceReportResource($maintenanceReport));
    }
}
