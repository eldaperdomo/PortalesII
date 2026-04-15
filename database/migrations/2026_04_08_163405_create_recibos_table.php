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
        Schema::create('recibos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pago_id');
            $table->unsignedBigInteger('abono_pago_id')->nullable();

            $table->string('numero', 50)->unique();

            $table->enum('tipo', ['abono', 'pago_completo']);

            $table->dateTime('fecha_emision')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->decimal('monto_recibido', 10, 2);

            $table->string('recibido_de', 150);
            $table->string('concepto', 255);

            $table->text('firma_base64')->nullable();
            $table->string('pdf_url', 255)->nullable();

            $table->boolean('activo')->default(true);

            $table->unsignedBigInteger('emitido_por_usuario_id')->nullable();
            $table->unsignedBigInteger('creado_por_usuario_id')->nullable();
            $table->unsignedBigInteger('actualizado_por_usuario_id')->nullable();

            // timestamps personalizados
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();

            // índices
            $table->index('pago_id');
            $table->index('abono_pago_id');
            $table->index('tipo');
            $table->index('activo');
            $table->index('fecha_emision');

            // claves foráneas opcionales
            // $table->foreign('pago_id')->references('id')->on('pagos')->onDelete('cascade');
            // $table->foreign('abono_pago_id')->references('id')->on('abonos_pago')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recibos');
    }
};
