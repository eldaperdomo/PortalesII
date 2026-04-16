<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReciboServicio;

class ReciboController extends Controller
{
    protected $service;

    public function __construct(ReciboServicio $service)
    {
        $this->service = $service;
    }

    public function crearDesdePago(Request $request)
    {
        try {
            $recibo = $this->service->crearDesdePago(
                $request->all(),
                auth()->id()
            );

            return response()->json(['ok' => true, 'recibo' => $recibo], 201);

        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'mensaje' => $e->getMessage()], 400);
        }
    }

    public function crearDesdeAbono(Request $request)
    {
        try {
            $recibo = $this->service->crearDesdeAbono(
                $request->all(),
                auth()->id()
            );

            return response()->json(['ok' => true, 'recibo' => $recibo], 201);

        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'mensaje' => $e->getMessage()], 400);
        }
    }
    public function verReciboPago($pagoId)
    {
        $pago = \App\Models\Pago::with('recibo')->find($pagoId);

        if (!$pago || !$pago->recibo) {
            return redirect()->back()->with('error', 'No se ha generado recibo de pago completo');
        }

        return redirect(asset($pago->recibo->pdf_url));
    }
    public function verReciboAbono($abonoId)
    {
        $abono = \App\Models\AbonoPago::with('recibo')->find($abonoId);

        if (!$abono || !$abono->recibo) {
            return redirect()->back()->with('error', 'No se ha generado recibo de este abono');
        }

        return redirect(asset($abono->recibo->pdf_url));
    }
}