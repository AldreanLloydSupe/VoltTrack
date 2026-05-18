<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

/**
 * Handles RegisterController responsibilities.
 */
class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => [
                'required',
                'digits:10',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (User::where('phone_number', '+63'.$value)->exists()) {
                        $fail('The phone number has already been taken.');
                    }
                },
            ],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'agree_terms' => ['required', 'accepted'],
        ]);

        $userData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone_number' => '+63'.$validated['phone'],
            'email' => $validated['email'],
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
