<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contrato extends Model
{
    protected $table = 'contratos';

    public $timestamps = false;

    protected $fillable = [
        'unidad_id',
        'inquilino_id',
        'fecha_inicio',
        'fecha_fin',
        'monto_renta',
        'dia_pago',
        'estado',
        'activo',
        'creado_por_usuario_id',
        'actualizado_por_usuario_id',
        'creado_en',
        'actualizado_en',
    ];

    protected $casts = [
        'fecha_inicio'   => 'date',
        'fecha_fin'      => 'date',
        'monto_renta'    => 'decimal:2',
        'activo'         => 'boolean',
        'creado_en'      => 'datetime',
        'actualizado_en' => 'datetime',
    ];

    // ─── Boot ──────────────────────────────────────────────────────────────────

    protected static function booted(): void
    {
        // Al activar un contrato, marcar la unidad como ocupada
        static::saved(function (Contrato $contrato) {
            if ($contrato->estado === 'activo') {
                Unidad::where('id', $contrato->unidad_id)->update(['estado' => 'ocupada']);
            } elseif (in_array($contrato->estado, ['terminado', 'cancelado'])) {
                $otroActivo = Contrato::where('unidad_id', $contrato->unidad_id)
                    ->where('id', '!=', $contrato->id)
                    ->where('estado', 'activo')
                    ->exists();
                if (!$otroActivo) {
                    Unidad::where('id', $contrato->unidad_id)->update(['estado' => 'disponible']);
                }
            }
        });
    }

    // ─── Relaciones ────────────────────────────────────────────────────────────

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class, 'unidad_id');
    }

    public function inquilino(): BelongsTo
    {
        return $this->belongsTo(Inquilino::class, 'inquilino_id');
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    // ─── Accessors ─────────────────────────────────────────────────────────────

    public function getDuracionMesesAttribute(): int
    {
        return (int) $this->fecha_inicio->diffInMonths($this->fecha_fin);
    }

    public function getDiasParaVencerAttribute(): int
    {
        return (int) now()->diffInDays($this->fecha_fin, false);
    }

    public static function generarCodigo()
{
    $ultimo = self::orderBy('id', 'desc')->first();

    $numero = $ultimo ? $ultimo->id + 1 : 1;

    return 'CTR-' . str_pad($numero, 5, '0', STR_PAD_LEFT);
}
}