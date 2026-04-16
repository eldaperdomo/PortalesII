<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->unsignedBigInteger('inquilino_id')->nullable();

            $table->enum('tipo', [
                'solicitud',
                'abono',
                'pago_completo',
                'general'
            ])->default('general');

            $table->string('titulo', 150);
            $table->string('mensaje', 255);

            $table->dateTime('fecha_enviada')->nullable();

            $table->enum('canal', ['correo'])->default('correo');

            $table->enum('estado', [
                'pendiente',
                'enviada',
                'fallida'
            ])->default('pendiente');

            $table->boolean('leida')->default(false);
            $table->boolean('activo')->default(true);

            $table->string('destino_correo', 150)->nullable();

            $table->string('referencia_tabla')->nullable();
            $table->unsignedBigInteger('referencia_id')->nullable();

            $table->unsignedBigInteger('creado_por_usuario_id')->nullable();
            $table->unsignedBigInteger('actualizado_por_usuario_id')->nullable();

            // 🔥 timestamps personalizados
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();

            // índices
            $table->index('usuario_id');
            $table->index('inquilino_id');
            $table->index('tipo');
            $table->index('estado');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};