<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(
            auth()->user()->isAdmin() ? 'admin.dashboard' : 'resident.dashboard'
        );
    }

    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/admin/residentList', function () {
    return view('admin.residentList');
})->name('admin.residentList');

Route::get('/admin/residentInfo', function () {
    return view('admin.residentInfo');
})->name('admin.residentInfo');

Route::get('/admin/billingHistory', function () {
    return view('admin.billingHistory');
})->name('admin.billingHistory');

Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
    ->middleware('auth')
    ->name('admin.dashboard');

Route::get('/resident/dashboard', [DashboardController::class, 'resident'])
    ->middleware('auth')
    ->name('resident.dashboard');
