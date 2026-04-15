<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    protected $table = 'recibos';
    public $timestamps = false;

    protected $fillable = [
        'pago_id',
        'abono_pago_id',
        'numero',
        'tipo',
        'fecha_emision',
        'monto_recibido',
        'recibido_de',
        'concepto',
        'firma_base64',
        'pdf_url',
        'activo',
        'emitido_por_usuario_id',
        'creado_por_usuario_id',
        'actualizado_por_usuario_id',
        'creado_en',
        'actualizado_en'
    ];


    public function pago()
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }

    public function abonoPago()
    {
        return $this->belongsTo(AbonoPago::class, 'abono_pago_id');
    }
}