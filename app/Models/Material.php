<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    protected $table = 'materiales';

    protected $fillable = [
        'nombre',
        'descripcion',
        'unidad_medida',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    // Centros que aceptan este material.
    public function centros(): BelongsToMany
    {
        return $this->belongsToMany(
            CentroAcopio::class,
            'centro_acopio_material',
            'fk_id_material',
            'fk_id_centro'
        );
    }

    // Campañas que incluyen este material.
    public function campanias(): BelongsToMany
    {
        return $this->belongsToMany(
            Campania::class,
            'campania_material',
            'fk_id_material',
            'fk_id_campania'
        )->withPivot('cantidad_solicitada');
    }

    // Solicitudes ciudadanas que incluyen este material.
    public function solicitudesAcopio(): BelongsToMany
    {
        return $this->belongsToMany(
            SolicitudAcopio::class,
            'solicitud_acopio_material',
            'fk_id_material',
            'fk_id_solicitud'
        )->withPivot('cantidad_estimada');
    }

    // Solicitudes de actualización que incluyen este material.
    public function solicitudesActualizacion(): BelongsToMany
    {
        return $this->belongsToMany(
            SolicitudActualizacionCentro::class,
            'solicitud_actualizacion_material',
            'fk_id_material',
            'fk_id_solicitud_actualizacion'
        );
    }

    // Registros reales de material recibido.
    public function registrosDiarios(): HasMany
    {
        return $this->hasMany(
            RegistroAcopioDiario::class,
            'fk_id_material'
        );
    }
}