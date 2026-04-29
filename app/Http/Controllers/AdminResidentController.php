<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class AdminResidentController extends Controller
{
    public function list(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $unitType = $request->input('unit_type');
        $search = trim((string) $request->input('search'));

        $residents = User::where('role', 'renter')
            ->when(
                Schema::hasColumn('users', 'status'),
                fn ($query) => $query->where('status', 'approved')
            )
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $query->where(function ($searchQuery) use ($search) {
                        $searchQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                    });
                }
            )
            ->when(
                filled($unitType) && $unitType !== 'All',
                fn ($query) => $query->whereHas(
                    'property',
                    fn ($propertyQuery) => $propertyQuery->where('unit_type', $unitType)
                )
            )
            ->with(['property', 'bills'])
            ->get();

        return view('admin.residentList', [
            'residents' => $residents,
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $resident = User::with(['properties', 'bills'])
            ->findOrFail($id);

        return view('admin.residentInfo', [
            'resident' => $resident,
        ]);
    }

    public function edit(Request $request, $id)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $resident = User::with(['property', 'properties'])->findOrFail($id);

        return view('admin.Update.updateResident', [
            'resident' => $resident,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $resident = User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255', Rule::unique('users', 'phone_number')->ignore($resident->id)],
            'house_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($resident->id)],
            'gender' => ['required', Rule::in(['Male', 'Female'])],
        ]);

        $resident->update($validated);

        return redirect()
            ->route('admin.residentInfo', $resident->id)
            ->with('success', 'Resident account updated successfully.');
    }

    // Sorting Kung residential ang i show or commercial
    public function residentList(Request $request)
    {
        return $this->list($request);
    }
}
