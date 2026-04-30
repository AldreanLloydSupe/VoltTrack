<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;
use App\Models\Meter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class AdminPropertyController extends Controller
{
    public function list(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $query = Property::query()->with(['user', 'meters']);

        if ($request->filled('unit_type') && in_array($request->unit_type, ['Residential', 'Commercial'], true)) {
            $query->where('unit_type', $request->unit_type);
        }

        if ($request->filled('status') && in_array($request->status, ['Active', 'Inactive', 'Archived'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($builder) use ($search) {
                $builder->where('property_unit_id', 'like', "%{$search}%")
                    ->orWhere('physical_address', 'like', "%{$search}%")
                    ->orWhere('cluster_housing', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                    });
            });
        }

        $properties = $query
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $totalAssets = Property::count();

        return view('admin.property', [
            'properties' => $properties,
            'totalAssets' => $totalAssets,
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $property = Property::with(['user', 'meters'])
            ->findOrFail($id);

        return view('admin.propertyInfo', [
            'property' => $property,
        ]);
    }

    public function create(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        return view('admin.createnew', [
            'approvedResidents' => $this->approvedResidents(),
        ]);
    }

    public function edit(Request $request, $id)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $property = Property::with(['user', 'meters'])->findOrFail($id);

        return view('admin.updateproperty', [
            'property' => $property,
            'approvedResidents' => $this->approvedResidents(),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $validated = $request->validate([
            'property_unit_id' => ['required', 'string', 'max:255', 'unique:properties,property_unit_id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'cluster_housing' => ['nullable', 'string', 'max:255'],
            'physical_address' => ['required', 'string', 'max:255'],
            'unit_type' => ['required', 'in:Residential,Commercial'],
            'lease_commencement_date' => ['nullable', 'date'],
            'status' => ['required', 'in:Active,Inactive,Archived'],
            'electric_serial_number' => ['nullable', 'string', 'max:255', 'unique:meters,serial_number'],
            'electric_initial_reading' => ['nullable', 'numeric', 'min:0'],
            'water_serial_number' => ['nullable', 'string', 'max:255', 'unique:meters,serial_number'],
            'water_initial_reading' => ['nullable', 'numeric', 'min:0'],
        ]);

        if (
            empty($validated['electric_serial_number'])
            && $request->filled('electric_initial_reading')
        ) {
            return back()
                ->withInput()
                ->withErrors(['electric_serial_number' => 'Electric serial number is required when adding an electricity meter.']);
        }

        if (
            empty($validated['water_serial_number'])
            && $request->filled('water_initial_reading')
        ) {
            return back()
                ->withInput()
                ->withErrors(['water_serial_number' => 'Water serial number is required when adding a water meter.']);
        }

        if (! empty($validated['user_id']) && Schema::hasColumn('users', 'status')) {
            $selectedResident = User::find($validated['user_id']);
            if (! $selectedResident || $selectedResident->status !== 'approved') {
                return back()
                    ->withInput()
                    ->withErrors(['user_id' => 'Only approved residents can be assigned to a property.']);
            }
        }

        $property = DB::transaction(function () use ($validated) {
            $property = Property::create([
                'user_id' => $validated['user_id'] ?? null,
                'property_unit_id' => $validated['property_unit_id'],
                'physical_address' => $validated['physical_address'],
                'unit_type' => $validated['unit_type'],
                'cluster_housing' => $validated['cluster_housing'] ?? null,
                'lease_commencement_date' => $validated['lease_commencement_date'] ?? null,
                'status' => $validated['status'],
            ]);

            if (! empty($validated['electric_serial_number'])) {
                $electricMeter = Meter::create([
                    'serial_number' => $validated['electric_serial_number'],
                    'utility_type' => 'Electricity',
                    'hardware_meter_number' => null,
                    'status' => 'Active',
                ]);

                $property->meters()->attach($electricMeter->id, [
                    'initial_reading' => (float) ($validated['electric_initial_reading'] ?? 0),
                    'unit' => 'kWh',
                    'status' => 'Assigned',
                    'assignment_date' => now()->toDateString(),
                ]);
            }

            if (! empty($validated['water_serial_number'])) {
                $waterMeter = Meter::create([
                    'serial_number' => $validated['water_serial_number'],
                    'utility_type' => 'Water',
                    'hardware_meter_number' => null,
                    'status' => 'Active',
                ]);

                $property->meters()->attach($waterMeter->id, [
                    'initial_reading' => (float) ($validated['water_initial_reading'] ?? 0),
                    'unit' => 'm3',
                    'status' => 'Assigned',
                    'assignment_date' => now()->toDateString(),
                ]);
            }

            return $property;
        });

        return redirect()
            ->route('admin.propertyInfo', $property->id)
            ->with('success', 'Property created and assigned successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $property = Property::with('meters')->findOrFail($id);
        $electricMeter = $property->meters->firstWhere('utility_type', 'Electricity');
        $waterMeter = $property->meters->firstWhere('utility_type', 'Water');

        $validated = $request->validate([
            'property_unit_id' => ['required', 'string', 'max:255', Rule::unique('properties', 'property_unit_id')->ignore($property->id)],
            'user_id' => ['nullable', 'exists:users,id'],
            'cluster_housing' => ['nullable', 'string', 'max:255'],
            'physical_address' => ['required', 'string', 'max:255'],
            'unit_type' => ['required', 'in:Residential,Commercial'],
            'lease_commencement_date' => ['nullable', 'date'],
            'status' => ['required', 'in:Active,Inactive,Archived'],
            'electric_serial_number' => ['nullable', 'string', 'max:255', Rule::unique('meters', 'serial_number')->ignore($electricMeter?->id)],
            'electric_initial_reading' => ['nullable', 'numeric', 'min:0'],
            'water_serial_number' => ['nullable', 'string', 'max:255', Rule::unique('meters', 'serial_number')->ignore($waterMeter?->id)],
            'water_initial_reading' => ['nullable', 'numeric', 'min:0'],
        ]);

        if (empty($validated['electric_serial_number']) && $request->filled('electric_initial_reading') && ! $electricMeter) {
            return back()
                ->withInput()
                ->withErrors(['electric_serial_number' => 'Electric serial number is required when adding an electricity meter.']);
        }

        if (empty($validated['water_serial_number']) && $request->filled('water_initial_reading') && ! $waterMeter) {
            return back()
                ->withInput()
                ->withErrors(['water_serial_number' => 'Water serial number is required when adding a water meter.']);
        }

        if (! empty($validated['user_id']) && Schema::hasColumn('users', 'status')) {
            $selectedResident = User::find($validated['user_id']);
            if (! $selectedResident || $selectedResident->status !== 'approved') {
                return back()
                    ->withInput()
                    ->withErrors(['user_id' => 'Only approved residents can be assigned to a property.']);
            }
        }

        DB::transaction(function () use ($property, $validated, $electricMeter, $waterMeter) {
            $property->update([
                'user_id' => $validated['user_id'] ?? null,
                'property_unit_id' => $validated['property_unit_id'],
                'physical_address' => $validated['physical_address'],
                'unit_type' => $validated['unit_type'],
                'cluster_housing' => $validated['cluster_housing'] ?? null,
                'lease_commencement_date' => $validated['lease_commencement_date'] ?? null,
                'status' => $validated['status'],
            ]);

            $this->upsertMeterAssignment(
                $property,
                $electricMeter,
                'Electricity',
                $validated['electric_serial_number'] ?? null,
                $validated['electric_initial_reading'] ?? null,
                'kWh'
            );

            $this->upsertMeterAssignment(
                $property,
                $waterMeter,
                'Water',
                $validated['water_serial_number'] ?? null,
                $validated['water_initial_reading'] ?? null,
                'm3'
            );
        });

        return redirect()
            ->route('admin.propertyInfo', $property->id)
            ->with('success', 'Property details updated successfully.');
    }

public function destroy(Request $request, $id)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $property = Property::with('meters')->findOrFail($id);
        $propertyLabel = $property->property_unit_id ?: "#{$property->id}";

        // Collect meter IDs and serial numbers before deletion
        $meters = $property->meters;
        $meterIds = $meters->pluck('id')->toArray();
        $meterSerialNumbers = $meters->pluck('serial_number')->filter()->toArray();

        DB::transaction(function () use ($property, $meterIds) {
            // 1. Permanently delete the meters assigned to this property
            if (!empty($meterIds)) {
                Meter::whereIn('id', $meterIds)->forceDelete();
            }

            // 2. Detach relationships from the pivot table (this is already a permanent action)
            $property->meters()->detach();

            // 3. Permanently delete the property itself
            $property->forceDelete();
        });

        return redirect()
            ->route('admin.property')
            ->with('success', "Property {$propertyLabel} and associated meters deleted successfully.");
    }

    protected function approvedResidents()
    {
        return User::query()
            ->where('role', 'renter')
            ->when(
                Schema::hasColumn('users', 'status'),
                fn ($query) => $query->where('status', 'approved')
            )
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get(['id', 'first_name', 'last_name', 'email']);
    }

    protected function upsertMeterAssignment(
        Property $property,
        ?Meter $meter,
        string $utilityType,
        ?string $serialNumber,
        mixed $initialReading,
        string $unit
    ): void {
        $serialNumber = filled($serialNumber) ? trim($serialNumber) : null;

        if ($meter) {
            if ($serialNumber !== null) {
                $meter->update(['serial_number' => $serialNumber]);
            }

            if ($initialReading !== null) {
                $property->meters()->updateExistingPivot($meter->id, [
                    'initial_reading' => (float) $initialReading,
                    'unit' => $unit,
                    'status' => 'Assigned',
                    'assignment_date' => $meter->pivot?->assignment_date ?? now()->toDateString(),
                ]);
            }

            return;
        }

        if ($serialNumber === null) {
            return;
        }

        $newMeter = Meter::create([
            'serial_number' => $serialNumber,
            'utility_type' => $utilityType,
            'hardware_meter_number' => null,
            'status' => 'Active',
        ]);

        $property->meters()->attach($newMeter->id, [
            'initial_reading' => (float) ($initialReading ?? 0),
            'unit' => $unit,
            'status' => 'Assigned',
            'assignment_date' => now()->toDateString(),
        ]);
    }
}
