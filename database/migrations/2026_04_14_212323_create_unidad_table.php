<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unidades', function (Blueprint $table) {
            $table->id();
            $table->integer('propiedad_id');
            $table->string('identificador', 50);
            $table->enum('estado', ['disponible', 'ocupada', 'mantenimiento'])->default('disponible');
            $table->decimal('monto_renta', 10, 2);
            $table->tinyInteger('activo')->default(1);
            $table->integer('creado_por_usuario_id')->nullable();
            $table->integer('actualizado_por_usuario_id')->nullable();
            $table->dateTime('creado_en')->nullable();
            $table->dateTime('actualizado_en')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unidades');
    }
};