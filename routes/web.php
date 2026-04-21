<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResidentController;

Route::get('/', function () {
    return view('login');
});

// Resident Account Update Routes
Route::get('/residents/{id}/edit', [ResidentController::class, 'edit'])->name('residents.edit');
Route::put('/residents/{id}', [ResidentController::class, 'update'])->name('residents.update');
