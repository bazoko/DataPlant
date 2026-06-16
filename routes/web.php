<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InspeccionController;
use App\Http\Controllers\MedicionController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/mediciones', [MedicionController::class, 'index'])->name('mediciones.index');
    Route::get('/mediciones/create', [MedicionController::class, 'create'])
        ->middleware('role:admin,carga')
        ->name('mediciones.create');
    Route::post('/mediciones', [MedicionController::class, 'store'])
        ->middleware('role:admin,carga')
        ->name('mediciones.store');
    Route::get('/mediciones/{medicion}/edit', [MedicionController::class, 'edit'])
        ->middleware('role:admin')
        ->name('mediciones.edit');
    Route::put('/mediciones/{medicion}', [MedicionController::class, 'update'])
        ->middleware('role:admin')
        ->name('mediciones.update');
    Route::delete('/mediciones/{medicion}', [MedicionController::class, 'destroy'])
        ->middleware('role:admin')
        ->name('mediciones.destroy');

    Route::get('/inspecciones', [InspeccionController::class, 'index'])->name('inspecciones.index');
    Route::get('/inspecciones/create', [InspeccionController::class, 'create'])
        ->middleware('role:admin,carga')
        ->name('inspecciones.create');
    Route::post('/inspecciones', [InspeccionController::class, 'store'])
        ->middleware('role:admin,carga')
        ->name('inspecciones.store');
    Route::get('/inspecciones/{inspeccion}/edit', [InspeccionController::class, 'edit'])
        ->middleware('role:admin')
        ->name('inspecciones.edit');
    Route::put('/inspecciones/{inspeccion}', [InspeccionController::class, 'update'])
        ->middleware('role:admin')
        ->name('inspecciones.update');
    Route::delete('/inspecciones/{inspeccion}', [InspeccionController::class, 'destroy'])
        ->middleware('role:admin')
        ->name('inspecciones.destroy');

    Route::middleware('role:admin')->group(function (): void {
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    });
});
