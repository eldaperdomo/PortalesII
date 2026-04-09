<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class unidad extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'unidad';

    protected $fillable = [
        'propiedad_id',
        'nombre',
        'numero',
        'tipo',
        'area',
        'habitaciones',
        'banos',
        'tiene_parqueo',
        'precio_renta',
        'estado',
        'descripcion',
        'piso',
    ];

    protected $casts = [
        'tiene_parqueo' => 'boolean',
        'precio_renta'  => 'decimal:2',
        'area'          => 'decimal:2',
    ];

    // ─── Relaciones ────────────────────────────────────────────────────────────

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class);
    }

    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class);
    }

    public function gastos(): HasMany
    {
        return $this->hasMany(Gasto::class);
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeDisponibles($query)
    {
        return $query->where('estado', 'disponible');
    }

    public function scopeOcupadas($query)
    {
        return $query->where('estado', 'ocupada');
    }

    // ─── Accessors ─────────────────────────────────────────────────────────────

    public function getContratoActivoAttribute(): ?Contrato
    {
        return $this->contratos()->where('estado', 'activo')->latest()->first();
    }

    public function getEstaOcupadaAttribute(): bool
    {
        return $this->estado === 'ocupada';
    }
}
