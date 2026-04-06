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
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propiedad_id')->constrained('propiedades')->onDelete('cascade');
            $table->foreignId('unidad_id')->nullable()->constrained('unidades')->onDelete('set null');
            $table->string('concepto');
            $table->decimal('monto', 12, 2);
            $table->date('fecha');
            $table->enum('categoria', [
                'mantenimiento',
                'reparacion',
                'impuesto',
                'seguro',
                'servicios',
                'administracion',
                'limpieza',
                'otro'
            ])->default('otro');
            $table->enum('estado', ['pendiente', 'pagado', 'cancelado'])->default('pendiente');
            $table->string('proveedor')->nullable();
            $table->string('comprobante')->nullable()->comment('Número de factura o recibo');
            $table->string('archivo_adjunto')->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};
