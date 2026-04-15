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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contrato_id')->constrained('contratos')->onDelete('cascade');

            $table->string('periodo', 7); // YYYY-MM

            $table->decimal('monto_esperado', 10, 2);
            $table->decimal('total_pagado', 10, 2)->default(0);

            $table->enum('estado', ['pendiente', 'parcial', 'pagado'])->default('pendiente');

            $table->date('fecha_ultimo_abono')->nullable();

            $table->boolean('activo')->default(true);

            $table->foreignId('creado_por_usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->foreignId('actualizado_por_usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();

            // 🔥 Timestamps personalizados
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();

            $table->unique(['contrato_id', 'periodo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
