<?php

namespace App\Http\Controllers;

use App\Models\Unidad;
use App\Models\Propiedad;
use Illuminate\Http\Request;
use App\Services\AuditoriaServicio; // 🔥 ajusta si tu servicio está en otro namespace

class UnidadController extends Controller
{
    public function index()
    {
        $unidades = Unidad::with('propiedad')
            ->latest('id')
            ->paginate(10);

        return view('unidad.index', compact('unidades'));
    }

    public function create()
    {
        $propiedades = Propiedad::activas()->get();
        return view('unidad.create', compact('propiedades'));
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

        $unidad = Unidad::create($validated);

        // 🔥 AUDITORÍA
        app(AuditoriaServicio::class)->registrar([
            'usuario_id' => auth()->id(),
            'tabla' => 'unidades',
            'accion' => 'CREATE',
            'registro_id' => $unidad->id,
            'datos_anteriores' => null,
            'datos_nuevos' => $unidad->toArray(),
            'ip' => $request->ip()
        ]);

        return redirect()->route('unidad.index')
            ->with('success', 'Unidad registrada correctamente.');
    }

    public function show(Unidad $unidad)
    {
        $unidad->load(['propiedad', 'contratos.inquilino', 'gastos']);
        $propiedades = Propiedad::activas()->get(); // 👈 agregar

        return view('unidad.show', compact('unidad', 'propiedades'));
    }

    public function edit(Unidad $unidad)
    {
        $propiedades = Propiedad::activas()->get();

        return view('unidad.edit', compact('unidad', 'propiedades'));
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

        $antes = $unidad->toArray();

        $unidad->update($validated);

        // 🔥 AUDITORÍA
        app(AuditoriaServicio::class)->registrar([
            'usuario_id' => auth()->id(),
            'tabla' => 'unidades',
            'accion' => 'UPDATE',
            'registro_id' => $unidad->id,
            'datos_anteriores' => $antes,
            'datos_nuevos' => $unidad->toArray(),
            'ip' => $request->ip()
        ]);

        return redirect()->route('unidad.show', $unidad)
            ->with('success', 'Unidad actualizada correctamente.');
    }

    public function destroy(Request $request, Unidad $unidad)
    {
        if ($unidad->estado === 'ocupada') {
            return back()->with('error', 'No se puede eliminar una unidad ocupada.');
        }

        $antes = $unidad->toArray();

        $unidad->delete();

        // 🔥 AUDITORÍA
        app(AuditoriaServicio::class)->registrar([
            'usuario_id' => auth()->id(),
            'tabla' => 'unidades',
            'accion' => 'DELETE',
            'registro_id' => $unidad->id,
            'datos_anteriores' => $antes,
            'datos_nuevos' => null,
            'ip' => $request->ip()
        ]);

        return redirect()->route('unidad.index')
            ->with('success', 'Unidad eliminada correctamente.');
    }
}