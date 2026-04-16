<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inquilinos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('telefono', 20)->nullable();
            $table->string('correo', 100)->nullable();
            $table->string('foto_url', 255)->nullable();
            $table->tinyInteger('activo')->default(1);
            $table->string('codigo_registro', 20)->nullable();
            $table->tinyInteger('codigo_registro_usado')->default(0);
            $table->dateTime('codigo_registro_expira_en')->nullable();
            $table->integer('creado_por_usuario_id')->nullable();
            $table->integer('actualizado_por_usuario_id')->nullable();
            //$table->dateTime('creado_en')->nullable();
            //$table->dateTime('actualizado_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquilinos');
    }
};