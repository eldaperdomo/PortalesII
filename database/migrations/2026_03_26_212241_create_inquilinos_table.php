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
        Schema::create('inquilinos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('dni')->unique()->comment('DPI, cédula, pasaporte');
            $table->string('email')->unique()->nullable();
            $table->string('telefono')->nullable();
            $table->string('telefono_emergencia')->nullable();
            $table->string('contacto_emergencia')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('estado_civil', ['soltero', 'casado', 'divorciado', 'viudo', 'otro'])->nullable();
            $table->string('ocupacion')->nullable();
            $table->string('empresa')->nullable();
            $table->decimal('ingreso_mensual', 12, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquilinos');
    }
};
