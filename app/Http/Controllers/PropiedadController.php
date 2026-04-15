<?php

namespace App\Http\Controllers;

use App\Models\Propiedad;
use Illuminate\Http\Request;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\Storage;
use App\Services\AuditoriaServicio;
>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87

class PropiedadController extends Controller
{
    public function index()
    {
<<<<<<< HEAD
        $propiedades = Propiedad::withCount('unidades')->latest('creado_en')->paginate(10);
=======
        $propiedades = Propiedad::withCount('unidades')
            ->latest()
            ->paginate(10);

>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87
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

<<<<<<< HEAD
        $validated['activo']               = $request->has('activo') ? 1 : 0;
        $validated['creado_por_usuario_id']     = auth()->id();
        $validated['actualizado_por_usuario_id'] = auth()->id();
        $validated['creado_en']            = now();
        $validated['actualizado_en']       = now();
=======
        // 🔥 checkbox activa
        $validated['activa'] = $request->has('activa');

        // 🔥 imagen
        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('propiedades', 'public');
        }

        $propiedad = Propiedad::create($validated);
>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87

        // 🔥 AUDITORÍA CREATE
        AuditoriaServicio::registrar([
            'tabla' => 'propiedades',
            'accion' => 'CREATE',
            'registro_id' => $propiedad->id,
            'datos_nuevos' => $propiedad->toArray()
        ]);

        return redirect()->route('propiedad.index')
            ->with('success', 'Propiedad registrada correctamente.');
    }

    public function show(Propiedad $propiedad)
    {
<<<<<<< HEAD
        $propiedad->load(['unidades']);
=======
        $propiedad->load([
            'unidades',
            'gastos' => fn($q) => $q->latest()->limit(10),
        ]);

>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87
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

<<<<<<< HEAD
        $validated['activo']                     = $request->has('activo') ? 1 : 0;
        $validated['actualizado_por_usuario_id'] = auth()->id();
        $validated['actualizado_en']             = now();
=======
        $antes = $propiedad->toArray();

        $validated['activa'] = $request->has('activa');

        if ($request->hasFile('imagen')) {
            if ($propiedad->imagen) {
                Storage::disk('public')->delete($propiedad->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('propiedades', 'public');
        }
>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87

        $propiedad->update($validated);

        // 🔥 AUDITORÍA UPDATE
        AuditoriaServicio::registrar([
            'tabla' => 'propiedades',
            'accion' => 'UPDATE',
            'registro_id' => $propiedad->id,
            'datos_anteriores' => $antes,
            'datos_nuevos' => $propiedad->toArray()
        ]);

        return redirect()->route('propiedad.show', $propiedad)
            ->with('success', 'Propiedad actualizada correctamente.');
    }

    public function destroy(Propiedad $propiedad)
    {
        if ($propiedad->unidades()->where('estado', 'ocupada')->exists()) {
            return back()->with('error', 'No se puede eliminar una propiedad con unidades ocupadas.');
        }
<<<<<<< HEAD
        $propiedad->delete();
=======

        $antes = $propiedad->toArray();

        $propiedad->delete();

        // 🔥 AUDITORÍA DELETE
        AuditoriaServicio::registrar([
            'tabla' => 'propiedades',
            'accion' => 'DELETE',
            'registro_id' => $propiedad->id,
            'datos_anteriores' => $antes
        ]);

>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87
        return redirect()->route('propiedad.index')
            ->with('success', 'Propiedad eliminada correctamente.');
    }
}