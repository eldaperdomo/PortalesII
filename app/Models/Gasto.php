<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gasto extends Model
{
    protected $table = 'gastos';
    

    public $timestamps = false;

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'propiedad_id',
        'unidad_id',
        'fecha',
        'monto',
        'categoria',
        'descripcion',
        'observaciones',
        'comprobante',
        'activo',
        'estado',
        'creado_por_usuario_id',
        'actualizado_por_usuario_id'
    ];

    protected $casts = [
        'fecha'    => 'date',
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
        return $query->whereMonth('fecha', $mes)->whereYear('fecha', $anio);
    }
}