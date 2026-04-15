<?php

namespace App\Services;

use App\Models\Pago;
use App\Models\Contrato;
use App\Models\AuditoriaLog;
use Carbon\Carbon;

class PagoServicio
{
    private function validarPeriodo($periodo)
    {
        if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $periodo)) {
            throw new \Exception('Formato inválido (YYYY-MM)');
        }
    }

    private function factor($periodicidad)
    {
        return match ($periodicidad) {
            'mensual' => 1,
            'bimestral' => 2,
            'trimestral' => 3,
            'semestral' => 6,
            'anual' => 12,
            default => 1
        };
    }

    // 🔥 VALIDAR PERIODICIDAD REAL
    private function validarPeriodicidad($contrato, $periodo)
    {
        $inicio = Carbon::parse($contrato->fecha_inicio);
        $periodoFecha = Carbon::createFromFormat('Y-m', $periodo);

        // 🔥 ajustar por día de pago
        if ($inicio->day > $contrato->dia_pago) {
            $inicio->addMonth();
        }

        $diff = $inicio->diffInMonths($periodoFecha);
        $factor = $this->factor($contrato->periodicidad);

        if ($diff % $factor !== 0) {
            throw new \Exception("El periodo no corresponde a la periodicidad del contrato");
        }
    }

    // 🔥 CREAR
    public function crear($datos, $usuarioId, $ip = null)
    {
        if (empty($datos['contrato_id'])) {
            throw new \Exception("Contrato requerido");
        }

        if (empty($datos['periodo'])) {
            throw new \Exception("Periodo requerido");
        }

        $this->validarPeriodo($datos['periodo']);

        $contrato = Contrato::where('id', $datos['contrato_id'])
            ->where('estado', 'activo')
            ->first();

        if (!$contrato) {
            throw new \Exception("Contrato no activo");
        }

        $periodoFecha = Carbon::createFromFormat('Y-m', $datos['periodo']);
        $inicio = Carbon::parse($contrato->fecha_inicio)->startOfMonth();
        $fin = Carbon::parse($contrato->fecha_fin)->endOfMonth();

        // 🔥 NO ANTES
        if ($periodoFecha->lt($inicio)) {
            throw new \Exception("No puedes crear pagos antes del contrato");
        }

        // 🔥 NO DESPUÉS
        if ($periodoFecha->gt($fin)) {
            throw new \Exception("El periodo está fuera del contrato");
        }

        // 🔥 PERIODICIDAD
        $this->validarPeriodicidad($contrato, $datos['periodo']);

        // 🔥 DUPLICADO
        if (Pago::where('contrato_id', $contrato->id)
            ->where('periodo', $datos['periodo'])
            ->exists()) {
            throw new \Exception("Ya existe un pago para ese periodo");
        }

        // 🔥 MONTO SEGÚN PERIODICIDAD
        $monto = $contrato->monto_mensual * $this->factor($contrato->periodicidad);

        $pago = Pago::create([
            'contrato_id' => $contrato->id,
            'periodo' => $datos['periodo'],
            'monto_esperado' => $monto,
            'total_pagado' => 0,
            'estado' => 'pendiente',
            'activo' => true,
            'creado_por_usuario_id' => $usuarioId,
            'actualizado_por_usuario_id' => $usuarioId,
        ]);

        AuditoriaLog::create([
            'usuario_id' => $usuarioId,
            'tabla' => 'pagos',
            'accion' => 'CREATE',
            'registro_id' => $pago->id,
            'datos_nuevos' => $pago->toArray(),
            'ip' => $ip,
            'fecha' => now()
        ]);

        return $pago;
    }

    // 🔥 ACTUALIZAR
    public function actualizar($pago, $datos, $usuarioId, $ip = null)
    {
        if ($pago->estado === 'pagado') {
            throw new \Exception("No se puede modificar un pago pagado");
        }

        if (empty($datos['periodo'])) {
            throw new \Exception("Periodo requerido");
        }

        $this->validarPeriodo($datos['periodo']);

        $contrato = $pago->contrato;

        $periodoFecha = Carbon::createFromFormat('Y-m', $datos['periodo']);
        $inicio = Carbon::parse($contrato->fecha_inicio)->startOfMonth();
        $fin = Carbon::parse($contrato->fecha_fin)->endOfMonth();

        if ($periodoFecha->lt($inicio)) {
            throw new \Exception("Periodo inválido");
        }

        if ($periodoFecha->gt($fin)) {
            throw new \Exception("Periodo fuera del contrato");
        }

        $this->validarPeriodicidad($contrato, $datos['periodo']);

        if (Pago::where('contrato_id', $pago->contrato_id)
            ->where('periodo', $datos['periodo'])
            ->where('id', '!=', $pago->id)
            ->exists()) {
            throw new \Exception("Periodo duplicado");
        }

        $antes = $pago->toArray();

        $pago->update([
            'periodo' => $datos['periodo'],
            'actualizado_por_usuario_id' => $usuarioId
        ]);

        AuditoriaLog::create([
            'usuario_id' => $usuarioId,
            'tabla' => 'pagos',
            'accion' => 'UPDATE',
            'registro_id' => $pago->id,
            'datos_anteriores' => $antes,
            'datos_nuevos' => $pago->toArray(),
            'ip' => $ip,
            'fecha' => now()
        ]);

        return $pago;
    }

    // 🔥 ELIMINAR
    public function eliminar($pago, $usuarioId, $ip = null)
    {
        if (!$pago->activo) {
            throw new \Exception("Ya está inactivo");
        }

        $antes = $pago->toArray();

        $pago->update([
            'activo' => false,
            'actualizado_por_usuario_id' => $usuarioId
        ]);

        AuditoriaLog::create([
            'usuario_id' => $usuarioId,
            'tabla' => 'pagos',
            'accion' => 'DELETE',
            'registro_id' => $pago->id,
            'datos_anteriores' => $antes,
            'datos_nuevos' => $pago->toArray(),
            'ip' => $ip,
            'fecha' => now()
        ]);
    }

    // 🔥 ACTIVAR
    public function activar($pago, $usuarioId, $ip = null)
    {
        if ($pago->activo) {
            throw new \Exception("Ya está activo");
        }

        $antes = $pago->toArray();

        $pago->update([
            'activo' => true,
            'actualizado_por_usuario_id' => $usuarioId
        ]);

        AuditoriaLog::create([
            'usuario_id' => $usuarioId,
            'tabla' => 'pagos',
            'accion' => 'UPDATE',
            'registro_id' => $pago->id,
            'datos_anteriores' => $antes,
            'datos_nuevos' => $pago->toArray(),
            'ip' => $ip,
            'fecha' => now()
        ]);

        return $pago;
    }
}