<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inquilino extends Model
{
    protected $table = 'inquilinos';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'telefono',
        'correo',
        'foto_url',
        'activo',
        'codigo_registro',
        'codigo_registro_usado',
        'codigo_registro_expira_en',
        'creado_por_usuario_id',
        'actualizado_por_usuario_id',
        'creado_en',
        'actualizado_en',
    ];

    protected $casts = [
        'activo'                    => 'boolean',
        'codigo_registro_usado'     => 'boolean',
        'codigo_registro_expira_en' => 'datetime',
        'creado_en'                 => 'datetime',
        'actualizado_en'            => 'datetime',
    ];

    // ─── Relaciones ────────────────────────────────────────────────────────────

    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class, 'inquilino_id');
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActivos($query)
    {
        return $query->where('activo', 1);
    }

    // ─── Accessors ─────────────────────────────────────────────────────────────

    public function getContratoActivoAttribute(): ?Contrato
    {
        return $this->contratos()->where('estado', 'activo')->latest('creado_en')->first();
    }

    public function getTieneContratoActivoAttribute(): bool
    {
        return $this->contratos()->where('estado', 'activo')->exists();
    }
}