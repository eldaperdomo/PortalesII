<?php

namespace App\Services;

use App\Models\AuditoriaLog;
use Illuminate\Support\Facades\Auth;

class AuditoriaServicio
{
    public static function registrar($data)
    {
        AuditoriaLog::create([
            'usuario_id' => $data['usuario_id'] ?? Auth::id(),
            'tabla' => $data['tabla'],
            'accion' => $data['accion'],
            'registro_id' => $data['registro_id'],
            'datos_anteriores' => $data['datos_anteriores'] ?? null,
            'datos_nuevos' => $data['datos_nuevos'] ?? null,
            'ip' => request()->ip(),
            'fecha' => now(),
        ]);
    }
}