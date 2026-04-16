<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Propiedad extends Model
{
    use SoftDeletes;

    protected $table = 'propiedades';

    // Desactivamos timestamps automáticos de Laravel porque usamos creado_en / actualizado_en
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'direccion',
        'ciudad',
        'tipo',
        'descripcion',
        'activo',
        'creado_por_usuario_id',
        'actualizado_por_usuario_id',
        'creado_en',
        'actualizado_en',
    ];

    protected $casts = [
        'activo'        => 'boolean',
        'creado_en'     => 'datetime',
        'actualizado_en'=> 'datetime',
    ];

    // 🔥 RELACIONES CORRECTAS

    public function unidades()
    {
        return $this->hasMany(Unidad::class, 'propiedad_id');
    }

    public function gastos()
    {
        return $this->hasMany(Gasto::class, 'propiedad_id');
    }

    // 🔥 SCOPES

    public function scopeActivas($query)
    {
        return $query->where('activo', 1);
    }

    // 🔥 ACCESSORS

    public function getTotalUnidadesAttribute()
    {
        return $this->unidades()->count();
    }

    public function getUnidadesDisponiblesAttribute()
    {
        return $this->unidades()->where('estado', 'disponible')->count();
    }

    public function getUnidadesOcupadasAttribute()
    {
        return $this->unidades()->where('estado', 'ocupada')->count();
    }

    public function getDireccionCompletaAttribute()
    {
        return "{$this->direccion}, {$this->ciudad}" . ($this->departamento ? ", {$this->departamento}" : '');
    }
}
