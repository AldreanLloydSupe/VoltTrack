<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResidentController;

Route::get('/', function () {
    return view('login');
});
Route::get('/register', function () {
    return view('register');
});
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

// Resident Account Update Routes
Route::get('/residents/{id}/edit', [ResidentController::class, 'edit'])->name('residents.edit');
Route::put('/residents/{id}', [ResidentController::class, 'update'])->name('residents.update');
