<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'unique:users,phone_number'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'house_number' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'role' => ['required', Rule::in(['admin', 'resident'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone_number' => $validated['phone'],
            'email' => $validated['email'],
            'house_number' => $validated['house_number'],
            'gender' => $validated['gender'] === 'male' ? 'Male' : 'Female',
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] === 'resident' ? 'renter' : 'admin',
        ]);

        Auth::login($user);

        return redirect()
            ->route('login')
            ->with('success', 'Account created successfully. You can now sign in.');
    }
}
