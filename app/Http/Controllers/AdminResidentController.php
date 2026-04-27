<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminResidentController extends Controller
{
    public function list(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $residents = User::where('role', 'renter')
            ->when(
                Schema::hasColumn('users', 'status'),
                fn ($query) => $query->where('status', 'approved')
            )
            ->with(['properties', 'bills'])
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
}
