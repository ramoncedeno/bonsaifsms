<?php

use App\Http\Controllers\SmsImportController;
use App\Http\Controllers\SmsTransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Routes user import
    Route::get('/import-users', [UserController::class, 'viewimportform'])->name('import.users'); // view form
    Route::post('/import-users', [UserController::class, 'requestimportform'])->name('import.users'); //Import file from form


    Route::get('/send-sms', [SmsTransactionController::class, 'showForm'])->name('sms.show');
    Route::get('/send-sms/{phone}/{message}', [SmsTransactionController::class, 'sendSMS'])->name('sms.send');
    Route::post('/send-sms/{phone}/{message}', [SmsTransactionController::class, 'sendSMS'])->name('sms.send');

    Route::get('/import-sms', [SmsImportController::class, 'showImportForm'])->name('sms.import.form');
    Route::post('/import-sms', [SmsImportController::class, 'import'])->name('sms.import');
});
