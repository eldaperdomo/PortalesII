<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TareaMantenimiento extends Model
{
    protected $table = 'tareas_mantenimiento';
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'unidad_id',
        'solicitud_inquilino_id',
        'titulo',
        'descripcion',
        'prioridad',
        'estado',
        'fecha_limite',
        'fecha_completada',
        'activo',
        'creado_por_usuario_id',
        'actualizado_por_usuario_id',
        'creado_en',
        'actualizado_en'
    ];

    //  RELACIONES

    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'unidad_id');
    }

    public function solicitudInquilino()
    {
        return $this->belongsTo(SolicitudInquilino::class, 'solicitud_inquilino_id');
    }
}
