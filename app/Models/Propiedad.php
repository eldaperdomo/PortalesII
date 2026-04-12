<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Propiedad extends Model
{
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

    // ─── Relaciones ────────────────────────────────────────────────────────────

    public function unidades(): HasMany
    {
        return $this->hasMany(Unidad::class, 'propiedad_id');
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActivas($query)
    {
        return $query->where('activo', 1);
    }

    // ─── Accessors ─────────────────────────────────────────────────────────────

    public function getUnidadesDisponiblesAttribute(): int
    {
        return $this->unidades()->where('estado', 'disponible')->count();
    }

    public function getUnidadesOcupadasAttribute(): int
    {
        return $this->unidades()->where('estado', 'ocupada')->count();
    }
}