<?php
namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use App\Services\AbonoPagoServicio;

class AbonoPagoController extends Controller
{
    private $servicio;

    public function __construct(AbonoPagoServicio $servicio)
    {
        $this->servicio = $servicio;
    }

   public function show($id)
    {
        $abono = \App\Models\AbonoPago::with('pago.contrato')->findOrFail($id);

        return view('abonos.show', compact('abono'));
    }
    public function create($pagoId)
    {
        $pago = \App\Models\Pago::with('contrato')->findOrFail($pagoId);

        return view('abonos.create', compact('pago'));
    }

    public function store(Request $request)
{
    try {
        $abono = $this->servicio->crear(
            $request->all(),
            auth()->id(),
            $request->ip()
        );

        // 🔥 OBTENER EL PAGO CORRECTO
        $pago = \App\Models\Pago::findOrFail($abono->pago_id);

        return redirect()->route('pagos.show', [
            'pago' => $pago->id,
            'contrato_id' => $pago->contrato_id,
            'estado' => request('estado', 'activos')
        ])->with('success', 'Abono registrado correctamente');

    } catch (\Exception $e) {
        return redirect()->route('abonos.create', $request->pago_id)
            ->withErrors($e->getMessage())
            ->withInput();
    }
}
}