<?php

namespace App\Services;

use App\Models\Recibo;
use App\Models\Pago;
use App\Models\AbonoPago;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ReciboServicio
{
    public function generarNumeroRecibo()
    {
        $hoy = now()->format('Ymd');
        $total = Recibo::count() + 1;
        $correlativo = str_pad($total, 4, '0', STR_PAD_LEFT);

        return "REC-{$hoy}-{$correlativo}";
    }

    // 🔥 CREAR DESDE PAGO COMPLETO
    public function crearDesdePago($datos, $usuarioId)
    {
        $pago = Pago::with(['contrato.unidad', 'contrato.inquilino'])
            ->where('id', $datos['pago_id'])
            ->where('activo', true)
            ->first();

        if (!$pago) {
            throw new \Exception("Pago no encontrado");
        }

        if ($pago->estado !== 'pagado') {
            throw new \Exception("El pago no está completamente pagado");
        }

        $existe = Recibo::where('pago_id', $pago->id)
            ->where('tipo', 'pago_completo')
            ->where('activo', true)
            ->exists();

        if ($existe) {
            throw new \Exception("Ya existe un recibo para este pago");
        }

        $numero = $this->generarNumeroRecibo();

        $recibo = Recibo::create([
            'pago_id' => $pago->id,
            'numero' => $numero,
            'tipo' => 'pago_completo',
            'fecha_emision' => now(),
            'monto_recibido' => $pago->total_pagado,
            'recibido_de' => $pago->contrato->inquilino->nombre,
            'concepto' => "Pago completo de unidad {$pago->contrato->unidad->identificador}, periodo {$pago->periodo}",
            'firma_base64' => $datos['firma_base64'] ?? null,
            'activo' => true,
            'emitido_por_usuario_id' => $usuarioId,
            'creado_por_usuario_id' => $usuarioId,
            'actualizado_por_usuario_id' => $usuarioId,
        ]);

        $this->generarPdf($recibo, $pago);

        return $recibo->load('pago');
    }

    // 🔥 CREAR DESDE ABONO
    public function crearDesdeAbono($datos, $usuarioId)
    {
        if (empty($datos['abono_pago_id'])) {
            throw new \Exception("abono_pago_id requerido");
        }

        $abono = AbonoPago::where('id', $datos['abono_pago_id'])
            ->where('activo', true)
            ->first();

        if (!$abono) {
            throw new \Exception("Abono no encontrado");
        }

        $pago = Pago::with(['contrato.unidad', 'contrato.inquilino'])
            ->findOrFail($abono->pago_id);

        $numero = $this->generarNumeroRecibo();

        $recibo = Recibo::create([
            'pago_id' => $pago->id,
            'abono_pago_id' => $abono->id,
            'numero' => $numero,
            'tipo' => 'abono',
            'fecha_emision' => now(),
            'monto_recibido' => $abono->monto,
            'recibido_de' => $pago->contrato->inquilino->nombre,
            'concepto' => "Abono de unidad {$pago->contrato->unidad->identificador}, periodo {$pago->periodo}",
            'firma_base64' => $datos['firma_base64'] ?? null,
            'activo' => true,
            'emitido_por_usuario_id' => $usuarioId,
            'creado_por_usuario_id' => $usuarioId,
            'actualizado_por_usuario_id' => $usuarioId,
        ]);

        $this->generarPdf($recibo, $pago, $abono);

        return $recibo->load(['pago', 'abonoPago']);
    }

    // 🔥 GENERAR PDF (reutilizable)
    private function generarPdf($recibo, $pago, $abono = null)
    {
        $pdf = Pdf::loadView('recibos.pdf', [
            'recibo' => $recibo,
            'pago' => $pago,
            'abono' => $abono
        ]);

        $nombre = $recibo->numero . '.pdf';
        $ruta = 'recibos/' . $nombre;

        Storage::disk('public')->put($ruta, $pdf->output());

        $recibo->update([
            'pdf_url' => 'storage/' . $ruta
        ]);
    }

    // 🔥 AUTOMÁTICO (cuando pago pasa a pagado)
    public function generarAutomatico($pagoId, $usuarioId = null)
    {
        $pago = Pago::with(['contrato.unidad', 'contrato.inquilino'])
            ->find($pagoId);

        if (!$pago || $pago->estado !== 'pagado') {
            return null;
        }

        $existe = Recibo::where('pago_id', $pago->id)
            ->where('tipo', 'pago_completo')
            ->where('activo', true)
            ->exists();

        if ($existe) {
            return null;
        }

        return $this->crearDesdePago([
            'pago_id' => $pago->id
        ], $usuarioId);
    }
}