<?php

namespace App\Http\Controllers;

use App\Models\Propiedad;
use Illuminate\Http\Request;

class PropiedadController extends Controller
{
    public function index()
    {
        $propiedades = Propiedad::withCount('unidades')->latest('creado_en')->paginate(10);
        return view('propiedad.index', compact('propiedades'));
    }

    public function create()
    {
        return view('propiedad.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:150',
            'direccion'   => 'nullable|string|max:255',
            'tipo'        => 'required|in:casa,edificio',
            'descripcion' => 'nullable|string',
            'activo'      => 'nullable|boolean',
        ]);

        $validated['activo']               = $request->has('activo') ? 1 : 0;
        $validated['creado_por_usuario_id']     = auth()->id();
        $validated['actualizado_por_usuario_id'] = auth()->id();
        $validated['creado_en']            = now();
        $validated['actualizado_en']       = now();

        Propiedad::create($validated);

        return redirect()->route('propiedad.index')
            ->with('success', 'Propiedad registrada correctamente.');
    }

    public function show(Propiedad $propiedad)
    {
        $propiedad->load(['unidades']);
        return view('propiedad.show', compact('propiedad'));
    }

    public function edit(Propiedad $propiedad)
    {
        return view('propiedad.edit', compact('propiedad'));
    }

    public function update(Request $request, Propiedad $propiedad)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:150',
            'direccion'   => 'nullable|string|max:255',
            'tipo'        => 'required|in:casa,edificio',
            'descripcion' => 'nullable|string',
            'activo'      => 'nullable|boolean',
        ]);

        $validated['activo']                     = $request->has('activo') ? 1 : 0;
        $validated['actualizado_por_usuario_id'] = auth()->id();
        $validated['actualizado_en']             = now();

        $propiedad->update($validated);

        return redirect()->route('propiedad.show', $propiedad)
            ->with('success', 'Propiedad actualizada correctamente.');
    }

    public function destroy(Propiedad $propiedad)
    {
        if ($propiedad->unidades()->where('estado', 'ocupada')->exists()) {
            return back()->with('error', 'No se puede eliminar una propiedad con unidades ocupadas.');
        }
        $propiedad->delete();
        return redirect()->route('propiedad.index')
            ->with('success', 'Propiedad eliminada correctamente.');
    }
}