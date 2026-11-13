<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SolicitudActualizacionCentro extends Model
{
    protected $table = 'solicitudes_actualizacion_centro';

    protected $fillable = [
        'fk_id_centro',
        'fk_id_usuario_solicita',
        'fk_id_usuario_revisa',
        'datos_propuestos',
        'estado',
        'observaciones_admin',
        'fecha_revision',
    ];

    protected function casts(): array
    {
        return [
            'datos_propuestos' => 'array',
            'fecha_revision' => 'datetime',
        ];
    }

    // Centro que se desea actualizar.
    public function centro(): BelongsTo
    {
        return $this->belongsTo(
            CentroAcopio::class,
            'fk_id_centro'
        );
    }

    // Dueño que solicita la actualización.
    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'fk_id_usuario_solicita'
        );
    }

    // Administrador que revisa la solicitud.
    public function revisor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'fk_id_usuario_revisa'
        );
    }

    // Materiales propuestos para el centro.
    public function materiales(): BelongsToMany
    {
        return $this->belongsToMany(
            Material::class,
            'solicitud_actualizacion_material',
            'fk_id_solicitud_actualizacion',
            'fk_id_material'
        );
    }
}
