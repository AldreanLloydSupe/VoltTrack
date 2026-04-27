<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        $user = Auth::user();

        // Official admin accounts do not require approval.
        if (! $user->isAdmin() && $user->status === 'pending') {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Your account is pending admin approval. Please wait for approval before logging in.',
            ]);
        }

        if (! $user->isAdmin() && $user->status === 'rejected') {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Your account registration was rejected. Please contact support.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route(
            $request->user()->isAdmin() ? 'admin.dashboard' : 'resident.dashboard'
        );
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}
