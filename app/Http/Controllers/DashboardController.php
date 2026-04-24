<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        return view('admin.dashboard');
    }
}
