<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\Propiedad;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\AuditoriaServicio;

class GastoController extends Controller
{
    public function index()
    {
        $gastos = Gasto::with(['propiedad', 'unidad'])
            ->latest()
            ->paginate(10);

        $totalPendiente = Gasto::pendientes()->sum('monto');
        $totalMes       = Gasto::delMes()->sum('monto');

        return view('gasto.index', compact('gastos', 'totalPendiente', 'totalMes'));
    }

    public function create()
    {
        $propiedades = Propiedad::activas()->get();
        $unidades    = Unidad::with('propiedad')->get();

        return view('gasto.create', compact('propiedades', 'unidades'));
    }

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

        if ($request->hasFile('archivo_adjunto')) {
            $validated['archivo_adjunto'] = $request->file('archivo_adjunto')
                ->store('gastos', 'public');
        }

        if (!empty($validated['unidad_id'])) {
            $unidad = Unidad::findOrFail($validated['unidad_id']);

            if ($unidad->propiedad_id != $validated['propiedad_id']) {
                return back()->withInput()
                    ->with('error', 'La unidad no pertenece a la propiedad seleccionada.');
            }
        }
        $gasto = Gasto::create(array_merge($validated, [
            'creado_por_usuario_id' => auth()->id(),
            'actualizado_por_usuario_id' => auth()->id(),
            'creado_en' => now(),
            'actualizado_en' => now(),
        ]));

        AuditoriaServicio::registrar([
            'usuario_id' => auth()->id(),
            'tabla' => 'gastos',
            'accion' => 'CREATE',
            'registro_id' => $gasto->id,
            'datos_anteriores' => null,
            'datos_nuevos' => $gasto->toArray(),
            'ip' => request()->ip()
        ]);

        return redirect()->route('gasto.index')
            ->with('success', 'Gasto registrado correctamente.');
    }

    public function show(Gasto $gasto)
    {
        $gasto->load(['propiedad', 'unidad']);
        return view('gasto.show', compact('gasto'));
    }

    public function edit(Gasto $gasto)
    {
        $propiedades = Propiedad::activas()->get();
        $unidades    = Unidad::with('propiedad')->get();

        return view('gasto.edit', compact('gasto', 'propiedades', 'unidades'));
    }

    public function update(Request $request, Gasto $gasto)
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

        $antes = $gasto->toArray();

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

    public function destroy(Gasto $gasto)
    {
        $antes = $gasto->toArray();

        if ($gasto->archivo_adjunto) {
            Storage::disk('public')->delete($gasto->archivo_adjunto);
        }

        $gasto->delete();

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