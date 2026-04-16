<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Contrato;
use Illuminate\Http\Request;
use App\Services\PagoServicio;

class PagoController extends Controller
{
    private $servicio;

    public function __construct(PagoServicio $servicio)
    {
        $this->servicio = $servicio;
    }

    // 🔥 LISTAR
public function index(Request $request)
{
    $estado = $request->estado ?? 'activos';

    if ($estado == 'inactivos') {
        $contratos = Contrato::onlyTrashed()->get();
    } else {
        $contratos = Contrato::all();
    }

    $pagos = [];

    if ($request->contrato_id) {
        $pagos = Pago::with('contrato')
            ->where('contrato_id', $request->contrato_id)
            ->get();
    }

    return view('pagos.index', compact('pagos', 'contratos'));
}
    // 🔥 FORM CREAR (SIN RELACIÓN 🔥)
    public function create()
    {
        $contratos = Contrato::activos()->get();

        // 🔥 calcular último periodo manualmente
        foreach ($contratos as $c) {
            $ultimoPago = Pago::where('contrato_id', $c->id)
                ->orderBy('periodo', 'desc')
                ->first();

            $c->ultimo_periodo = $ultimoPago?->periodo;
        }

        return view('pagos.create', compact('contratos'));
    }

    // 🔥 GUARDAR
    public function store(Request $request)
    {
        $request->validate([
            'contrato_id' => 'required|exists:contratos,id',
            'periodo' => 'required|date_format:Y-m'
        ]);

        try {
            $this->servicio->crear(
                $request->all(),
                auth()->id(),
                $request->ip()
            );

            return redirect()->route('pagos.index')
                ->with('success', 'Pago creado correctamente');

        } catch (\Exception $e) {
            return back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    // 🔥 VER
    public function show(Pago $pago)
    {
        $pago->load(['contrato', 'abonos']);
        return view('pagos.show', compact('pago'));
    }

    // 🔥 EDITAR
    public function edit(Pago $pago)
    {
        if ($pago->estado === 'pagado') {
            return redirect()->route('pagos.index')
                ->with('error', 'No se puede editar un pago ya completado');
        }

        $pago->load('contrato');

        return view('pagos.edit', compact('pago'));
    }

    // 🔥 ACTUALIZAR
    public function update(Request $request, Pago $pago)
    {
        $request->validate([
            'periodo' => 'required|date_format:Y-m'
        ]);

        try {
            $this->servicio->actualizar(
                $pago,
                $request->all(),
                auth()->id(),
                $request->ip()
            );

            return redirect()->route('pagos.index')
                ->with('success', 'Pago actualizado correctamente');

        } catch (\Exception $e) {
            return back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    // 🔥 DESACTIVAR
    public function destroy(Pago $pago)
    {
        if (!auth()->user()->esAdmin()) {
            return back()->withErrors('No tienes permiso para desactivar pagos');
        }

        try {
            $this->servicio->eliminar(
                $pago,
                auth()->id(),
                request()->ip()
            );

            return back()->with('success', 'Pago desactivado correctamente');

        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    // 🔥 ACTIVAR
    public function activar(Pago $pago)
    {
        if (!auth()->user()->esAdmin()) {
            return back()->withErrors('No tienes permiso para activar pagos');
        }

        try {
            $this->servicio->activar(
                $pago,
                auth()->id(),
                request()->ip()
            );

            return redirect()->route('pagos.index')
                ->with('success', 'Pago activado correctamente');

        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}