<?php
// routes/api.php

use App\Http\Controllers\TestResultController;
use Illuminate\Support\Facades\Route;

Route::get('/resultados', [TestResultController::class, 'index']);
Route::get('/estadisticas', [TestResultController::class, 'estadisticas']);
Route::get('/resultados/{id}', [TestResultController::class, 'show']);
Route::post('/guardar-resultado', [TestResultController::class, 'store']);
Route::delete('/resultados/{id}', [TestResultController::class, 'destroy']);