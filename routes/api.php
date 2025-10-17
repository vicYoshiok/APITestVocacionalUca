<?php
// routes/api.php
/*
|
use App\Http\Controllers\TestResultController;
use Illuminate\Support\Facades\Route;

Route::get('/resultados', [TestResultController::class, 'index']);
Route::get('/estadisticas', [TestResultController::class, 'estadisticas']);
Route::get('/resultados/{id}', [TestResultController::class, 'show']);
Route::post('/guardar-resultado', [TestResultController::class, 'store']);
Route::delete('/resultados/{id}', [TestResultController::class, 'destroy']);
|*/

use App\Http\Controllers\TestResultController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Rutas públicas (para el test desde React)
Route::post('/guardar-resultado', [TestResultController::class, 'store']);

// Autenticación de administrador
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']); // opcional

// Rutas protegidas (solo usuarios autenticados con Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/resultados', [TestResultController::class, 'index']);
    Route::get('/estadisticas', [TestResultController::class, 'estadisticas']);
    Route::get('/resultados/{id}', [TestResultController::class, 'show']);
    Route::delete('/resultados/{id}', [TestResultController::class, 'destroy']);

    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout']);
});
