<?php

use App\Http\Controllers\SmsImportController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', App\Livewire\UserManagement::class)->name('users.index');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'permission:import sms'])->group(function () {
    Route::get('/sms/view', App\Livewire\SmsAttemptView::class)->name('sms.reg.view');
    // The import route is now handled by Livewire, so it's commented out or removed.
});
