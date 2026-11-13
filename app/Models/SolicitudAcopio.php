<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SolicitudAcopio extends Model
{
    protected $table = 'solicitudes_acopio';

    protected $fillable = [
        'fk_id_centro',
        'fk_id_usuario',
        'fecha_entrega_estimada',
        'comentarios',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_entrega_estimada' => 'date',
        ];
    }

    // Centro al que se dirige el aviso.
    public function centro(): BelongsTo
    {
        return $this->belongsTo(
            CentroAcopio::class,
            'fk_id_centro'
        );
    }

    // Ciudadano que realizó la solicitud.
    public function ciudadano(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'fk_id_usuario'
        );
    }

    // Materiales y cantidades estimadas que llevará.
    public function materiales(): BelongsToMany
    {
        return $this->belongsToMany(
            Material::class,
            'solicitud_acopio_material',
            'fk_id_solicitud',
            'fk_id_material'
        )->withPivot('cantidad_estimada');
    }
}
