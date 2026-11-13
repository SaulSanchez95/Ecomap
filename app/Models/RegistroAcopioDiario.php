<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroAcopioDiario extends Model
{
    protected $table = 'registros_acopio_diario';

    protected $fillable = [
        'fk_id_centro',
        'fk_id_material',
        'fk_id_usuario_registra',
        'cantidad_recibida',
        'fecha',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'cantidad_recibida' => 'decimal:3',
            'fecha' => 'date',
        ];
    }

    // Centro donde se recibió el material.
    public function centro(): BelongsTo
    {
        return $this->belongsTo(
            CentroAcopio::class,
            'fk_id_centro'
        );
    }

    // Material que se recibió.
    public function material(): BelongsTo
    {
        return $this->belongsTo(
            Material::class,
            'fk_id_material'
        );
    }

    // Usuario que registró la recepción.
    public function usuarioRegistra(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'fk_id_usuario_registra'
        );
    }
}