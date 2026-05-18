<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Manages viewing and restoring soft-deleted residents and properties.
 */
class AdminDeletedRecordController extends Controller
{
    /**
     * Shows deleted residents and properties in separate paginated lists.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $deletedResidents = User::onlyTrashed()
            ->where('role', 'renter')
            ->orderByDesc('deleted_at')
            ->paginate(10, ['*'], 'residents_page')
            ->withQueryString();

        $deletedProperties = Property::onlyTrashed()
            ->with([
                'user' => fn ($query) => $query->withTrashed(),
                'meters',
            ])
            ->orderByDesc('deleted_at')
            ->paginate(10, ['*'], 'properties_page')
            ->withQueryString();

        return view('admin.deletedRecords', [
            'deletedResidents' => $deletedResidents,
            'deletedProperties' => $deletedProperties,
        ]);
    }

    /**
     * Restores a soft-deleted resident account and writes an audit trail.
     */
    public function restoreResident(Request $request, int $id)
    {
        $admin = $request->user();

        abort_unless($admin && $admin->isAdmin(), 403);

        $resident = User::onlyTrashed()
            ->where('role', 'renter')
            ->findOrFail($id);

        $residentName = trim("{$resident->first_name} {$resident->last_name}");
        $resident->restore();

        AuditLogger::log(
            $admin,
            'resident_restored',
            "Restored resident account for {$residentName}.",
            [
                'resident_id' => $resident->id,
                'resident_name' => $residentName,
            ],
            'deleted_records',
            $request
        );

        return back()->with('success', "{$residentName} has been restored.");
    }

    /**
     * Restores a soft-deleted property and safely validates resident assignment.
     */
    public function restoreProperty(Request $request, int $id)
    {
        $admin = $request->user();

        abort_unless($admin && $admin->isAdmin(), 403);

        $property = Property::onlyTrashed()
            ->with(['user' => fn ($query) => $query->withTrashed()])
            ->findOrFail($id);

        $propertyLabel = $property->property_unit_id ?: "#{$property->id}";
        $restoredAssignedResidentId = $property->user_id;
        $wasUnassignedDuringRestore = false;

        DB::transaction(function () use ($property, &$restoredAssignedResidentId, &$wasUnassignedDuringRestore) {
            // Keep assignment only when resident exists, is active, and has no other property.
            $assignedResident = $property->user;
            $assignedResidentUnavailable = ! $assignedResident || $assignedResident->trashed();
            $assignedResidentHasOtherProperty = $restoredAssignedResidentId
                ? Property::query()
                    ->where('user_id', $restoredAssignedResidentId)
                    ->whereKeyNot($property->id)
                    ->exists()
                : false;

            if ($assignedResidentUnavailable || $assignedResidentHasOtherProperty) {
                $restoredAssignedResidentId = null;
                $wasUnassignedDuringRestore = true;
            }

            $property->user_id = $restoredAssignedResidentId;
            $property->status = $restoredAssignedResidentId ? 'Active' : 'Inactive';
            $property->restore();
            $property->save();
        });

        AuditLogger::log(
            $admin,
            'property_restored',
            "Restored property {$propertyLabel}.",
            [
                'property_id' => $property->id,
                'property_unit_id' => $property->property_unit_id,
                'assigned_resident_id' => $restoredAssignedResidentId,
                'unassigned_during_restore' => $wasUnassignedDuringRestore,
            ],
            'deleted_records',
            $request
        );

        $message = "Property {$propertyLabel} has been restored.";

        if ($wasUnassignedDuringRestore) {
            // Explain why the property was restored without resident assignment.
            $message .= ' It was restored as unassigned because the previous resident is unavailable or already assigned elsewhere.';
        }

        return back()->with('success', $message);
    }
}
