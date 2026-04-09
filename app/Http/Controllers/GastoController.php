<?php

namespace App\Http\Controllers;

use App\Models\gasto;
use App\Models\propiedad;
use App\Models\unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GastosController extends Controller
{
    public function index()
    {
        $gastos = Gasto::with(['propiedad', 'unidad'])
            ->latest()
            ->paginate(10);

        $totalPendiente = Gasto::pendientes()->sum('monto');
        $totalMes       = Gasto::delMes()->sum('monto');

        return view('gastos.index', compact('gastos', 'totalPendiente', 'totalMes'));
    }

    public function create()
    {
        $propiedades = Propiedad::activas()->get();
        $unidades    = Unidad::with('propiedad')->get();
        return view('gastos.create', compact('propiedades', 'unidades'));
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

        // Validar que la unidad pertenece a la propiedad seleccionada
        if (!empty($validated['unidad_id'])) {
            $unidad = Unidad::findOrFail($validated['unidad_id']);
            if ($unidad->propiedad_id != $validated['propiedad_id']) {
                return back()->withInput()
                    ->with('error', 'La unidad no pertenece a la propiedad seleccionada.');
            }
        }

        Gasto::create($validated);

        return redirect()->route('gastos.index')
            ->with('success', 'Gasto registrado correctamente.');
    }

    public function show(Gasto $gasto)
    {
        $gasto->load(['propiedad', 'unidad']);
        return view('gastos.show', compact('gasto'));
    }

    public function edit(Gasto $gasto)
    {
        $propiedades = Propiedad::activas()->get();
        $unidades    = Unidad::with('propiedad')->get();
        return view('gastos.edit', compact('gasto', 'propiedades', 'unidades'));
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

        if ($request->hasFile('archivo_adjunto')) {
            if ($gasto->archivo_adjunto) {
                Storage::disk('public')->delete($gasto->archivo_adjunto);
            }
            $validated['archivo_adjunto'] = $request->file('archivo_adjunto')
                ->store('gastos', 'public');
        }

        $gasto->update($validated);

        return redirect()->route('gastos.show', $gasto)
            ->with('success', 'Gasto actualizado correctamente.');
    }

    public function destroy(Gasto $gasto)
    {
        if ($gasto->archivo_adjunto) {
            Storage::disk('public')->delete($gasto->archivo_adjunto);
        }

        $gasto->delete();

        return redirect()->route('gastos.index')
            ->with('success', 'Gasto eliminado correctamente.');
    }
}
