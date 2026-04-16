<?php

namespace App\Http\Controllers;

use App\Models\TareaMantenimiento;
use App\Models\Unidad;
use App\Models\SolicitudInquilino;
use App\Models\AuditoriaLog;
use Illuminate\Http\Request;

class TareaMantenimientoController extends Controller
{

    
    public function index(Request $request)
    {
        $query = TareaMantenimiento::with(['unidad', 'solicitudInquilino']);

   
        if ($request->incluir_inactivos === 'true') {

            if (!auth()->user()->esAdmin()) {
                abort(403);
            }

        } else {
            $query->where('activo', true);
        }

       
        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        if ($request->prioridad) {
            $query->where('prioridad', $request->prioridad);
        }

        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%$search%")
                  ->orWhere('descripcion', 'like', "%$search%")

                  ->orWhereHas('unidad', function ($q2) use ($search) {
                      $q2->where('nombre', 'like', "%$search%");
                  })

                  ->orWhereHas('solicitudInquilino', function ($q3) use ($search) {
                      $q3->where('asunto', 'like', "%$search%");
                  });
            });
        }

        $tareas = $query->latest()->paginate(10)->withQueryString();

        return view('tareas.index', compact('tareas'));
    }

    public function create()
    {
        $unidades = Unidad::all();
        $solicitudes = SolicitudInquilino::where('activo', true)->get();

        return view('tareas.create', compact('unidades', 'solicitudes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unidad_id' => 'required|exists:unidades,id',
            'titulo' => 'required|max:150',
        ]);

        $unidad = Unidad::find($request->unidad_id);
        if (!$unidad) {
            return back()->withErrors("Unidad no válida")->withInput();
        }

        if ($request->solicitud_inquilino_id) {

            $solicitud = SolicitudInquilino::find($request->solicitud_inquilino_id);

            if (!$solicitud) {
                return back()->withErrors("Solicitud inválida")->withInput();
            }

            if ($solicitud->unidad_id != $request->unidad_id) {
                return back()->withErrors("La solicitud no pertenece a esa unidad")->withInput();
            }
        }

        $estado = $request->estado ?? 'pendiente';
        $fechaCompletada = $estado === 'completada' ? now() : null;

        $tarea = TareaMantenimiento::create([
            'unidad_id' => $request->unidad_id,
            'solicitud_inquilino_id' => $request->solicitud_inquilino_id,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'prioridad' => $request->prioridad ?? 'media',
            'estado' => $estado,
            'fecha_limite' => $request->fecha_limite,
            'fecha_completada' => $fechaCompletada,
            'activo' => true,
            'creado_por_usuario_id' => auth()->id(),
            'actualizado_por_usuario_id' => auth()->id(),
        ]);

        if ($estado === 'completada' && $tarea->solicitud_inquilino_id) {

            $solicitud = SolicitudInquilino::find($tarea->solicitud_inquilino_id);

            if ($solicitud && $solicitud->estado !== 'resuelta') {

                $antesSolicitud = $solicitud->toArray();

                $solicitud->update([
                    'estado' => 'resuelta',
                    'fecha_cierre' => now(),
                    'actualizado_por_usuario_id' => auth()->id()
                ]);

                AuditoriaLog::create([
                    'usuario_id' => auth()->id(),
                    'tabla' => 'solicitudes_inquilino',
                    'accion' => 'UPDATE',
                    'registro_id' => $solicitud->id,
                    'datos_anteriores' => $antesSolicitud,
                    'datos_nuevos' => $solicitud->toArray(),
                    'ip' => $request->ip(),
                    'fecha' => now()
                ]);
            }
        }

        AuditoriaLog::create([
            'usuario_id' => auth()->id(),
            'tabla' => 'tareas_mantenimiento',
            'accion' => 'CREATE',
            'registro_id' => $tarea->id,
            'datos_nuevos' => $tarea->toArray(),
            'ip' => $request->ip(),
            'fecha' => now()
        ]);

        return redirect()->route('tareas.index')
            ->with('success', 'Tarea creada correctamente');
    }

    public function show(TareaMantenimiento $tarea)
    {
        $tarea->load(['unidad', 'solicitudInquilino']);

        return view('tareas.show', compact('tarea'));
    }

    
    public function edit(TareaMantenimiento $tarea)
    {
        $unidades = Unidad::all();
        $solicitudes = SolicitudInquilino::all();

        return view('tareas.edit', compact('tarea', 'unidades', 'solicitudes'));
    }

    public function update(Request $request, TareaMantenimiento $tarea)
    {
        $request->validate([
            'titulo' => 'required|max:150',
        ]);

        $antes = $tarea->toArray();

        $estado = $request->estado ?? $tarea->estado;
        $fechaCompletada = $estado === 'completada' ? now() : null;

        $tarea->update([
            'unidad_id' => $request->unidad_id ?? $tarea->unidad_id,
            'solicitud_inquilino_id' => $request->solicitud_inquilino_id,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'prioridad' => $request->prioridad,
            'estado' => $estado,
            'fecha_limite' => $request->fecha_limite,
            'fecha_completada' => $fechaCompletada,
            'actualizado_por_usuario_id' => auth()->id(),
        ]);

        AuditoriaLog::create([
            'usuario_id' => auth()->id(),
            'tabla' => 'tareas_mantenimiento',
            'accion' => 'UPDATE',
            'registro_id' => $tarea->id,
            'datos_anteriores' => $antes,
            'datos_nuevos' => $tarea->toArray(),
            'ip' => $request->ip(),
            'fecha' => now()
        ]);

        return redirect()->route('tareas.show', $tarea)
            ->with('success', 'Tarea actualizada');
    }

    public function destroy(TareaMantenimiento $tarea)
    {
        if (!auth()->user()->esAdmin()) {
            return back()->withErrors("No autorizado");
        }

        $antes = $tarea->toArray();

        $tarea->update([
            'activo' => false,
            'actualizado_por_usuario_id' => auth()->id()
        ]);

        AuditoriaLog::create([
            'usuario_id' => auth()->id(),
            'tabla' => 'tareas_mantenimiento',
            'accion' => 'DELETE',
            'registro_id' => $tarea->id,
            'datos_anteriores' => $antes,
            'datos_nuevos' => $tarea->toArray(),
            'ip' => request()->ip(),
            'fecha' => now()
        ]);

        return back()->with('success', 'Tarea desactivada');
    }

    public function activar(TareaMantenimiento $tarea)
    {
        if (!auth()->user()->esAdmin()) {
            return back()->withErrors("No autorizado");
        }

        $antes = $tarea->toArray();

        $tarea->update([
            'activo' => true,
            'actualizado_por_usuario_id' => auth()->id()
        ]);

        AuditoriaLog::create([
            'usuario_id' => auth()->id(),
            'tabla' => 'tareas_mantenimiento',
            'accion' => 'UPDATE',
            'registro_id' => $tarea->id,
            'datos_anteriores' => $antes,
            'datos_nuevos' => $tarea->toArray(),
            'ip' => request()->ip(),
            'fecha' => now()
        ]);

        return back()->with('success', 'Tarea activada');
    }
}