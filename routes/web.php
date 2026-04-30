<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\AdminResidentController;
use App\Http\Controllers\AdminPropertyController;
use App\Http\Controllers\AdminBillingController;
use App\Http\Controllers\AdminUtilityController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(
            auth()->user()->isAdmin() ? 'admin.dashboard' : 'resident.dashboard'
        );
    }

    return view('login');
})->middleware('no.cache')->name('login');

Route::get('/register', function () {
    return view('register');
})->middleware('no.cache')->name('register');

Route::get('/admin/residentList', [AdminResidentController::class, 'list'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.residentList');

Route::get('/admin/pending', [AdminUtilityController::class, 'pending'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.pending');

Route::post('/admin/pending/{id}/approve', [AdminUtilityController::class, 'approveResident'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.pending.approve');

Route::post('/admin/pending/{id}/reject', [AdminUtilityController::class, 'rejectResident'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.pending.reject');

Route::get('/admin/confirming/{id}', [AdminUtilityController::class, 'confirming'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.confirming');

Route::get('/admin/createnew', [AdminPropertyController::class, 'create'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.createnew');
Route::post('/admin/createnew', [AdminPropertyController::class, 'store'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.createnew.store');

Route::get('/admin/property', [AdminPropertyController::class, 'list'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.property');

Route::get('/admin/residentInfo/{id}', [AdminResidentController::class, 'show'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.residentInfo');
Route::get('/admin/resident/{id}/edit', [AdminResidentController::class, 'edit'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.resident.edit');
Route::patch('/admin/resident/{id}', [AdminResidentController::class, 'update'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.resident.update');
Route::delete('/admin/resident/{id}', [AdminResidentController::class, 'destroy'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.resident.destroy');

Route::get('/admin/billingHistory', [AdminBillingController::class, 'list'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.billingHistory');
Route::patch('/admin/bills/{bill}/status', [AdminBillingController::class, 'updateStatus'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.bills.updateStatus');

Route::get('/admin/propertyInfo/{id}', [AdminPropertyController::class, 'show'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.propertyInfo');
Route::get('/admin/property/{id}/edit', [AdminPropertyController::class, 'edit'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.property.edit');
Route::patch('/admin/property/{id}', [AdminPropertyController::class, 'update'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.property.update');
Route::delete('/admin/property/{id}', [AdminPropertyController::class, 'destroy'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.property.destroy');

Route::get('/admin/receipt', function () {
    return view('admin.receipt');
})->name('admin.receipt');

// Creating Page
Route::get('/admin/Create/createNewWaterBill', [AdminBillingController::class, 'createWaterBill'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.Create.createNewWaterBill');
Route::post('/admin/Create/createNewWaterBill', [AdminBillingController::class, 'storeWaterBill'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.Create.storeNewWaterBill');

Route::get('/admin/Create/createNewElectricityBill', [AdminBillingController::class, 'createElectricityBill'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.Create.createNewElectricityBill');
Route::post('/admin/Create/createNewElectricityBill', [AdminBillingController::class, 'storeElectricityBill'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.Create.storeNewElectricityBill');

Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
    ->middleware(['auth', 'no.cache'])
    ->name('admin.dashboard');

Route::get('/resident/dashboard', [ResidentController::class, 'dashboard'])
    ->middleware(['auth', 'no.cache'])
    ->name('resident.dashboard');

Route::get('/resident/payment-history', [ResidentController::class, 'history'])
    ->middleware(['auth', 'no.cache'])
    ->name('resident.history');

Route::get('/resident/settings', [ResidentController::class, 'settings'])
    ->middleware(['auth', 'no.cache'])
    ->name('resident.settings');
Route::patch('/resident/settings', [ResidentController::class, 'updateSettings'])
    ->middleware(['auth', 'no.cache'])
    ->name('resident.settings.update');
Route::get('/resident/contact-admin', [ResidentController::class, 'contactAdmin'])
    ->middleware(['auth', 'no.cache'])
    ->name('resident.contactAdmin');
Route::post('/resident/contact-admin', [ResidentController::class, 'sendContactAdmin'])
    ->middleware(['auth', 'no.cache'])
    ->name('resident.contactAdmin.send');
