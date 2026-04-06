<?php

namespace App\Http\Controllers;

use App\Models\unidades;
use App\Models\propiedades;
use Illuminate\Http\Request;

class UnidadesController extends Controller
{
    public function index()
    {
        $unidades = Unidad::with('propiedad')
            ->latest()
            ->paginate(10);

        return view('unidades.index', compact('unidades'));
    }

    public function create()
    {
        $propiedades = Propiedad::activas()->get();
        return view('unidades.create', compact('propiedades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'propiedad_id'   => 'required|exists:propiedades,id',
            'nombre'         => 'required|string|max:255',
            'numero'         => 'nullable|string|max:50',
            'tipo'           => 'required|in:apartamento,casa,habitacion,local,oficina,bodega,otro',
            'area'           => 'nullable|numeric|min:0',
            'habitaciones'   => 'required|integer|min:0',
            'banos'          => 'required|integer|min:0',
            'tiene_parqueo'  => 'boolean',
            'precio_renta'   => 'required|numeric|min:0',
            'estado'         => 'required|in:disponible,ocupada,en_mantenimiento,inactiva',
            'descripcion'    => 'nullable|string',
            'piso'           => 'nullable|integer',
        ]);

        Unidad::create($validated);

        return redirect()->route('unidades.index')
            ->with('success', 'Unidad registrada correctamente.');
    }

    public function show(Unidad $unidad)
    {
        $unidad->load(['propiedad', 'contratos.inquilino', 'gastos']);
        return view('unidades.show', compact('unidad'));
    }

    public function edit(Unidad $unidad)
    {
        $propiedades = Propiedad::activas()->get();
        return view('unidades.edit', compact('unidad', 'propiedades'));
    }

    public function update(Request $request, Unidad $unidad)
    {
        $validated = $request->validate([
            'propiedad_id'   => 'required|exists:propiedades,id',
            'nombre'         => 'required|string|max:255',
            'numero'         => 'nullable|string|max:50',
            'tipo'           => 'required|in:apartamento,casa,habitacion,local,oficina,bodega,otro',
            'area'           => 'nullable|numeric|min:0',
            'habitaciones'   => 'required|integer|min:0',
            'banos'          => 'required|integer|min:0',
            'tiene_parqueo'  => 'boolean',
            'precio_renta'   => 'required|numeric|min:0',
            'estado'         => 'required|in:disponible,ocupada,en_mantenimiento,inactiva',
            'descripcion'    => 'nullable|string',
            'piso'           => 'nullable|integer',
        ]);

        $unidad->update($validated);

        return redirect()->route('unidades.show', $unidad)
            ->with('success', 'Unidad actualizada correctamente.');
    }

    public function destroy(Unidad $unidad)
    {
        if ($unidad->estado === 'ocupada') {
            return back()->with('error', 'No se puede eliminar una unidad ocupada.');
        }

        $unidad->delete();

        return redirect()->route('unidades.index')
            ->with('success', 'Unidad eliminada correctamente.');
    }
}
