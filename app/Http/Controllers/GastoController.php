<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\Unidad;
use Illuminate\Http\Request;

class GastoController extends Controller
{
    public function index()
    {
        $gastos      = Gasto::with('unidad.propiedad')->latest('creado_en')->paginate(10);
        $totalMes    = Gasto::delMes()->sum('monto');
        return view('gasto.index', compact('gastos', 'totalMes'));
    }

    public function create()
    {
        $unidades = Unidad::activas()->with('propiedad')->get();
        return view('gasto.create', compact('unidades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unidad_id'       => 'required|exists:unidades,id',
            'fecha_gasto'     => 'required|date',
            'monto'           => 'required|numeric|min:0',
            'tipo'            => 'required|in:mantenimiento,reparacion,compra,servicio,otro',
            'descripcion'     => 'nullable|string|max:255',
            'observaciones'   => 'nullable|string',
            'comprobante_url' => 'nullable|string|max:255',
            'activo'          => 'nullable|boolean',
        ]);

        $validated['activo']                     = $request->has('activo') ? 1 : 0;
        $validated['creado_por_usuario_id']      = auth()->id();
        $validated['actualizado_por_usuario_id'] = auth()->id();
        $validated['creado_en']                  = now();
        $validated['actualizado_en']             = now();

        Gasto::create($validated);

        return redirect()->route('gasto.index')
            ->with('success', 'Gasto registrado correctamente.');
    }

    public function show(Gasto $gasto)
    {
        $gasto->load('unidad.propiedad');
        return view('gasto.show', compact('gasto'));
    }

    public function edit(Gasto $gasto)
    {
        $unidades = Unidad::activas()->with('propiedad')->get();
        return view('gasto.edit', compact('gasto', 'unidades'));
    }

    public function update(Request $request, Gasto $gasto)
    {
        $validated = $request->validate([
            'unidad_id'       => 'required|exists:unidades,id',
            'fecha_gasto'     => 'required|date',
            'monto'           => 'required|numeric|min:0',
            'tipo'            => 'required|in:mantenimiento,reparacion,compra,servicio,otro',
            'descripcion'     => 'nullable|string|max:255',
            'observaciones'   => 'nullable|string',
            'comprobante_url' => 'nullable|string|max:255',
            'activo'          => 'nullable|boolean',
        ]);

        $validated['activo']                     = $request->has('activo') ? 1 : 0;
        $validated['actualizado_por_usuario_id'] = auth()->id();
        $validated['actualizado_en']             = now();

        $gasto->update($validated);

        return redirect()->route('gasto.show', $gasto)
            ->with('success', 'Gasto actualizado correctamente.');
    }

    public function destroy(Gasto $gasto)
    {
        $gasto->delete();
        return redirect()->route('gasto.index')
            ->with('success', 'Gasto eliminado correctamente.');
    }
}