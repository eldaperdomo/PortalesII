<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 
class Pago extends Model
{
    protected $table = 'pagos';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'contrato_id',
        'periodo',
        'monto_esperado',
        'total_pagado',
        'estado',
        'fecha_ultimo_abono',
        'activo',
        'creado_por_usuario_id',
        'actualizado_por_usuario_id'
    ];

    protected $casts = [
        'monto_esperado' => 'decimal:2',
        'total_pagado' => 'decimal:2',
        'fecha_ultimo_abono' => 'date',
        'activo' => 'boolean'
    ];

    public function contrato()
{
    return $this->belongsTo(Contrato::class)->withTrashed();
}

    public function abonos()
    {
        return $this->hasMany(AbonoPago::class);
    }
    public function recibo()
    {
        return $this->hasOne(\App\Models\Recibo::class, 'pago_id')
            ->where('tipo', 'pago_completo')
            ->where('activo', true);
    }

    public function getSaldoAttribute()
    {
        return $this->monto_esperado - $this->total_pagado;
    }
}