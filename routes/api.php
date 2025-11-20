<?php

use App\Http\Controllers\SmsImportController;
use App\Http\Controllers\SmsSenderController;
use App\Http\Controllers\SmsTransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});

Route::middleware('auth:sanctum')->post('/app/send-sms/{phone}/{message}', [SmsSenderController::class, 'sendSMS'])->name('app.sms.send'); // Test Controller
