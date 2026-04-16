<?php
namespace App\Services;

use App\Models\AbonoPago;
use App\Models\Pago;
use App\Models\AuditoriaLog;
use App\Services\ReciboServicio;
use App\Services\NotificacionesServicio;

class AbonoPagoServicio
{
    private function recalcularPago($pagoId)
    {
        $pago = Pago::findOrFail($pagoId);

        $total = AbonoPago::where('pago_id', $pagoId)
            ->where('activo', true)
            ->sum('monto');

        $estado = 'pendiente';

        if ($total > 0 && $total < $pago->monto_esperado) {
            $estado = 'parcial';
        } elseif ($total >= $pago->monto_esperado) {
            $estado = 'pagado';
        }

        $pago->update([
            'total_pagado' => $total,
            'estado' => $estado,
            'fecha_ultimo_abono' => now()
        ]);
    }

    public function crear($datos, $usuarioId, $ip = null)
    {
        if (empty($datos['pago_id'])) {
            throw new \Exception("Pago requerido");
        }

        if (empty($datos['monto']) || $datos['monto'] <= 0) {
            throw new \Exception("Monto inválido");
        }

        $pago = Pago::findOrFail($datos['pago_id']);

        if ($pago->estado === 'pagado') {
            throw new \Exception("El pago ya está completado");
        }

        $totalActual = AbonoPago::where('pago_id', $pago->id)
            ->where('activo', true)
            ->sum('monto');

        $nuevoTotal = $totalActual + $datos['monto'];

        if ($nuevoTotal > $pago->monto_esperado) {
            $saldo = $pago->monto_esperado - $totalActual;
            throw new \Exception("Excede el saldo. Disponible: L " . number_format($saldo,2));
        }

        $ruta = null;

        if (request()->hasFile('referencia_pago')) {
            $ruta = request()->file('referencia_pago')->store('abonos', 'public');
        }

        $abono = AbonoPago::create([
            'pago_id' => $pago->id,
            'fecha_abono' => $datos['fecha_abono'] ?? now(),
            'monto' => $datos['monto'],
            'metodo' => $datos['metodo'] ?? 'efectivo',
            'referencia_pago' => $ruta,
            'observacion' => $datos['observacion'] ?? null,
            'activo' => true,
            'creado_por_usuario_id' => $usuarioId,
            'actualizado_por_usuario_id' => $usuarioId,
        ]);

        $this->recalcularPago($pago->id);

        // =============================
        // 🔥 RECIBO DEL ABONO
        // =============================
        app(ReciboServicio::class)
            ->crearDesdeAbono([
                'abono_pago_id' => $abono->id
            ], $usuarioId);

        // 🔥 cargar relaciones
        $abono->load('pago.contrato.inquilino');

        // 🔥 obtener recibo
        $reciboAbono = \App\Models\Recibo::where('abono_pago_id', $abono->id)
            ->where('tipo', 'abono')
            ->latest()
            ->first();

        // 🔥 notificar abono
        app(NotificacionesServicio::class)
            ->notificarAbono($abono, $reciboAbono);

        // =============================
        // 🔥 RECIBO DE PAGO COMPLETO
        // =============================
        $reciboPago = app(ReciboServicio::class)
            ->generarAutomatico($pago->id, $usuarioId);

        if ($reciboPago) {

            $pago->load('contrato.inquilino');

            app(NotificacionesServicio::class)
                ->notificarPagoCompleto($pago, $reciboPago);
        }

        AuditoriaLog::create([
            'usuario_id' => $usuarioId,
            'tabla' => 'abonos_pago',
            'accion' => 'CREATE',
            'registro_id' => $abono->id,
            'datos_nuevos' => $abono->toArray(),
            'ip' => $ip,
            'fecha' => now()
        ]);

        return $abono;
    }
}