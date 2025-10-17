<?php
// app/Models/TestResult.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido', 
        'correo',
        'telefono',
        'edad',
        'escuela',
        'puntaje_R',
        'puntaje_I',
        'puntaje_A', 
        'puntaje_S',
        'puntaje_E',
        'puntaje_C',
        'porcentaje_R',
        'porcentaje_I',
        'porcentaje_A',
        'porcentaje_S',
        'porcentaje_E', 
        'porcentaje_C',
        'respuestas'
    ];

    protected $casts = [
        'respuestas' => 'array',
        'edad' => 'integer',
        'puntaje_R' => 'integer',
        'puntaje_I' => 'integer',
        'puntaje_A' => 'integer',
        'puntaje_S' => 'integer',
        'puntaje_E' => 'integer',
        'puntaje_C' => 'integer',
        'porcentaje_R' => 'integer',
        'porcentaje_I' => 'integer',
        'porcentaje_A' => 'integer',
        'porcentaje_S' => 'integer',
        'porcentaje_E' => 'integer',
        'porcentaje_C' => 'integer'
    ];
}