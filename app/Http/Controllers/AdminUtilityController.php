<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UtilityAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminUtilityController extends Controller
{
    public function pending(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $pendingResidents = User::query()
            ->where('role', 'renter')
            ->when(
                Schema::hasColumn('users', 'status'),
                fn ($query) => $query->where('status', 'pending')
            )
            ->latest()
            ->get();

        return view('admin.pending', [
            'pendingResidents' => $pendingResidents,
        ]);
    }

    public function approveResident(Request $request, $id)
    {
        $admin = $request->user();

        abort_unless($admin && $admin->isAdmin(), 403);

        $resident = User::findOrFail($id);

        if (! Schema::hasColumn('users', 'status')) {
            return back()->with('error', 'Status column is missing. Please run database migrations first.');
        }

        $resident->update([
            'role' => 'renter',
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $admin->id,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Resident approved successfully. Dashboard has been updated.');
    }

    public function rejectResident(Request $request, $id)
    {
        $admin = $request->user();

        abort_unless($admin && $admin->isAdmin(), 403);

        $resident = User::findOrFail($id);
        $resident->forceDelete();

        return redirect()
            ->route('admin.pending')
            ->with('success', 'Resident registration rejected and deleted.');
    }

    public function confirming(Request $request, $id)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $assignment = UtilityAssignment::with(['user', 'property.meters'])
            ->findOrFail($id);

        return view('admin.confirming', [
            'assignment' => $assignment,
        ]);
    }
}
