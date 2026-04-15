<?php

namespace App\Http\Controllers;

use App\Models\SolicitudInquilino;
use App\Models\Unidad;
use App\Models\Contrato;
use App\Models\AuditoriaLog;
use Illuminate\Http\Request;

class SolicitudInquilinoController extends Controller
{

    // 🔥 LISTAR
    public function index(Request $request)
    {
        $query = SolicitudInquilino::with(['inquilino', 'unidad']);

        // 🔥 activos / inactivos
        if ($request->incluir_inactivos === 'true') {

            if (!auth()->user()->esAdmin()) {
                abort(403);
            }

        } else {
            $query->where('activo', true);
        }

        // 🔥 filtros
        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        if ($request->prioridad) {
            $query->where('prioridad', $request->prioridad);
        }

        if ($request->tipo) {
            $query->where('tipo', $request->tipo);
        }

        // 🔥 🔥 BUSCADOR PRO
        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('asunto', 'like', "%$search%")
                  ->orWhere('tipo', 'like', "%$search%");

                $q->orWhereHas('inquilino', function ($q2) use ($search) {
                    $q2->where('nombre', 'like', "%$search%");
                });

                $q->orWhereHas('unidad', function ($q3) use ($search) {
                       $q3->where('nombre', 'like', "%$search%");
                });

            });
        }

        $solicitudes = $query->latest()->paginate(10)->withQueryString();

        return view('solicitudes.index', compact('solicitudes'));
    }

    // 🔥 FORM CREAR
    public function create()
    {
        $unidades = Unidad::with(['contratos.inquilino'])->get();

        return view('solicitudes.create', compact('unidades'));
    }

    // 🔥 GUARDAR
    public function store(Request $request)
    {
        $request->validate([
            'inquilino_id' => 'required|exists:inquilinos,id',
            'unidad_id' => 'required|exists:unidades,id',
            'tipo' => 'required',
            'asunto' => 'required|max:150',
            'descripcion' => 'required'
        ]);

        // 🔥 validar contrato activo
        $contrato = Contrato::where('inquilino_id', $request->inquilino_id)
            ->where('unidad_id', $request->unidad_id)
            ->where('estado', 'activo')
            ->first();

        if (!$contrato) {
            return back()
                ->withErrors("El inquilino no tiene contrato activo en esa unidad")
                ->withInput();
        }

        // 🔥 archivo
        $ruta = null;
        if ($request->hasFile('evidencia')) {
            $ruta = $request->file('evidencia')->store('solicitudes', 'public');
        }

        $solicitud = SolicitudInquilino::create([
            'inquilino_id' => $request->inquilino_id,
            'unidad_id' => $request->unidad_id,
            'tipo' => $request->tipo,
            'asunto' => $request->asunto,
            'descripcion' => $request->descripcion,
            'prioridad' => $request->prioridad ?? 'media',
            'estado' => 'abierta',
            'evidencia_url' => $ruta,
            'activo' => true,
            'creado_por_usuario_id' => auth()->id(),
            'actualizado_por_usuario_id' => auth()->id(),
        ]);

        // 🔥 AUDITORÍA
        AuditoriaLog::create([
            'usuario_id' => auth()->id(),
            'tabla' => 'solicitudes_inquilino',
            'accion' => 'CREATE',
            'registro_id' => $solicitud->id,
            'datos_nuevos' => $solicitud->toArray(),
            'ip' => $request->ip(),
            'fecha' => now()
        ]);

        return redirect()->route('solicitudes.index')
            ->with('success', 'Solicitud creada correctamente');
    }

    // 🔥 VER
    public function show(SolicitudInquilino $solicitude)
    {
        $solicitude->load(['inquilino', 'unidad']);
        return view('solicitudes.show', compact('solicitude'));
    }

    // 🔥 EDITAR
    public function edit(SolicitudInquilino $solicitude)
    {
        $unidades = Unidad::with(['contratos.inquilino'])->get();

        return view('solicitudes.edit', compact('solicitude', 'unidades'));
    }

    // 🔥 ACTUALIZAR
    public function update(Request $request, SolicitudInquilino $solicitude)
    {
        $request->validate([
            'asunto' => 'required|max:150',
            'descripcion' => 'required'
        ]);

        $estado = $request->estado ?? $solicitude->estado;

        $fechaCierre = null;
        if (in_array($estado, ['resuelta', 'cerrada'])) {
            $fechaCierre = now();
        }

        $antes = $solicitude->toArray();

        // 🔥 archivo
        if ($request->hasFile('evidencia')) {
            $ruta = $request->file('evidencia')->store('solicitudes', 'public');
            $solicitude->evidencia_url = $ruta;
        }

        $solicitude->update([
            'tipo' => $request->tipo ?? $solicitude->tipo,
            'asunto' => $request->asunto,
            'descripcion' => $request->descripcion,
            'prioridad' => $request->prioridad ?? $solicitude->prioridad,
            'estado' => $estado,
            'fecha_cierre' => $fechaCierre,
            'respuesta' => $request->respuesta,
            'actualizado_por_usuario_id' => auth()->id(),
        ]);

        // 🔥 AUDITORÍA
        AuditoriaLog::create([
            'usuario_id' => auth()->id(),
            'tabla' => 'solicitudes_inquilino',
            'accion' => 'UPDATE',
            'registro_id' => $solicitude->id,
            'datos_anteriores' => $antes,
            'datos_nuevos' => $solicitude->toArray(),
            'ip' => $request->ip(),
            'fecha' => now()
        ]);

        return redirect()->route('solicitudes.show', $solicitude)
            ->with('success', 'Solicitud actualizada');
    }

    // 🔥 DESACTIVAR
    public function destroy(SolicitudInquilino $solicitude)
    {
        $antes = $solicitude->toArray();

        $solicitude->update([
            'activo' => false,
            'actualizado_por_usuario_id' => auth()->id()
        ]);

        AuditoriaLog::create([
            'usuario_id' => auth()->id(),
            'tabla' => 'solicitudes_inquilino',
            'accion' => 'DELETE',
            'registro_id' => $solicitude->id,
            'datos_anteriores' => $antes,
            'datos_nuevos' => $solicitude->toArray(),
            'ip' => request()->ip(),
            'fecha' => now()
        ]);

        return back()->with('success', 'Solicitud desactivada');
    }

    // 🔥 ACTIVAR
    public function activar(SolicitudInquilino $solicitude)
    {
        $antes = $solicitude->toArray();

        $solicitude->update([
            'activo' => true,
            'actualizado_por_usuario_id' => auth()->id()
        ]);

        AuditoriaLog::create([
            'usuario_id' => auth()->id(),
            'tabla' => 'solicitudes_inquilino',
            'accion' => 'UPDATE',
            'registro_id' => $solicitude->id,
            'datos_anteriores' => $antes,
            'datos_nuevos' => $solicitude->toArray(),
            'ip' => request()->ip(),
            'fecha' => now()
        ]);

        return back()->with('success', 'Solicitud activada');
    }
}