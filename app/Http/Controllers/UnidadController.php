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
<<<<<<< HEAD
        $unidades = Unidad::with('propiedad')->latest('creado_en')->paginate(10);
=======
        $unidades = Unidad::with('propiedad')
            ->latest('id')
            ->paginate(10);

>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87
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
            'propiedad_id'  => 'required|exists:propiedades,id',
            'identificador' => 'required|string|max:50',
            'estado'        => 'required|in:disponible,ocupada,mantenimiento',
            'monto_renta'   => 'required|numeric|min:0',
            'activo'        => 'nullable|boolean',
        ]);

<<<<<<< HEAD
        $validated['activo']                     = $request->has('activo') ? 1 : 0;
        $validated['creado_por_usuario_id']      = auth()->id();
        $validated['actualizado_por_usuario_id'] = auth()->id();
        $validated['creado_en']                  = now();
        $validated['actualizado_en']             = now();

        Unidad::create($validated);
=======
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
>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87

        return redirect()->route('unidad.index')
            ->with('success', 'Unidad registrada correctamente.');
    }

    public function show(Unidad $unidad)
    {
        $unidad->load(['propiedad', 'contratos.inquilino', 'gastos']);
<<<<<<< HEAD
        return view('unidad.show', compact('unidad'));
=======
        $propiedades = Propiedad::activas()->get(); // 👈 agregar

        return view('unidad.show', compact('unidad', 'propiedades'));
>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87
    }

    public function edit(Unidad $unidad)
    {
        $propiedades = Propiedad::activas()->get();
<<<<<<< HEAD
=======

>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87
        return view('unidad.edit', compact('unidad', 'propiedades'));
    }

    public function update(Request $request, Unidad $unidad)
    {
        $validated = $request->validate([
            'propiedad_id'  => 'required|exists:propiedades,id',
            'identificador' => 'required|string|max:50',
            'estado'        => 'required|in:disponible,ocupada,mantenimiento',
            'monto_renta'   => 'required|numeric|min:0',
            'activo'        => 'nullable|boolean',
        ]);

<<<<<<< HEAD
        $validated['activo']                     = $request->has('activo') ? 1 : 0;
        $validated['actualizado_por_usuario_id'] = auth()->id();
        $validated['actualizado_en']             = now();
=======
        $antes = $unidad->toArray();
>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87

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
<<<<<<< HEAD
        $unidad->delete();
=======

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

>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87
        return redirect()->route('unidad.index')
            ->with('success', 'Unidad eliminada correctamente.');
    }
}