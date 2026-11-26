<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $materiales = [
            [
                'nombre' => 'Papel',
                'descripcion' => 'Hojas, periódicos, revistas y otros productos de papel reciclable.',
                'unidad_medida' => 'kg',
                'activo' => true,
            ],
            [
                'nombre' => 'Cartón',
                'descripcion' => 'Cajas, empaques y otros productos de cartón reciclable.',
                'unidad_medida' => 'kg',
                'activo' => true,
            ],
            [
                'nombre' => 'Plástico',
                'descripcion' => 'Envases, botellas y otros productos de plástico reciclable.',
                'unidad_medida' => 'kg',
                'activo' => true,
            ],
            [
                'nombre' => 'Vidrio',
                'descripcion' => 'Botellas, frascos y otros envases de vidrio.',
                'unidad_medida' => 'kg',
                'activo' => true,
            ],
            [
                'nombre' => 'Metales',
                'descripcion' => 'Aluminio, acero, hierro, cobre y otros metales reciclables.',
                'unidad_medida' => 'kg',
                'activo' => true,
            ],
            [
                'nombre' => 'Electrónicos',
                'descripcion' => 'Equipos electrónicos, componentes y aparatos eléctricos en desuso.',
                'unidad_medida' => 'kg',
                'activo' => true,
            ],
            [
                'nombre' => 'Baterías',
                'descripcion' => 'Pilas y baterías usadas que requieren manejo especializado.',
                'unidad_medida' => 'kg',
                'activo' => true,
            ],
        ];

        foreach ($materiales as $material) {
            Material::updateOrCreate(
                ['nombre' => $material['nombre']],
                $material
            );
        }
    }
}
