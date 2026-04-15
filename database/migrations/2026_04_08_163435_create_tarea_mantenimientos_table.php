<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration

{
    public function up()
    {
        Schema::create('tareas_mantenimiento', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('unidad_id');
            $table->unsignedBigInteger('solicitud_inquilino_id')->nullable();

            $table->string('titulo', 150);
            $table->text('descripcion')->nullable();

            $table->enum('prioridad', ['baja','media','alta','urgente'])->default('media');
            $table->enum('estado', ['pendiente','en_proceso','completada','cancelada'])->default('pendiente');

            $table->date('fecha_limite')->nullable();
            $table->dateTime('fecha_completada')->nullable();

            $table->boolean('activo')->default(true);

            $table->unsignedBigInteger('creado_por_usuario_id')->nullable();
            $table->unsignedBigInteger('actualizado_por_usuario_id')->nullable();

            // timestamps personalizados
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();

            // índices
            $table->index('unidad_id');
            $table->index('solicitud_inquilino_id');
            $table->index('prioridad');
            $table->index('estado');
            $table->index('fecha_limite');
            $table->index('activo');

            // claves foráneas opcionales
            // $table->foreign('unidad_id')->references('id')->on('unidades')->onDelete('cascade');
            // $table->foreign('solicitud_inquilino_id')->references('id')->on('solicitudes_inquilino')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarea_mantenimientos');
    }
};
