<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ForgotPasswordController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $user = User::query()
            ->where('email', $validated['email'])
            ->first();

        if (! $user || ! $user->isResident()) {
            return back()->withErrors([
                'email' => 'We could not find a resident account with that email.',
            ])->withInput($request->only('email'));
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'remember_token' => null,
        ])->save();

        return redirect()->route('login')->with('success', 'Password reset successful. You can now sign in.');
    }

    public function edit()
    {
        return redirect()->route('password.request');
    }

    public function update()
    {
        return redirect()->route('password.request');
    }
}
