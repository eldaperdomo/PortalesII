<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unidad extends Model
{
    protected $table = 'unidades';

    public $timestamps = false;

    protected $fillable = [
        'propiedad_id',
        'identificador',
        'estado',
        'monto_renta',
        'activo',
        'creado_por_usuario_id',
        'actualizado_por_usuario_id',
        'creado_en',
        'actualizado_en',
    ];

    protected $casts = [
        'activo'         => 'boolean',
        'monto_renta'    => 'decimal:2',
        'creado_en'      => 'datetime',
        'actualizado_en' => 'datetime',
    ];

    // ─── Relaciones ────────────────────────────────────────────────────────────

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }

    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class, 'unidad_id');
    }

    public function gastos(): HasMany
    {
        return $this->hasMany(Gasto::class, 'unidad_id');
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeDisponibles($query)
    {
        return $query->where('estado', 'disponible');
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', 1);
    }

    // ─── Accessors ─────────────────────────────────────────────────────────────

    public function getContratoActivoAttribute(): ?Contrato
    {
        return $this->contratos()->where('estado', 'activo')->latest('creado_en')->first();
    }
}