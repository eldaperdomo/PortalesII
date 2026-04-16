<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaLog extends Model
{
    protected $table = 'auditoria_logs';

    public $timestamps = false; 

    protected $fillable = [
        'usuario_id',
        'tabla',
        'accion',
        'registro_id',
        'datos_anteriores',
        'datos_nuevos',
        'ip',
        'fecha'
    ];

    protected $casts = [
        'datos_anteriores' => 'array',
        'datos_nuevos' => 'array',
    ];
    public function usuario() 
    { 
        return $this->belongsTo(Usuario::class, 'usuario_id'); 
    }
}