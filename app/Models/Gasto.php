<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gasto extends Model
{
    protected $table = 'gastos';

    public $timestamps = false;

    protected $fillable = [
        'unidad_id',
        'fecha_gasto',
        'monto',
        'tipo',
        'descripcion',
        'observaciones',
        'comprobante_url',
        'activo',
        'creado_por_usuario_id',
        'actualizado_por_usuario_id',
        'creado_en',
        'actualizado_en',
    ];

    protected $casts = [
        'fecha_gasto'    => 'date',
        'monto'          => 'decimal:2',
        'activo'         => 'boolean',
        'creado_en'      => 'datetime',
        'actualizado_en' => 'datetime',
    ];

    // ─── Relaciones ────────────────────────────────────────────────────────────

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class, 'unidad_id');
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActivos($query)
    {
        return $query->where('activo', 1);
    }

    public function scopeDelMes($query, $mes = null, $anio = null)
    {
        $mes  = $mes  ?? now()->month;
        $anio = $anio ?? now()->year;
        return $query->whereMonth('fecha_gasto', $mes)->whereYear('fecha_gasto', $anio);
    }
}