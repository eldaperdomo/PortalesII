<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AbonoPago extends Model
{
    protected $table = 'abonos_pago';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'pago_id',
        'fecha_abono',
        'monto',
        'metodo',
        'referencia_pago',
        'observacion',
        'activo',
        'creado_por_usuario_id',
        'actualizado_por_usuario_id'
    ];

    protected $casts = [
        'fecha_abono' => 'datetime',
        'monto' => 'decimal:2',
        'activo' => 'boolean'
    ];

    public function pago()
    {
        return $this->belongsTo(Pago::class);
    }
}