<?php

namespace App\Http\Controllers;

use App\Models\contrato;
use App\Models\unidad;
use App\Models\inquilino;
use Illuminate\Http\Request;

class ContratosController extends Controller
{
    public function index()
    {
        $contratos = Contrato::with(['unidad.propiedad', 'inquilino'])
            ->latest()
            ->paginate(10);

        return view('contratos.index', compact('contratos'));
    }

    public function create()
    {
        $unidades   = Unidad::disponibles()->with('propiedad')->get();
        $inquilinos = Inquilino::activos()->get();
        return view('contratos.create', compact('unidades', 'inquilinos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unidad_id'              => 'required|exists:unidades,id',
            'inquilino_id'           => 'required|exists:inquilinos,id',
            'fecha_inicio'           => 'required|date',
            'fecha_fin'              => 'required|date|after:fecha_inicio',
            'monto_mensual'          => 'required|numeric|min:0',
            'deposito'               => 'nullable|numeric|min:0',
            'dia_pago'               => 'required|integer|min:1|max:31',
            'estado'                 => 'required|in:activo,vencido,cancelado,pendiente',
            'periodicidad'           => 'required|in:mensual,bimestral,trimestral,semestral,anual',
            'incremento_anual'       => 'nullable|numeric|min:0|max:100',
            'clausulas_adicionales'  => 'nullable|string',
            'observaciones'          => 'nullable|string',
            'renovacion_automatica'  => 'boolean',
        ]);

        // Verificar que la unidad no tenga contrato activo
        $tieneContratoActivo = Contrato::where('unidad_id', $validated['unidad_id'])
            ->where('estado', 'activo')
            ->exists();

        if ($tieneContratoActivo) {
            return back()
                ->withInput()
                ->with('error', 'La unidad ya tiene un contrato activo.');
        }

        $validated['codigo'] = Contrato::generarCodigo();

        Contrato::create($validated);

        return redirect()->route('contratos.index')
            ->with('success', 'Contrato creado correctamente.');
    }

    public function show(Contrato $contrato)
    {
        $contrato->load(['unidad.propiedad', 'inquilino']);
        return view('contratos.show', compact('contrato'));
    }

    public function edit(Contrato $contrato)
    {
        $unidades   = Unidad::with('propiedad')->get();
        $inquilinos = Inquilino::activos()->get();
        return view('contratos.edit', compact('contrato', 'unidades', 'inquilinos'));
    }

    public function update(Request $request, Contrato $contrato)
    {
        $validated = $request->validate([
            'unidad_id'              => 'required|exists:unidades,id',
            'inquilino_id'           => 'required|exists:inquilinos,id',
            'fecha_inicio'           => 'required|date',
            'fecha_fin'              => 'required|date|after:fecha_inicio',
            'monto_mensual'          => 'required|numeric|min:0',
            'deposito'               => 'nullable|numeric|min:0',
            'dia_pago'               => 'required|integer|min:1|max:31',
            'estado'                 => 'required|in:activo,vencido,cancelado,pendiente',
            'periodicidad'           => 'required|in:mensual,bimestral,trimestral,semestral,anual',
            'incremento_anual'       => 'nullable|numeric|min:0|max:100',
            'clausulas_adicionales'  => 'nullable|string',
            'observaciones'          => 'nullable|string',
            'renovacion_automatica'  => 'boolean',
        ]);

        $contrato->update($validated);

        return redirect()->route('contratos.show', $contrato)
            ->with('success', 'Contrato actualizado correctamente.');
    }

    public function destroy(Contrato $contrato)
    {
        if ($contrato->estado === 'activo') {
            return back()->with('error', 'No se puede eliminar un contrato activo. Cancélelo primero.');
        }

        $contrato->delete();

        return redirect()->route('contratos.index')
            ->with('success', 'Contrato eliminado correctamente.');
    }
}