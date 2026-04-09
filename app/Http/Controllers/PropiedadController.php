<?php

namespace App\Http\Controllers;

use App\Models\Propiedad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropiedadController extends Controller
{
    public function index()
    {
        $propiedad = Propiedad::withCount('unidades')
            ->with('unidades')
            ->latest()
            ->paginate(10);

        return view('propiedad.index', compact('propiedad'));
    }

    public function create()
    {
        return view('propiedad.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'        => 'required|string|max:255',
            'direccion'     => 'required|string|max:255',
            'ciudad'        => 'required|string|max:100',
            'departamento'  => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|max:20',
            'tipo'          => 'required|in:casa,apartamento,local_comercial,edificio,otro',
            'descripcion'   => 'nullable|string',
            'area_total'    => 'nullable|numeric|min:0',
            'imagen'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'activa'        => 'boolean',
        ]);

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('propiedades', 'public');
        }

        $validated['user_id'] = auth()->id();

        Propiedad::create($validated);

        return redirect()->route('propiedad.index')
            ->with('success', 'Propiedad registrada correctamente.');
    }

    public function show(Propiedad $propiedad)
    {
        $propiedad->load([
            'unidad',
            'gasto' => fn($q) => $q->latest()->limit(10),
        ]);

        return view('propiedad.show', compact('propiedad'));
    }

    public function edit(Propiedad $propiedad)
    {
        return view('propiedad.edit', compact('propiedad'));
    }

    public function update(Request $request, Propiedad $propiedad)
    {
        $validated = $request->validate([
            'nombre'        => 'required|string|max:255',
            'direccion'     => 'required|string|max:255',
            'ciudad'        => 'required|string|max:100',
            'departamento'  => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|max:20',
            'tipo'          => 'required|in:casa,apartamento,local_comercial,edificio,otro',
            'descripcion'   => 'nullable|string',
            'area_total'    => 'nullable|numeric|min:0',
            'imagen'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'activa'        => 'boolean',
        ]);

        if ($request->hasFile('imagen')) {
            if ($propiedad->imagen) {
                Storage::disk('public')->delete($propiedad->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('propiedad', 'public');
        }

        $propiedad->update($validated);

        return redirect()->route('propiedad.show', $propiedad)
            ->with('success', 'Propiedad actualizada correctamente.');
    }

    public function destroy(Propiedad $propiedad)
    {
        if ($propiedad->unidad()->whereIn('estado', ['ocupada'])->exists()) {
            return back()->with('error', 'No se puede eliminar una propiedad con unidades ocupadas.');
        }

        $propiedad->delete();

        return redirect()->route('propiedad.index')
            ->with('success', 'Propiedad eliminada correctamente.');
    }
}
