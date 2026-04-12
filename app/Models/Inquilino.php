<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inquilino extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inquilinos';

    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'email',
        'telefono',
        'telefono_emergencia',
        'contacto_emergencia',
        'fecha_nacimiento',
        'estado_civil',
        'ocupacion',
        'empresa',
        'ingreso_mensual',
        'observaciones',
        'activo',
    ];

    protected $casts = [
        'fecha_nacimiento'  => 'date',
        'activo'            => 'boolean',
        'ingreso_mensual'   => 'decimal:2',
    ];

    // ─── Relaciones ────────────────────────────────────────────────────────────

    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class);
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // ─── Accessors ─────────────────────────────────────────────────────────────

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function getContratoActivoAttribute(): ?Contrato
    {
        return $this->contratos()->where('estado', 'activo')->latest()->first();
    }

    public function getTieneContratoActivoAttribute(): bool
    {
        return $this->contratos()->where('estado', 'activo')->exists();
    }
}