<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\HasMany;

class Propiedad extends Model
{
=======
use Illuminate\Database\Eloquent\SoftDeletes;

class Propiedad extends Model
{
    use SoftDeletes;

>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87
    protected $table = 'propiedades';

    // Desactivamos timestamps automáticos de Laravel porque usamos creado_en / actualizado_en
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'direccion',
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

<<<<<<< HEAD
    public function unidades(): HasMany
=======
    public function unidades()
>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87
    {
        return $this->hasMany(Unidad::class, 'propiedad_id');
    }

<<<<<<< HEAD
    // ─── Scopes ────────────────────────────────────────────────────────────────
=======
    public function gastos()
    {
        return $this->hasMany(Gasto::class, 'propiedad_id');
    }

    // 🔥 SCOPES
>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87

    public function scopeActivas($query)
    {
        return $query->where('activo', 1);
    }

    // 🔥 ACCESSORS

<<<<<<< HEAD
    public function getUnidadesDisponiblesAttribute(): int
=======
    public function getTotalUnidadesAttribute()
    {
        return $this->unidades()->count();
    }

    public function getUnidadesDisponiblesAttribute()
>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87
    {
        return $this->unidades()->where('estado', 'disponible')->count();
    }

    public function getUnidadesOcupadasAttribute()
    {
        return $this->unidades()->where('estado', 'ocupada')->count();
    }
<<<<<<< HEAD
}
=======

    public function getDireccionCompletaAttribute()
    {
        return "{$this->direccion}, {$this->ciudad}" . ($this->departamento ? ", {$this->departamento}" : '');
    }
}
>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87
