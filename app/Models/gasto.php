<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class gasto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gasto';

    protected $fillable = [
        'propiedad_id',
        'unidad_id',
        'concepto',
        'monto',
        'fecha',
        'categoria',
        'estado',
        'proveedor',
        'comprobante',
        'archivo_adjunto',
        'descripcion',
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2',
    ];

    // ─── Relaciones ────────────────────────────────────────────────────────────

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class);
    }

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class);
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopePagados($query)
    {
        return $query->where('estado', 'pagado');
    }

    public function scopeDelMes($query, $mes = null, $anio = null)
    {
        $mes  = $mes  ?? now()->month;
        $anio = $anio ?? now()->year;
        return $query->whereMonth('fecha', $mes)->whereYear('fecha', $anio);
    }

    // ─── Accessors ─────────────────────────────────────────────────────────────

    public function getEsPendienteAttribute(): bool
    {
        return $this->estado === 'pendiente';
    }
}
