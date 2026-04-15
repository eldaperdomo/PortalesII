<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\Unidad;
use Illuminate\Http\Request;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\Storage;
use App\Services\AuditoriaServicio;
>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87

class GastoController extends Controller
{
    // 🔥 LISTAR
    public function index()
    {
<<<<<<< HEAD
        $gastos      = Gasto::with('unidad.propiedad')->latest('creado_en')->paginate(10);
        $totalMes    = Gasto::delMes()->sum('monto');
        return view('gasto.index', compact('gastos', 'totalMes'));
=======
        $gastos = Gasto::with(['propiedad', 'unidad'])
            ->latest()
            ->paginate(10);

        $totalPendiente = Gasto::pendientes()->sum('monto');
        $totalMes       = Gasto::delMes()->sum('monto');

        return view('gasto.index', compact('gastos', 'totalPendiente', 'totalMes'));
>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87
    }

    // 🔥 CREAR
    public function create()
    {
<<<<<<< HEAD
        $unidades = Unidad::activas()->with('propiedad')->get();
        return view('gasto.create', compact('unidades'));
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'unidad_id' => 'required|exists:unidades,id',
        'fecha' => 'required|date',
        'monto' => 'required|numeric|min:0',
        'categoria' => 'required|in:mantenimiento,reparacion,compra,servicio,otro',
        'descripcion' => 'nullable|string|max:255',
        'observaciones' => 'nullable|string',
        'comprobante' => 'nullable|string|max:255',
        'activo' => 'nullable|boolean',
    ]);

    // 🔥 OBTENER LA UNIDAD
    $unidad = Unidad::find($validated['unidad_id']);

    // 🔥 AGREGAR PROPIEDAD AUTOMÁTICAMENTE
    $validated['propiedad_id'] = $unidad->propiedad_id;

    $validated['activo'] = $request->has('activo') ? 1 : 0;
    $validated['creado_por_usuario_id'] = auth()->id();
    $validated['actualizado_por_usuario_id'] = auth()->id();
    $validated['creado_en'] = now();
    $validated['actualizado_en'] = now();
=======
        $propiedades = Propiedad::activas()->get();
        $unidades    = Unidad::with('propiedad')->get();

        return view('gasto.create', compact('propiedades', 'unidades'));
    }

    // 🔥 GUARDAR
    public function store(Request $request)
    {
        $validated = $request->validate([
            'propiedad_id'    => 'required|exists:propiedades,id',
            'unidad_id'       => 'nullable|exists:unidades,id',
            'concepto'        => 'required|string|max:255',
            'monto'           => 'required|numeric|min:0',
            'fecha'           => 'required|date',
            'categoria'       => 'required|in:mantenimiento,reparacion,impuesto,seguro,servicios,administracion,limpieza,otro',
            'estado'          => 'required|in:pendiente,pagado,cancelado',
            'proveedor'       => 'nullable|string|max:150',
            'comprobante'     => 'nullable|string|max:100',
            'archivo_adjunto' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'descripcion'     => 'nullable|string',
        ]);

        // 🔥 archivo
        if ($request->hasFile('archivo_adjunto')) {
            $validated['archivo_adjunto'] = $request->file('archivo_adjunto')
                ->store('gastos', 'public');
        }

        // 🔥 validar unidad pertenece a propiedad
        if (!empty($validated['unidad_id'])) {
            $unidad = Unidad::findOrFail($validated['unidad_id']);

            if ($unidad->propiedad_id != $validated['propiedad_id']) {
                return back()->withInput()
                    ->with('error', 'La unidad no pertenece a la propiedad seleccionada.');
            }
        }

        // 🔥 crear
        $gasto = Gasto::create(array_merge($validated, [
            'creado_por_usuario_id' => auth()->id(),
            'actualizado_por_usuario_id' => auth()->id(),
            'creado_en' => now(),
            'actualizado_en' => now(),
        ]));

        // 🔥 auditoría
        AuditoriaServicio::registrar([
            'usuario_id' => auth()->id(),
            'tabla' => 'gastos',
            'accion' => 'CREATE',
            'registro_id' => $gasto->id,
            'datos_anteriores' => null,
            'datos_nuevos' => $gasto->toArray(),
            'ip' => request()->ip()
        ]);
>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87

    Gasto::create($validated);

    return redirect()->route('gasto.index')
        ->with('success', 'Gasto registrado correctamente.');
}

    // 🔥 VER
    public function show(Gasto $gasto)
    {
        $gasto->load('unidad.propiedad');
        return view('gasto.show', compact('gasto'));
    }

    // 🔥 EDITAR
    public function edit(Gasto $gasto)
    {
<<<<<<< HEAD
        $unidades = Unidad::activas()->with('propiedad')->get();
        return view('gasto.edit', compact('gasto', 'unidades'));
=======
        $propiedades = Propiedad::activas()->get();
        $unidades    = Unidad::with('propiedad')->get();

        return view('gasto.edit', compact('gasto', 'propiedades', 'unidades'));
>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87
    }

    // 🔥 ACTUALIZAR
    public function update(Request $request, Gasto $gasto)
    {
        $unidad = Unidad::find($validated['unidad_id']);
        $validated['propiedad_id'] = $unidad->propiedad_id;

        $validated = $request->validate([
            'unidad_id'       => 'required|exists:unidades,id',
            'fecha'     => 'required|date',
            'monto'           => 'required|numeric|min:0',
            'categoria'            => 'required|in:mantenimiento,reparacion,compra,servicio,otro',
            'descripcion'     => 'nullable|string|max:255',
            'observaciones'   => 'nullable|string',
            'comprobante' => 'nullable|string|max:255',
            'activo'          => 'nullable|boolean',
        ]);

<<<<<<< HEAD
        $validated['activo']                     = $request->has('activo') ? 1 : 0;
        $validated['actualizado_por_usuario_id'] = auth()->id();
        $validated['actualizado_en']             = now();
=======
        $antes = $gasto->toArray();

        // 🔥 archivo
        if ($request->hasFile('archivo_adjunto')) {
            if ($gasto->archivo_adjunto) {
                Storage::disk('public')->delete($gasto->archivo_adjunto);
            }

            $validated['archivo_adjunto'] = $request->file('archivo_adjunto')
                ->store('gastos', 'public');
        }
>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87

        $gasto->update(array_merge($validated, [
            'actualizado_por_usuario_id' => auth()->id(),
            'actualizado_en' => now(),
        ]));

        // 🔥 auditoría
        AuditoriaServicio::registrar([
            'usuario_id' => auth()->id(),
            'tabla' => 'gastos',
            'accion' => 'UPDATE',
            'registro_id' => $gasto->id,
            'datos_anteriores' => $antes,
            'datos_nuevos' => $gasto->toArray(),
            'ip' => request()->ip()
        ]);

        return redirect()->route('gasto.show', $gasto)
            ->with('success', 'Gasto actualizado correctamente.');
    }

    // 🔥 ELIMINAR
    public function destroy(Gasto $gasto)
    {
<<<<<<< HEAD
        $gasto->delete();
=======
        $antes = $gasto->toArray();

        if ($gasto->archivo_adjunto) {
            Storage::disk('public')->delete($gasto->archivo_adjunto);
        }

        $gasto->delete();

        // 🔥 auditoría
        AuditoriaServicio::registrar([
            'usuario_id' => auth()->id(),
            'tabla' => 'gastos',
            'accion' => 'DELETE',
            'registro_id' => $gasto->id,
            'datos_anteriores' => $antes,
            'datos_nuevos' => null,
            'ip' => request()->ip()
        ]);

>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87
        return redirect()->route('gasto.index')
            ->with('success', 'Gasto eliminado correctamente.');
    }
}