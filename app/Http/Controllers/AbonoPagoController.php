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
        $pago = \App\Models\Pago::findOrFail($pagoId);

        return view('abonos.create', compact('pago'));
    }

    public function store(Request $request)
    {
        try {
            $this->servicio->crear(
                $request->all(),
                auth()->id(),
                $request->ip()
            );

            return redirect()->route('pagos.index')
                ->with('success', 'Abono registrado correctamente');

        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }
}