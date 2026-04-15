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
        Schema::create('solicitudes_inquilino', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('inquilino_id');
            $table->unsignedBigInteger('unidad_id');

            $table->enum('tipo', ['reparacion','mantenimiento','queja','sugerencia','incidente','otro'])->default('otro');

            $table->string('asunto', 150);
            $table->text('descripcion');

            $table->enum('prioridad', ['baja','media','alta','urgente'])->default('media');
            $table->enum('estado', ['abierta','en_revision','en_proceso','resuelta','cerrada'])->default('abierta');

            $table->text('respuesta')->nullable();
            $table->dateTime('fecha_cierre')->nullable();
            $table->string('evidencia_url', 255)->nullable();

            $table->boolean('activo')->default(true);

            $table->unsignedBigInteger('creado_por_usuario_id')->nullable();
            $table->unsignedBigInteger('actualizado_por_usuario_id')->nullable();

            // timestamps personalizados
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();

            // índices
            $table->index('inquilino_id');
            $table->index('unidad_id');
            $table->index('tipo');
            $table->index('prioridad');
            $table->index('estado');
            $table->index('activo');

            // claves foráneas opcionales
            // $table->foreign('inquilino_id')->references('id')->on('inquilinos')->onDelete('cascade');
            // $table->foreign('unidad_id')->references('id')->on('unidades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_inquilinos');
    }
};
