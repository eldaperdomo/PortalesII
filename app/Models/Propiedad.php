<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Propiedad extends Model
{
    use SoftDeletes;

    protected $table = 'propiedades';

    protected $fillable = [
        'nombre',
        'direccion',
        'ciudad',
        'departamento',
        'codigo_postal',
        'tipo',
        'descripcion',
        'area_total',
        'imagen',
        'activa',
    ];

    protected $casts = [
        'activa'     => 'boolean',
        'area_total' => 'decimal:2',
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
        return $query->where('activa', true);
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
