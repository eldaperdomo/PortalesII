<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudInquilino extends Model
{
    protected $table = 'solicitudes_inquilino';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'inquilino_id',
        'unidad_id',
        'tipo',
        'asunto',
        'descripcion',
        'prioridad',
        'estado',
        'respuesta',
        'fecha_cierre',
        'evidencia_url',
        'activo',
        'creado_por_usuario_id',
        'actualizado_por_usuario_id'
    ];

    public function inquilino()
    {
        return $this->belongsTo(Inquilino::class);
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }
}