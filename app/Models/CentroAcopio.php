<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CentroAcopio extends Model
{
    use SoftDeletes;

    protected $table = 'centros_acopio';

    protected $fillable = [
        'fk_id_usuario',
        'nombre',
        'descripcion',
        'direccion',
        'colonia',
        'municipio',
        'estado',
        'codigo_postal',
        'telefono',
        'correo',
        'horario',
        'latitud',
        'longitud',
        'estado_validacion',
        'activo',
        'fk_id_usuario_valida',
        'fecha_validacion',
        'observaciones_admin',
        'fecha_baja',
    ];

    protected function casts(): array
    {
        return [
            'latitud' => 'decimal:7',
            'longitud' => 'decimal:7',
            'activo' => 'boolean',
            'fecha_validacion' => 'datetime',
            'fecha_baja' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // Usuario propietario del centro.
    public function dueno(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'fk_id_usuario'
        );
    }

    // Administrador que validó el centro.
    public function validador(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'fk_id_usuario_valida'
        );
    }

    // Materiales que acepta el centro.
    public function materiales(): BelongsToMany
    {
        return $this->belongsToMany(
            Material::class,
            'centro_acopio_material',
            'fk_id_centro',
            'fk_id_material'
        );
    }

    // Campañas organizadas por el centro.
    public function campanias(): HasMany
    {
        return $this->hasMany(
            Campania::class,
            'fk_id_centro'
        );
    }

    // Avisos informativos enviados por ciudadanos.
    public function solicitudesAcopio(): HasMany
    {
        return $this->hasMany(
            SolicitudAcopio::class,
            'fk_id_centro'
        );
    }

    // Solicitudes de actualización del centro.
    public function solicitudesActualizacion(): HasMany
    {
        return $this->hasMany(
            SolicitudActualizacionCentro::class,
            'fk_id_centro'
        );
    }

    // Registros de materiales realmente recibidos.
    public function registrosDiarios(): HasMany
    {
        return $this->hasMany(
            RegistroAcopioDiario::class,
            'fk_id_centro'
        );
    }
}
