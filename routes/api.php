<?php

use App\Http\Controllers\SmsTransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/send-sms/{phone}/{message}', [SmsTransactionController::class, 'sendSMS'])->name('sms.send');

// Routes user import
Route::get('/import-users', [UserController::class, 'viewimportform'])->name('import.users'); // view form
Route::post('/import-users', [UserController::class, 'requestimportform'])->name('import.users'); //Import file from form
