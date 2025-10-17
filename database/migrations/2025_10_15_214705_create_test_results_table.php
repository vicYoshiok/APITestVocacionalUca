<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_test_results_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('correo');
            $table->string('telefono');
            $table->integer('edad');
            $table->string('escuela');
            
            // Resultados del test
            $table->integer('puntaje_R');
            $table->integer('puntaje_I');
            $table->integer('puntaje_A');
            $table->integer('puntaje_S');
            $table->integer('puntaje_E');
            $table->integer('puntaje_C');
            
            // Porcentajes
            $table->integer('porcentaje_R');
            $table->integer('porcentaje_I');
            $table->integer('porcentaje_A');
            $table->integer('porcentaje_S');
            $table->integer('porcentaje_E');
            $table->integer('porcentaje_C');
            
            // Respuestas completas en JSON
            $table->json('respuestas');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_results');
    }
};