<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Unidad;
use App\Models\Inquilino;
use Illuminate\Http\Request;
use App\Services\AuditoriaServicio;

class ContratoController extends Controller
{
    public function index()
    {
<<<<<<< HEAD
        $contratos = Contrato::with(['unidad.propiedad', 'inquilino'])->latest('creado_en')->paginate(10);
        return view('contrato.index', compact('contratos'));
=======
        $Contrato = Contrato::with(['unidad.propiedad', 'inquilino'])
            ->latest()
            ->paginate(10);

        return view('contrato.index', compact('Contrato'));
>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87
    }

    public function create()
    {
        $unidades   = Unidad::disponibles()->with('propiedad')->get();
        $inquilinos = Inquilino::activos()->get();

        return view('contrato.create', compact('unidades', 'inquilinos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
<<<<<<< HEAD
            'unidad_id'    => 'required|exists:unidades,id',
            'inquilino_id' => 'required|exists:inquilinos,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after:fecha_inicio',
            'monto_renta'  => 'required|numeric|min:0',
            'dia_pago'     => 'required|integer|min:1|max:31',
            'estado'       => 'required|in:activo,terminado,cancelado',
            'activo'       => 'nullable|boolean',
        ]);

        // Verificar que la unidad no tenga contrato activo
        if (Contrato::where('unidad_id', $validated['unidad_id'])->where('estado', 'activo')->exists()) {
            return back()->withInput()->with('error', 'La unidad ya tiene un contrato activo.');
        }

        $validated['activo']                     = $request->has('activo') ? 1 : 0;
        $validated['creado_por_usuario_id']      = auth()->id();
        $validated['actualizado_por_usuario_id'] = auth()->id();
        $validated['creado_en']                  = now();
        $validated['actualizado_en']             = now();
=======
            'unidad_id'              => 'required|exists:unidades,id',
            'inquilino_id'           => 'required|exists:inquilinos,id',
            'fecha_inicio'           => 'required|date',
            'fecha_fin'              => 'required|date|after:fecha_inicio',
            'deposito'               => 'nullable|numeric|min:0',
            'dia_pago'               => 'required|integer|min:1|max:31',
            'estado'                 => 'required|in:activo,vencido,cancelado,pendiente',
            'periodicidad'           => 'required|in:mensual,bimestral,trimestral,semestral,anual',
            'incremento_anual'       => 'nullable|numeric|min:0|max:100',
            'clausulas_adicionales'  => 'nullable|string',
            'observaciones'          => 'nullable|string',
            'renovacion_automatica'  => 'boolean',
        ]);

        // 🔥 VALIDAR UNIDAD
        $unidad = Unidad::findOrFail($validated['unidad_id']);

        // 🔥 VALIDAR CONTRATO ACTIVO
        $tieneContratoActivo = Contrato::where('unidad_id', $unidad->id)
            ->where('estado', 'activo')
            ->exists();

        if ($tieneContratoActivo) {
            return back()
                ->withInput()
                ->with('error', 'La unidad ya tiene un contrato activo.');
        }

        // 🔥 TOMAR MONTO DESDE LA UNIDAD
        $validated['monto_mensual'] = $unidad->precio_renta;

        // 🔥 CODIGO
        $validated['codigo'] = Contrato::generarCodigo();
>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87

        $validated['creado_por_usuario_id'] = auth()->id();
        $validated['actualizado_por_usuario_id'] = auth()->id();

        $contrato = Contrato::create($validated);

        // 🔥 AUDITORIA
        AuditoriaServicio::registrar([
            'tabla' => 'contratos',
            'accion' => 'CREATE',
            'registro_id' => $contrato->id,
            'datos_nuevos' => $contrato->toArray()
        ]);

        return redirect()->route('contrato.index')
            ->with('success', 'Contrato creado correctamente.');
    }

    public function show(Contrato $contrato)
    {
        $contrato->load(['unidad.propiedad', 'inquilino']);
        return view('contrato.show', compact('contrato'));
    }

    public function edit(Contrato $contrato)
    {
        $unidades   = Unidad::with('propiedad')->get();
        $inquilinos = Inquilino::activos()->get();

        return view('contrato.edit', compact('contrato', 'unidades', 'inquilinos'));
    }

    public function update(Request $request, Contrato $contrato)
    {
        $validated = $request->validate([
<<<<<<< HEAD
            'unidad_id'    => 'required|exists:unidades,id',
            'inquilino_id' => 'required|exists:inquilinos,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after:fecha_inicio',
            'monto_renta'  => 'required|numeric|min:0',
            'dia_pago'     => 'required|integer|min:1|max:31',
            'estado'       => 'required|in:activo,terminado,cancelado',
            'activo'       => 'nullable|boolean',
        ]);

        $validated['activo']                     = $request->has('activo') ? 1 : 0;
        $validated['actualizado_por_usuario_id'] = auth()->id();
        $validated['actualizado_en']             = now();
=======
            'unidad_id'              => 'required|exists:unidades,id',
            'inquilino_id'           => 'required|exists:inquilinos,id',
            'fecha_inicio'           => 'required|date',
            'fecha_fin'              => 'required|date|after:fecha_inicio',
            'deposito'               => 'nullable|numeric|min:0',
            'dia_pago'               => 'required|integer|min:1|max:31',
            'estado'                 => 'required|in:activo,vencido,cancelado,pendiente',
            'periodicidad'           => 'required|in:mensual,bimestral,trimestral,semestral,anual',
            'incremento_anual'       => 'nullable|numeric|min:0|max:100',
            'clausulas_adicionales'  => 'nullable|string',
            'observaciones'          => 'nullable|string',
            'renovacion_automatica'  => 'boolean',
        ]);

        $antes = $contrato->toArray();

        // 🔥 ACTUALIZAR MONTO DESDE UNIDAD SI CAMBIA
        $unidad = Unidad::findOrFail($validated['unidad_id']);
        $validated['monto_mensual'] = $unidad->precio_renta;

        $validated['actualizado_por_usuario_id'] = auth()->id();
>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87

        $contrato->update($validated);

        // 🔥 AUDITORIA
        AuditoriaServicio::registrar([
            'tabla' => 'contratos',
            'accion' => 'UPDATE',
            'registro_id' => $contrato->id,
            'datos_anteriores' => $antes,
            'datos_nuevos' => $contrato->toArray()
        ]);

        return redirect()->route('contrato.show', $contrato)
            ->with('success', 'Contrato actualizado correctamente.');
    }

    public function destroy(Contrato $contrato)
    {
        if ($contrato->estado === 'activo') {
            return back()->with('error', 'No se puede eliminar un contrato activo.');
        }
<<<<<<< HEAD
        $contrato->delete();
=======

        $antes = $contrato->toArray();

        $contrato->delete();

        // 🔥 AUDITORIA
        AuditoriaServicio::registrar([
            'tabla' => 'contratos',
            'accion' => 'DELETE',
            'registro_id' => $contrato->id,
            'datos_anteriores' => $antes,
            'datos_nuevos' => null
        ]);

>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87
        return redirect()->route('contrato.index')
            ->with('success', 'Contrato eliminado correctamente.');
    }
}