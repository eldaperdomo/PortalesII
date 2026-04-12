<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Unidad;
use App\Models\Inquilino;
use Illuminate\Http\Request;

class ContratoController extends Controller
{
    public function index()
    {
        $contratos = Contrato::with(['unidad.propiedad', 'inquilino'])->latest('creado_en')->paginate(10);
        return view('contrato.index', compact('contratos'));
    }

    public function create()
    {
        $unidades   = Unidad::disponibles()->with('propiedad')->get();
        $inquilinos = Inquilino::activos()->get();
        return view('contrato.create', compact('unidades', 'inquilinos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unidad_id'    => 'required|exists:unidades,id',
            'inquilino_id' => 'required|exists:inquilinos,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after:fecha_inicio',
            'monto_renta'  => 'required|numeric|min:0',
            'dia_pago'     => 'required|integer|min:1|max:31',
            'estado'       => 'required|in:activo,terminado,cancelado',
            'activo'       => 'nullable|boolean',
        ]);

        // Verificar que la unidad no tenga contrato activo
        if (Contrato::where('unidad_id', $validated['unidad_id'])->where('estado', 'activo')->exists()) {
            return back()->withInput()->with('error', 'La unidad ya tiene un contrato activo.');
        }

        $validated['activo']                     = $request->has('activo') ? 1 : 0;
        $validated['creado_por_usuario_id']      = auth()->id();
        $validated['actualizado_por_usuario_id'] = auth()->id();
        $validated['creado_en']                  = now();
        $validated['actualizado_en']             = now();

        Contrato::create($validated);

        return redirect()->route('contrato.index')
            ->with('success', 'Contrato creado correctamente.');
    }

    public function show(Contrato $contrato)
    {
        $contrato->load(['unidad.propiedad', 'inquilino']);
        return view('contrato.show', compact('contrato'));
    }

    public function edit(Contrato $contrato)
    {
        $unidades   = Unidad::with('propiedad')->get();
        $inquilinos = Inquilino::activos()->get();
        return view('contrato.edit', compact('contrato', 'unidades', 'inquilinos'));
    }

    public function update(Request $request, Contrato $contrato)
    {
        $validated = $request->validate([
            'unidad_id'    => 'required|exists:unidades,id',
            'inquilino_id' => 'required|exists:inquilinos,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after:fecha_inicio',
            'monto_renta'  => 'required|numeric|min:0',
            'dia_pago'     => 'required|integer|min:1|max:31',
            'estado'       => 'required|in:activo,terminado,cancelado',
            'activo'       => 'nullable|boolean',
        ]);

        $validated['activo']                     = $request->has('activo') ? 1 : 0;
        $validated['actualizado_por_usuario_id'] = auth()->id();
        $validated['actualizado_en']             = now();

        $contrato->update($validated);

        return redirect()->route('contrato.show', $contrato)
            ->with('success', 'Contrato actualizado correctamente.');
    }

    public function destroy(Contrato $contrato)
    {
        if ($contrato->estado === 'activo') {
            return back()->with('error', 'No se puede eliminar un contrato activo.');
        }
        $contrato->delete();
        return redirect()->route('contrato.index')
            ->with('success', 'Contrato eliminado correctamente.');
    }
}