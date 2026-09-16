<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MeasurementController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:dashboard.view')
        ->name('dashboard');

    Route::get('/usuarios', function () {
        return view('users.index', [
            'users' => User::with('roles')->orderBy('name')->get(),
        ]);
    })->middleware('permission:users.manage')->name('users.index');

    Route::get('/mediciones', [MeasurementController::class, 'index'])
        ->middleware('permission:measurements.view')
        ->name('measurements.index');
    Route::get('/mediciones/crear', [MeasurementController::class, 'create'])
        ->middleware('permission:measurements.create')
        ->name('measurements.create');
    Route::post('/mediciones', [MeasurementController::class, 'store'])
        ->middleware('permission:measurements.create')
        ->name('measurements.store');

    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
});
