<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;

class Sesion extends Model
{
    protected $table = 'sesiones';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'usuario_id',
        'inicio_sesion',
        'cierre_sesion',
        'user_agent'
    ];


    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }


}

