<?php

namespace App\Http\Controllers;

use App\Models\Inquilino;
use Illuminate\Http\Request;

class InquilinoController extends Controller
{
    public function index()
    {
        $inquilinos = Inquilino::withCount('contratos')->latest('creado_en')->paginate(10);
        return view('inquilino.index', compact('inquilinos'));
    }

    public function create()
    {
        return view('inquilino.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'   => 'required|string|max:150',
            'telefono' => 'nullable|string|max:20',
            'correo'   => 'nullable|email|max:100|unique:inquilinos,correo',
            'foto_url' => 'nullable|string|max:255',
            'activo'   => 'nullable|boolean',
        ]);

        $validated['activo']                     = $request->has('activo') ? 1 : 0;
        $validated['creado_por_usuario_id']      = auth()->id();
        $validated['actualizado_por_usuario_id'] = auth()->id();
        $validated['creado_en']                  = now();
        $validated['actualizado_en']             = now();

        Inquilino::create($validated);

        return redirect()->route('inquilino.index')
            ->with('success', 'Inquilino registrado correctamente.');
    }

    public function show(Inquilino $inquilino)
    {
        $inquilino->load(['contratos.unidad.propiedad']);
        return view('inquilino.show', compact('inquilino'));
    }

    public function edit(Inquilino $inquilino)
    {
        return view('inquilino.edit', compact('inquilino'));
    }

    public function update(Request $request, Inquilino $inquilino)
    {
        $validated = $request->validate([
            'nombre'   => 'required|string|max:150',
            'telefono' => 'nullable|string|max:20',
            'correo'   => 'nullable|email|max:100|unique:inquilinos,correo,' . $inquilino->id,
            'foto_url' => 'nullable|string|max:255',
            'activo'   => 'nullable|boolean',
        ]);

        $validated['activo']                     = $request->has('activo') ? 1 : 0;
        $validated['actualizado_por_usuario_id'] = auth()->id();
        $validated['actualizado_en']             = now();

        $inquilino->update($validated);

        return redirect()->route('inquilino.show', $inquilino)
            ->with('success', 'Inquilino actualizado correctamente.');
    }

    public function destroy(Inquilino $inquilino)
    {
        if ($inquilino->contratos()->where('estado', 'activo')->exists()) {
            return back()->with('error', 'No se puede eliminar un inquilino con contratos activos.');
        }
        $inquilino->delete();
        return redirect()->route('inquilino.index')
            ->with('success', 'Inquilino eliminado correctamente.');
    }
}