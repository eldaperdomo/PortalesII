<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{

    public function up(): void
    {
        Schema::create('auditoria_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();

            $table->string('tabla', 100);
            $table->enum('accion', ['CREATE', 'UPDATE', 'DELETE']);

            $table->unsignedBigInteger('registro_id');

            $table->json('datos_anteriores')->nullable();
            $table->json('datos_nuevos')->nullable();

            $table->string('ip', 50)->nullable();

            $table->timestamp('fecha')->useCurrent();
        });
    }
     public function down(): void
    {
        Schema::dropIfExists('auditoria_logs');
    }
};
