<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    /**
     * Show the form for editing the resident's account.
     */
    public function edit($id)
    {
        $user = User::with('property.meters')->findOrFail($id);

        // Get meters through PropertyMeter junction
        $meters = [];
        if ($user->property) {
            $meters = $user->property->meters()->get();
        }

        return view('residents.edit', compact('user', 'meters'));
    }

    /**
     * Update the resident's account.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|unique:users,phone_number,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'gender' => 'required|in:Male,Female',
        ]);

        $user->update($validated);

        return redirect()->route('residents.edit', $user->id)
            ->with('success', 'Account updated successfully!');
    }
}
