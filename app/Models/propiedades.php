<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class propiedades extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'propiedades';

    protected $fillable = [
        'user_id',
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

    // ─── Relaciones ────────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function unidades(): HasMany
    {
        return $this->hasMany(Unidad::class);
    }

    public function gastos(): HasMany
    {
        return $this->hasMany(Gasto::class);
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    // ─── Accessors ─────────────────────────────────────────────────────────────

    public function getTotalUnidadesAttribute(): int
    {
        return $this->unidades()->count();
    }

    public function getUnidadesDisponiblesAttribute(): int
    {
        return $this->unidades()->where('estado', 'disponible')->count();
    }

    public function getUnidadesOcupadasAttribute(): int
    {
        return $this->unidades()->where('estado', 'ocupada')->count();
    }

    public function getDireccionCompletaAttribute(): string
    {
        return "{$this->direccion}, {$this->ciudad}" . ($this->departamento ? ", {$this->departamento}" : '');
    }
}
