<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('abonos_pago', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pago_id')->constrained('pagos');

            $table->dateTime('fecha_abono');

            $table->decimal('monto', 10, 2);

            $table->enum('metodo', ['efectivo','transferencia','otro'])->default('efectivo');

            $table->string('referencia_pago')->nullable();
            $table->string('observacion')->nullable();

            $table->boolean('activo')->default(true);

            $table->foreignId('creado_por_usuario_id')->nullable();
            $table->foreignId('actualizado_por_usuario_id')->nullable();

            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abono_pagos');
    }
    
};
