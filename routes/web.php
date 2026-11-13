<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;

// Mostrar login
Route::get('/', [AuthController::class, 'mostrarLogin'])
    ->name('login');

// Procesar login
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.procesar');

// Mostrar pantalla de verificación
Route::get('/verificar-codigo/{usuario}', [AuthController::class, 'mostrarVerificacion'])
    ->middleware('signed')
    ->name('verificar.codigo');

// Procesar el código ingresado
Route::post('/verificar-codigo/{usuario}', [AuthController::class, 'verificarCodigo'])
    ->middleware('signed')
    ->name('verificar.codigo.procesar');

// Página principal
Route::get('/principal', function () {
    return view('principal');
})->middleware('auth')->name('principal');

// Cerrar sesión
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Crear usuario
Route::get('/usuarios/crear', [UsuarioController::class, 'crear'])
    ->name('usuarios.crear');