<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Campania extends Model
{
    protected $table = 'campanias';

    protected $fillable = [
        'fk_id_centro',
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
        ];
    }

    // Centro de acopio que organiza la campaña.
    public function centro(): BelongsTo
    {
        return $this->belongsTo(
            CentroAcopio::class,
            'fk_id_centro'
        );
    }

    // Materiales incluidos en la campaña.
    public function materiales(): BelongsToMany
    {
        return $this->belongsToMany(
            Material::class,
            'campania_material',
            'fk_id_campania',
            'fk_id_material'
        )->withPivot('cantidad_solicitada');
    }
}
