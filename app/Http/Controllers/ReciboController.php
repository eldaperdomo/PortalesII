<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recibo;
use App\Models\Pago;
use App\Models\AbonoPago;
use App\Models\Contrato;
use App\Models\Unidad;
use App\Models\Inquilino;

class ReciboController extends Controller
{
    // =========================
    // 🔹 CREAR DESDE ABONO
    // =========================
    public function crearDesdeAbono(Request $request)
    {
        try {
            if (!$request->abono_pago_id) {
                throw new \Exception("abono_pago_id es obligatorio");
            }

            $abono = AbonoPago::where('id', $request->abono_pago_id)
                ->where('activo', true)
                ->first();

            if (!$abono) {
                throw new \Exception("Abono no encontrado o desactivado");
            }

            $pago = $this->obtenerPagoCompleto($abono->pago_id);

            $numero = $this->generarNumeroRecibo();

            $recibo = Recibo::create([
                'pago_id' => $pago->id,
                'abono_pago_id' => $abono->id,
                'numero' => $numero,
                'tipo' => 'abono',
                'fecha_emision' => now(),
                'monto_recibido' => $abono->monto,
                'recibido_de' => $pago->contrato->inquilino->nombre,
                'concepto' => "Abono de alquiler de unidad {$pago->contrato->unidad->identificador}, periodo {$pago->periodo}",
                'firma_base64' => $request->firma_base64,
                'pdf_url' => null,
                'activo' => true,
                'emitido_por_usuario_id' => auth()->id(),
            ]);

            // aquí podrías llamar PDF si ya lo tienes en Laravel
            // $recibo->update(['pdf_url' => generarPdf($recibo)]);

            return response()->json(['ok' => true, 'recibo' => $recibo], 201);

        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'mensaje' => $e->getMessage()], 400);
        }
    }

    // =========================
    // 🔹 CREAR DESDE PAGO
    // =========================
    public function crearDesdePago(Request $request)
    {
        try {
            if (!$request->pago_id) {
                throw new \Exception("pago_id es obligatorio");
            }

            $pago = $this->obtenerPagoCompleto($request->pago_id);

            if ($pago->estado !== 'pagado') {
                throw new \Exception("Solo se puede generar recibo si el pago está completo");
            }

            $existe = Recibo::where('pago_id', $pago->id)
                ->where('tipo', 'pago_completo')
                ->where('activo', true)
                ->exists();

            if ($existe) {
                throw new \Exception("Ya existe un recibo de pago completo");
            }

            $numero = $this->generarNumeroRecibo();

            $recibo = Recibo::create([
                'pago_id' => $pago->id,
                'abono_pago_id' => null,
                'numero' => $numero,
                'tipo' => 'pago_completo',
                'fecha_emision' => now(),
                'monto_recibido' => $pago->total_pagado,
                'recibido_de' => $pago->contrato->inquilino->nombre,
                'concepto' => "Pago completo de unidad {$pago->contrato->unidad->identificador}, periodo {$pago->periodo}",
                'firma_base64' => $request->firma_base64,
                'pdf_url' => null,
                'activo' => true,
                'emitido_por_usuario_id' => auth()->id(),
            ]);

            return response()->json(['ok' => true, 'recibo' => $recibo], 201);

        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'mensaje' => $e->getMessage()], 400);
        }
    }

    // =========================
    // 🔹 LISTAR
    // =========================
    public function index(Request $request)
    {
        try {
            $query = Recibo::query();

            if ($request->incluir_inactivos !== "true") {
                $query->where('activo', true);
            }

            if ($request->pago_id) {
                $query->where('pago_id', $request->pago_id);
            }

            if ($request->abono_pago_id) {
                $query->where('abono_pago_id', $request->abono_pago_id);
            }

            if ($request->tipo) {
                $query->where('tipo', $request->tipo);
            }

            // includes simples
            if ($request->include_pago === "true") {
                $query->with('pago');
            }

            if ($request->include_abono === "true") {
                $query->with('abonoPago');
            }

            // orden
            $query->orderBy('id', 'desc');

            $recibos = $query->paginate($request->limit ?? 10);

            return response()->json([
                'ok' => true,
                'recibos' => $recibos->items(),
                'pagina' => $recibos->currentPage(),
                'total' => $recibos->total(),
                'total_paginas' => $recibos->lastPage(),
            ]);

        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'mensaje' => $e->getMessage()], 500);
        }
    }

    // =========================
    // 🔹 OBTENER
    // =========================
    public function show($id)
    {
        try {
            $recibo = Recibo::where('id', $id)
                ->where('activo', true)
                ->with(['pago.contrato.unidad', 'pago.contrato.inquilino', 'abonoPago'])
                ->first();

            if (!$recibo) {
                throw new \Exception("Recibo no encontrado");
            }

            return response()->json(['ok' => true, 'recibo' => $recibo]);

        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'mensaje' => $e->getMessage()], 404);
        }
    }

    // =========================
    // 🔹 ELIMINAR (LOGICO)
    // =========================
    public function destroy($id)
    {
        try {
            $recibo = Recibo::find($id);

            if (!$recibo || !$recibo->activo) {
                throw new \Exception("Recibo no encontrado");
            }

            $recibo->update(['activo' => false]);

            return response()->json(['ok' => true, 'mensaje' => 'Recibo desactivado']);

        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'mensaje' => $e->getMessage()], 400);
        }
    }

    // =========================
    // 🔹 ACTIVAR
    // =========================
    public function activar($id)
    {
        try {
            $recibo = Recibo::find($id);

            if (!$recibo) {
                throw new \Exception("Recibo no encontrado");
            }

            $recibo->update(['activo' => true]);

            return response()->json(['ok' => true, 'recibo' => $recibo]);

        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'mensaje' => $e->getMessage()], 400);
        }
    }

    // =========================
    // 🔹 FUNCIONES INTERNAS
    // =========================

    private function generarNumeroRecibo()
    {
        $count = Recibo::count() + 1;
        return 'REC-' . now()->format('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    private function obtenerPagoCompleto($id)
    {
        $pago = Pago::with('contrato.unidad', 'contrato.inquilino')
            ->where('id', $id)
            ->where('activo', true)
            ->first();

        if (!$pago) throw new \Exception("Pago no encontrado");

        return $pago;
    }
}