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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('/resultados', [TestResultController::class, 'index']);
Route::get('/estadisticas', [TestResultController::class, 'estadisticas']);
Route::get('/resultados/{id}', [TestResultController::class, 'show']);
Route::post('/guardar-resultado', [TestResultController::class, 'store']);
Route::delete('/resultados/{id}', [TestResultController::class, 'destroy']);
Route::post('/register-admin', [AuthController::class, 'registerAdmin']);
Route::get('admin/users', [AuthController::class, 'getAdmins']);
Route::delete('admin/users/{id}', [AuthController::class, 'deleteAdmin']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
    //Route::post('/register-admin', [AuthController::class, 'registerAdmin']);
});
