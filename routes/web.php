<?php

use App\Http\Controllers\SmsImportController;
use App\Http\Controllers\SmsTransactionController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';


Route::get('/send-sms', [SmsTransactionController::class, 'showForm'])->name('sms.show')
->middleware(['auth', 'verified']);

Route::get('/import-sms', [SmsImportController::class, 'showImportForm'])->name('sms.import.form')
->middleware(['auth', 'verified']);
