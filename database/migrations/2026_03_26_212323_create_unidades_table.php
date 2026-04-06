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
        Schema::create('unidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propiedad_id')->constrained('propiedades')->onDelete('cascade');
            $table->string('nombre')->comment('Ej: Apto 101, Local A, Casa 2');
            $table->string('numero')->nullable();
            $table->enum('tipo', ['apartamento', 'casa', 'habitacion', 'local', 'oficina', 'bodega', 'otro'])->default('apartamento');
            $table->decimal('area', 10, 2)->nullable()->comment('En metros cuadrados');
            $table->integer('habitaciones')->default(1);
            $table->integer('banos')->default(1);
            $table->boolean('tiene_parqueo')->default(false);
            $table->decimal('precio_renta', 12, 2);
            $table->enum('estado', ['disponible', 'ocupada', 'en_mantenimiento', 'inactiva'])->default('disponible');
            $table->text('descripcion')->nullable();
            $table->integer('piso')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidades');
    }
};
