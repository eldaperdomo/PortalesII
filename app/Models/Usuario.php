<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';


    protected $fillable = [
        'nombre',
        'username',
        'email',
        'password',
        'rol',
        'foto_perfil_url',
        'activo'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // 🔥 HELPERS
    public function esAdmin()
    {
        return $this->rol === 'admin';
    }

    public function esEmpleado()
    {
        return $this->rol === 'empleado';
    }

    // 🔥 SCOPES
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}

