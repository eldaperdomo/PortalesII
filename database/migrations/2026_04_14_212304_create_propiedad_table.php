<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('propiedades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('direccion', 255)->nullable();
          //  $table->enum('tipo', ['casa', 'edificio']);
           // $table->text('descripcion')->nullable();
            $table->tinyInteger('activo')->default(1);
            $table->integer('creado_por_usuario_id')->nullable();
            $table->integer('actualizado_por_usuario_id')->nullable();
            $table->dateTime('creado_en')->nullable();
            $table->dateTime('actualizado_en')->nullable();

          //  $table->string('nombre');
           // $table->string('direccion');
            $table->string('ciudad');
            $table->string('departamento')->nullable();
            $table->string('codigo_postal')->nullable();

            $table->enum('tipo', [
                'casa',
                'apartamento',
                'local_comercial',
                'edificio',
                'otro'
            ])->default('casa');

            $table->text('descripcion')->nullable();
            $table->decimal('area_total', 10, 2)->nullable();

            $table->string('imagen')->nullable();
            $table->boolean('activa')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('propiedades');
    }
};