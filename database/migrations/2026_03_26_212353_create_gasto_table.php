<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('propiedad_id')->constrained('propiedades')->onDelete('cascade');
            $table->foreignId('unidad_id')->nullable()->constrained('unidades')->onDelete('set null');
            
            // Campos de datos (Nombres exactos según tus errores)
            $table->date('fecha');
            $table->decimal('monto', 12, 2);
            $table->string('categoria'); 
            $table->text('descripcion')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('comprobante')->nullable();
            $table->boolean('activo')->default(true);
            $table->string('estado')->default('pendiente');
            
            // Auditoría con nombres en español (Como pedía el error)
            $table->unsignedBigInteger('creado_por_usuario_id')->nullable();
            $table->unsignedBigInteger('actualizado_por_usuario_id')->nullable();

            // Timestamps en español
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
            
            $table->softDeletes(); // Esto crea 'deleted_at'
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};
