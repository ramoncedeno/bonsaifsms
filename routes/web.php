<?php

use App\Http\Controllers\SmsImportController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

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
    Route::get('/sms/view',[SmsImportController::class,'index_smsview'])->name('sms.reg.view');
});
