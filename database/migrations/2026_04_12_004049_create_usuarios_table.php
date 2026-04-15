<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();

            $table->string('nombre', 150);
            $table->string('username', 50)->unique();
            $table->string('email', 120)->unique();

            $table->string('password'); // Laravel usa esto

            $table->enum('rol', ['admin', 'empleado']); // quitamos cliente
            $table->string('foto_perfil_url')->nullable();

            $table->boolean('activo')->default(true);

    
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};