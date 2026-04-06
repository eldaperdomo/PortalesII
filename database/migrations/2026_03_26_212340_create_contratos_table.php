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
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unidad_id')->constrained('unidades')->onDelete('cascade');
            $table->foreignId('inquilino_id')->constrained('inquilinos')->onDelete('cascade');
            $table->string('codigo')->unique()->comment('Número de contrato');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('monto_mensual', 12, 2);
            $table->decimal('deposito', 12, 2)->default(0);
            $table->integer('dia_pago')->default(1)->comment('Día del mes para pago');
            $table->enum('estado', ['activo', 'vencido', 'cancelado', 'pendiente'])->default('pendiente');
            $table->enum('periodicidad', ['mensual', 'bimestral', 'trimestral', 'semestral', 'anual'])->default('mensual');
            $table->decimal('incremento_anual', 5, 2)->default(0)->comment('Porcentaje de incremento anual');
            $table->text('clausulas_adicionales')->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('renovacion_automatica')->default(false);
            $table->timestamps();
            $table->softDeletes();
 
            // Un inquilino no puede tener dos contratos activos en la misma unidad
            $table->unique(['unidad_id', 'inquilino_id', 'fecha_inicio']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
