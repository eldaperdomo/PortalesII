<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class contrato extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'contrato';

    protected $fillable = [
        'unidad_id',
        'inquilino_id',
        'codigo',
        'fecha_inicio',
        'fecha_fin',
        'monto_mensual',
        'deposito',
        'dia_pago',
        'estado',
        'periodicidad',
        'incremento_anual',
        'clausulas_adicionales',
        'observaciones',
        'renovacion_automatica',
    ];

    protected $casts = [
        'fecha_inicio'         => 'date',
        'fecha_fin'            => 'date',
        'monto_mensual'        => 'decimal:2',
        'deposito'             => 'decimal:2',
        'incremento_anual'     => 'decimal:2',
        'renovacion_automatica'=> 'boolean',
    ];

    // ─── Boot ──────────────────────────────────────────────────────────────────

    protected static function booted(): void
    {
        // Al crear un contrato activo, marcar la unidad como ocupada
        static::created(function (Contrato $contrato) {
            if ($contrato->estado === 'activo') {
                $contrato->unidad->update(['estado' => 'ocupada']);
            }
        });

        // Al actualizar estado, sincronizar con la unidad
        static::updated(function (Contrato $contrato) {
            if ($contrato->isDirty('estado')) {
                if ($contrato->estado === 'activo') {
                    $contrato->unidad->update(['estado' => 'ocupada']);
                } elseif (in_array($contrato->estado, ['vencido', 'cancelado'])) {
                    // Solo liberar si no hay otro contrato activo en esa unidad
                    $otroActivo = Contrato::where('unidad_id', $contrato->unidad_id)
                        ->where('id', '!=', $contrato->id)
                        ->where('estado', 'activo')
                        ->exists();
                    if (!$otroActivo) {
                        $contrato->unidad->update(['estado' => 'disponible']);
                    }
                }
            }
        });
    }

    // ─── Relaciones ────────────────────────────────────────────────────────────

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class);
    }

    public function inquilino(): BelongsTo
    {
        return $this->belongsTo(Inquilino::class);
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopeVencidos($query)
    {
        return $query->where('estado', 'vencido');
    }

    // ─── Accessors ─────────────────────────────────────────────────────────────

    public function getDuracionMesesAttribute(): int
    {
        return (int) $this->fecha_inicio->diffInMonths($this->fecha_fin);
    }

    public function getEstaVigenteAttribute(): bool
    {
        return $this->estado === 'activo'
            && now()->between($this->fecha_inicio, $this->fecha_fin);
    }

    public function getDiasParaVencerAttribute(): int
    {
        return (int) now()->diffInDays($this->fecha_fin, false);
    }

    public function getMontoTotalAttribute(): float
    {
        return (float) ($this->monto_mensual * $this->duracion_meses);
    }

    // ─── Helpers ───────────────────────────────────────────────────────────────

    public static function generarCodigo(): string
    {
        $ultimo = static::withTrashed()->latest('id')->first();
        $numero = $ultimo ? ($ultimo->id + 1) : 1;
        return 'CONT-' . str_pad($numero, 5, '0', STR_PAD_LEFT);
    }
}