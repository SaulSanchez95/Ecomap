<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'rol',
    'telefono',
    'fk_id_usuario_crea',
])]

#[Hidden([
    'password',
    'remember_token',
])]

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Centros pertenecientes al usuario.
    public function centros(): HasMany
    {
        return $this->hasMany(
            CentroAcopio::class,
            'fk_id_usuario'
        );
    }

    // Cuenta administrativa que creó a este usuario.
    public function creadoPor()
    {
        return $this->belongsTo(
            User::class,
            'fk_id_usuario_crea'
        );
    }

    // Cuentas creadas por este administrador.
    public function usuariosCreados(): HasMany
    {
        return $this->hasMany(
            User::class,
            'fk_id_usuario_crea'
        );
    }

    // Solicitudes de acopio realizadas por el ciudadano.
    public function solicitudesAcopio(): HasMany
    {
        return $this->hasMany(
            SolicitudAcopio::class,
            'fk_id_usuario'
        );
    }

    // Registros de acopio capturados por este usuario.
    public function registrosAcopio(): HasMany
    {
        return $this->hasMany(
            RegistroAcopioDiario::class,
            'fk_id_usuario_registra'
        );
    }

    // Comprobación de roles.
    public function esAdministrador(): bool
    {
        return $this->rol === 'administrador';
    }

    public function esDueno(): bool
    {
        return $this->rol === 'dueno';
    }

    public function esCiudadano(): bool
    {
        return $this->rol === 'ciudadano';
    }
}
