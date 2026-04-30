<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class AdminResidentController extends Controller
{
    public function list(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        Bill::markPastDueAsOverdue();

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

        Bill::markPastDueAsOverdue();

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

    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $resident = User::query()
            ->where('role', 'renter')
            ->findOrFail($id);

        DB::transaction(function () use ($resident) {
            $resident->properties()->update(['user_id' => null]);
            $resident->bills()->delete();
            $resident->utilityAssignments()->delete();
            $resident->adminNotifications()->delete();
            $resident->forceDelete();
        });

        return redirect()
            ->route('admin.residentList')
            ->with('success', 'Resident account deleted permanently.');
    }

    // Sorting Kung residential ang i show or commercial
    public function residentList(Request $request)
    {
        return $this->list($request);
    }
}
