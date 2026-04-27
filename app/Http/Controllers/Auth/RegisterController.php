<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
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
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'agree_terms' => ['required', 'accepted'],
        ]);

        $userData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone_number' => $validated['phone'],
            'email' => $validated['email'],
            'house_number' => $validated['house_number'],
            'gender' => $validated['gender'] === 'male' ? 'Male' : 'Female',
            'password' => Hash::make($validated['password']),
            'role' => 'renter',
        ];

        if (Schema::hasColumn('users', 'status')) {
            $userData['status'] = 'pending';
        }

        $user = User::create($userData);

        return redirect()
            ->route('login')
            ->with('success', 'Account created successfully! Your registration is pending admin approval. You\'ll be able to log in once approved.');
    }
}
