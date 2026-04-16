<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->integer('unidad_id');
            $table->integer('inquilino_id');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('monto_renta', 10, 2);
            $table->integer('dia_pago')->default(1);
            $table->enum('estado', ['activo', 'terminado', 'cancelado'])->default('activo');
            $table->tinyInteger('activo')->default(1);
            $table->integer('creado_por_usuario_id')->nullable();
            $table->integer('actualizado_por_usuario_id')->nullable();
            //$table->dateTime('creado_en')->nullable();
            //$table->dateTime('actualizado_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};