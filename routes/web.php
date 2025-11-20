<?php

use App\Http\Controllers\SmsImportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HealthCheckController;
use Illuminate\Support\Facades\Route;

Route::get('/health-check', [HealthCheckController::class, 'check'])->name('healthcheck.check');

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', App\Livewire\UserManagement::class)->name('users.index');
    Route::get('/sms-consumption', App\Livewire\SmsConsumptionDashboard::class)->name('sms.consumption');
    Route::view('test-app', 'livewire.layout.test-app')->name('test.app');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'permission:import sms'])->group(function () {
    Route::get('/sms/view', App\Livewire\SmsAttemptView::class)->name('sms.reg.view');
    // The import route is now handled by Livewire, so it's commented out or removed.
});
