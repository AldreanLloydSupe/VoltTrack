<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate the inputs from your Create Account form
        $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|string|unique:users',
            'phone_number' => 'required|unique:users',
            'house_number' => 'required',
            'gender'       => 'required',
            'password'     => 'required|min:4|confirmed', // 'confirmed' looks for password_confirmation field
        ]);

        // 2. Create the Client in the database using an array
        $user = User::create([
            'first_name'   => $request->first_name,
            'last_name'    => $request->last_name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'house_number' => $request->house_number,
            'gender'       => $request->gender,
            'password'     => Hash::make($request->password),
            'role'         => 'renter', // Hardcoded as 'renter' for client registration
        ]);

        // 3. Automatically login the client immediately after saving
        Auth::login($user);

        // 4. Redirect to the Client Dashboard
        return redirect()->route('client.dashboard');
    }
}