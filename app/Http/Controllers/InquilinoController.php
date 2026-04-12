<?php

namespace App\Http\Controllers;

use App\Models\Inquilino;
use Illuminate\Http\Request;

class InquilinoController extends Controller
{
    public function index()
    {
        $Inquilinos = Inquilino::withCount('Contratos')
            ->latest()
            ->paginate(10);

        return view('inquilino.index', compact('Inquilinos'));
    }

    public function create()
    {
        return view('inquilino.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'               => 'required|string|max:100',
            'apellido'             => 'required|string|max:100',
            'dni'                  => 'required|string|max:30|unique:inquilinos,dni',
            'email'                => 'nullable|email|unique:inquilinos,email',
            'telefono'             => 'nullable|string|max:20',
            'telefono_emergencia'  => 'nullable|string|max:20',
            'contacto_emergencia'  => 'nullable|string|max:150',
            'fecha_nacimiento'     => 'nullable|date|before:today',
            'estado_civil'         => 'nullable|in:soltero,casado,divorciado,viudo,otro',
            'ocupacion'            => 'nullable|string|max:100',
            'empresa'              => 'nullable|string|max:150',
            'ingreso_mensual'      => 'nullable|numeric|min:0',
            'observaciones'        => 'nullable|string',
        ]);

        Inquilino::create($validated);

        return redirect()->route('inquilino.index')
            ->with('success', 'Inquilino registrado correctamente.');
    }

    public function show(Inquilino $inquilino)
    {
        $inquilino->load(['contrato.unidad.propiedad']);
        return view('inquilino.show', compact('inquilino'));
    }

    public function edit(Inquilino $inquilino)
    {
        return view('inquilino.edit', compact('inquilino'));
    }

    public function update(Request $request, Inquilino $inquilino)
    {
        $validated = $request->validate([
            'nombre'               => 'required|string|max:100',
            'apellido'             => 'required|string|max:100',
            'dni'                  => 'required|string|max:30|unique:inquilinos,dni,' . $inquilino->id,
            'email'                => 'nullable|email|unique:inquilinos,email,' . $inquilino->id,
            'telefono'             => 'nullable|string|max:20',
            'telefono_emergencia'  => 'nullable|string|max:20',
            'contacto_emergencia'  => 'nullable|string|max:150',
            'fecha_nacimiento'     => 'nullable|date|before:today',
            'estado_civil'         => 'nullable|in:soltero,casado,divorciado,viudo,otro',
            'ocupacion'            => 'nullable|string|max:100',
            'empresa'              => 'nullable|string|max:150',
            'ingreso_mensual'      => 'nullable|numeric|min:0',
            'observaciones'        => 'nullable|string',
        ]);

        $inquilino->update($validated);

        return redirect()->route('inquilino.show', $inquilino)
            ->with('success', 'Inquilino actualizado correctamente.');
    }

    public function destroy(Inquilino $inquilino)
    {
        if ($inquilino->contratos()->where('estado', 'activo')->exists()) {
            return back()->with('error', 'No se puede eliminar un inquilino con contratos activos.');
        }

        $inquilino->delete();

        return redirect()->route('inquilino.index')
            ->with('success', 'Inquilino eliminado correctamente.');
    }
}
