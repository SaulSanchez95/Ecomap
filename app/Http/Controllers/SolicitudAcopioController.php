<?php

namespace App\Http\Controllers;

use App\Models\CentroAcopio;
use App\Models\Material;
use App\Models\SolicitudAcopio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SolicitudAcopioController extends Controller
{
    // Verifica que el usuario sea ciudadano.
    private function verificarCiudadano(Request $request): void
    {
        abort_unless(
            $request->user() &&
            $request->user()->rol === 'ciudadano',
            403,
            'Solo los ciudadanos pueden registrar solicitudes de acopio.'
        );
    }

    // Verifica que el usuario sea dueño.
    private function verificarDueno(Request $request): void
    {
        abort_unless(
            $request->user() &&
            $request->user()->rol === 'dueno',
            403,
            'No tienes permiso para consultar estas solicitudes.'
        );
    }

    // Muestra el formulario para registrar una solicitud.
    public function create(Request $request)
    {
        $this->verificarCiudadano($request);

        $centros = CentroAcopio::where('estado_validacion', 'validado')
            ->where('activo', true)
            ->with('materiales')
            ->orderBy('nombre')
            ->get();

        $materiales = Material::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view(
            'ecomap.solicitudes.create',
            compact('centros', 'materiales')
        );
    }

    // Guarda la solicitud del ciudadano.
    public function store(Request $request)
    {
        $this->verificarCiudadano($request);

        $datos = $request->validate([
            'fk_id_centro' => [
                'required',
                'integer',
                Rule::exists('centros_acopio', 'id')
                    ->where('estado_validacion', 'validado')
                    ->where('activo', true),
            ],

            'fecha_entrega_estimada' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'comentarios' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'materiales' => [
                'required',
                'array',
                'min:1',
            ],

            'materiales.*' => [
                'required',
                'array',
            ],

            'materiales.*.id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('materiales', 'id')
                    ->where('activo', true),
            ],

            'materiales.*.cantidad' => [
                'required',
                'numeric',
                'decimal:0,3',
                'gt:0',
            ],
        ]);

        // Inicia una transacción para comprobar y guardar la solicitud.
        DB::transaction(function () use ($datos, $request) {

            // Bloquea el centro mientras se realizan las comprobaciones.
            $centro = CentroAcopio::whereKey($datos['fk_id_centro'])
                ->where('estado_validacion', 'validado')
                ->where('activo', true)
                ->lockForUpdate()
                ->firstOrFail();

            $centro->load('materiales');

            // Obtiene los identificadores de los materiales solicitados.
            $idsMateriales = collect($datos['materiales'])
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            // Comprueba que todos los materiales sigan activos.
            $materialesActivos = Material::whereIn('id', $idsMateriales)
                ->where('activo', true)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            abort_unless(
                count($materialesActivos) === count($idsMateriales),
                422,
                'Uno o más materiales ya no están disponibles.'
            );

            // Comprueba que el centro acepte todos los materiales.
            $materialesAceptados = $centro->materiales
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            foreach ($idsMateriales as $idMaterial) {
                abort_unless(
                    in_array($idMaterial, $materialesAceptados, true),
                    422,
                    'El centro seleccionado no acepta uno de los materiales indicados.'
                );
            }

            // Crea la solicitud informativa del ciudadano.
            $solicitud = SolicitudAcopio::create([
                'fk_id_centro' => $centro->id,
                'fk_id_usuario' => $request->user()->id,
                'fecha_entrega_estimada' => $datos['fecha_entrega_estimada'],
                'comentarios' => $datos['comentarios'] ?? null,
                'estado' => 'notificada',
            ]);

            // Prepara las cantidades estimadas de cada material.
            $materiales = [];

            foreach ($datos['materiales'] as $material) {
                $materiales[$material['id']] = [
                    'cantidad_estimada' => $material['cantidad'],
                ];
            }

            // Guarda las relaciones y cantidades estimadas.
            $solicitud->materiales()->sync($materiales);
        });

        return redirect()
            ->route('solicitudes.create')
            ->with(
                'success',
                'Tu solicitud de acopio fue registrada correctamente.'
            );
    }

    // Muestra las solicitudes recibidas por los centros del dueño.
    public function index(Request $request)
    {
        $this->verificarDueno($request);

        $solicitudes = SolicitudAcopio::whereHas('centro', function ($query) use ($request) {
            $query->where('fk_id_usuario', $request->user()->id);
        })
            ->with([
                'centro',
                'ciudadano',
                'materiales',
            ])
            ->latest()
            ->get();

        return view(
            'ecomap.solicitudes.index',
            compact('solicitudes')
        );
    }

    // Muestra los detalles de una solicitud.
    public function show(Request $request, string $id)
    {
        $this->verificarDueno($request);

        $solicitud = SolicitudAcopio::whereHas('centro', function ($query) use ($request) {
            $query->where('fk_id_usuario', $request->user()->id);
        })
            ->with([
                'centro',
                'ciudadano',
                'materiales',
            ])
            ->findOrFail($id);

        return view(
            'ecomap.solicitudes.show',
            compact('solicitud')
        );
    }
}
