<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    // 🔥 ACTIVAR TIMESTAMPS
    public $timestamps = true;

    // 🔥 PERSONALIZAR NOMBRES
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'usuario_id',
        'inquilino_id',
        'tipo',
        'titulo',
        'mensaje',
        'fecha_enviada',
        'canal',
        'estado',
        'leida',
        'activo',
        'destino_correo',
        'referencia_tabla',
        'referencia_id',
        'creado_por_usuario_id',
        'actualizado_por_usuario_id'
    ];

    // 🔥 relaciones

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function inquilino()
    {
        return $this->belongsTo(Inquilino::class, 'inquilino_id');
    }
}