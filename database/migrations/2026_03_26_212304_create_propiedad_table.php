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
        Schema::create('propiedades', function (Blueprint $table) {
            $table->id();
           // $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nombre');
            $table->string('direccion');
            $table->string('ciudad');
            $table->string('departamento')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->enum('tipo', ['casa', 'apartamento', 'local_comercial', 'edificio', 'otro'])->default('casa');
            $table->text('descripcion')->nullable();
            $table->decimal('area_total', 10, 2)->nullable()->comment('En metros cuadrados');
            $table->string('imagen')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propiedades');
    }
};
