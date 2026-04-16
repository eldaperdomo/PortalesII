<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\Unidad;
use App\Models\Propiedad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\AuditoriaServicio;

class GastoController extends Controller
{
    // 🔥 LISTAR
    public function index()
    {
        $gastos = Gasto::with(['propiedad', 'unidad'])
            ->latest()
            ->paginate(10);

        $totalPendiente = Gasto::pendientes()->sum('monto');
        $totalMes       = Gasto::delMes()->sum('monto');

        return view('gasto.index', compact('gastos', 'totalPendiente', 'totalMes'));
    }

    // 🔥 CREAR
    public function create()
    {
        $propiedades = Propiedad::activas()->get();
        $unidades    = Unidad::with('propiedad')->get();

        return view('gasto.create', compact('propiedades', 'unidades'));
    }

    // 🔥 GUARDAR
public function store(Request $request)
{
    // ✅ Validación acorde al formulario
    $validated = $request->validate([
        'unidad_id'     => 'required|exists:unidades,id',
        'fecha'         => 'required|date',
        'monto'         => 'required|numeric|min:0',
        'categoria'     => 'required|in:mantenimiento,reparacion,compra,servicio,otro',
        'descripcion'   => 'nullable|string|max:255',
        'observaciones' => 'nullable|string',
        'comprobante'   => 'nullable|string|max:255',
        'activo'        => 'nullable|boolean',
    ]);

    // ✅ Obtener unidad y asignar propiedad automáticamente
    $unidad = Unidad::findOrFail($validated['unidad_id']);
    $validated['propiedad_id'] = $unidad->propiedad_id;

    // ✅ Estado por defecto
    $validated['estado'] = 'pendiente';

    // ✅ Manejo de checkbox (activo)
    $validated['activo'] = $request->has('activo') ? 1 : 0;

    // ✅ Crear gasto
    $gasto = Gasto::create([
        ...$validated,
        'creado_por_usuario_id'      => auth()->id(),
        'actualizado_por_usuario_id'=> auth()->id(),
        'creado_en'                 => now(),
        'actualizado_en'            => now(),
    ]);

    // ✅ Auditoría
    AuditoriaServicio::registrar([
        'usuario_id'       => auth()->id(),
        'tabla'            => 'gastos',
        'accion'           => 'CREATE',
        'registro_id'      => $gasto->id,
        'datos_anteriores' => null,
        'datos_nuevos'     => $gasto->toArray(),
        'ip'               => $request->ip()
    ]);

    // ✅ Redirección
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
        $propiedades = Propiedad::activas()->get();
        $unidades    = Unidad::with('propiedad')->get();

        return view('gasto.edit', compact('gasto', 'propiedades', 'unidades'));
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

        $antes = $gasto->toArray();

        // 🔥 archivo
        if ($request->hasFile('archivo_adjunto')) {
            if ($gasto->archivo_adjunto) {
                Storage::disk('public')->delete($gasto->archivo_adjunto);
            }

            $validated['archivo_adjunto'] = $request->file('archivo_adjunto')
                ->store('gastos', 'public');
        }

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

        return redirect()->route('gasto.index')
            ->with('success', 'Gasto eliminado correctamente.');
    }
}