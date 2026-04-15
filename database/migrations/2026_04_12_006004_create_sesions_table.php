<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesiones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')->constrained('usuarios');

            $table->timestamp('inicio_sesion');
            $table->timestamp('cierre_sesion')->nullable();

            $table->string('user_agent')->nullable();

            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('sesiones');
    }
};