<?php

namespace App\Http\Controllers;

use App\Models\CentroAcopio;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CatalogoCentroAcopioController extends Controller
{
    /**
     * Mostrar el catálogo de centros de acopio disponibles.
     * Permite buscar por nombre, ubicación y material aceptado.
     */
    public function index(Request $request)
    {
        $datos = $request->validate([
            'buscar' => [
                'nullable',
                'string',
                'max:120',
            ],

            'material' => [
                'nullable',
                'integer',
                Rule::exists('materiales', 'id')
                    ->where('activo', true),
            ],
        ]);

        $buscar = trim($datos['buscar'] ?? '');
        $materialId = $datos['material'] ?? null;

        // Consultar únicamente centros validados y activos.
        $centros = CentroAcopio::query()
            ->where('estado_validacion', 'validado')
            ->where('activo', true)
            ->with('materiales')

            // Buscar por nombre o ubicación.
            ->when($buscar !== '', function ($consulta) use ($buscar) {
                $consulta->where(function ($subconsulta) use ($buscar) {
                    $subconsulta
                        ->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('direccion', 'like', "%{$buscar}%")
                        ->orWhere('colonia', 'like', "%{$buscar}%")
                        ->orWhere('municipio', 'like', "%{$buscar}%")
                        ->orWhere('estado', 'like', "%{$buscar}%");
                });
            })

            // Filtrar por material aceptado.
            ->when($materialId, function ($consulta) use ($materialId) {
                $consulta->whereHas('materiales', function ($subconsulta) use ($materialId) {
                    $subconsulta->where('materiales.id', $materialId);
                });
            })

            ->orderBy('nombre')
            ->get();

        // Obtener los materiales disponibles en centros validados y activos.
        $materiales = Material::query()
            ->where('activo', true)
            ->whereHas('centros', function ($consulta) {
                $consulta
                    ->where('estado_validacion', 'validado')
                    ->where('activo', true);
            })
            ->orderBy('nombre')
            ->get();

        return view(
            'ecomap.ciudadano',
            compact('centros', 'materiales', 'buscar', 'materialId')
        );
    }

    /**
     * Mostrar la información detallada de un centro.
     * Los ciudadanos solamente pueden consultar centros
     * validados y activos.
     */
    public function show(string $id)
    {
        $centro = CentroAcopio::query()
            ->where('estado_validacion', 'validado')
            ->where('activo', true)
            ->with('materiales')
            ->findOrFail($id);

        return view(
            'ecomap.centros-publicos.show',
            compact('centro')
        );
    }
}
