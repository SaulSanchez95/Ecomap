<?php

namespace App\Http\Controllers;

use App\Models\CentroAcopio;
use App\Models\Material;
use App\Models\SolicitudActualizacionCentro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CentroAcopioController extends Controller
{
    /**
     * Verificar que el usuario sea dueño.
     */
    private function verificarDueno(Request $request): void
    {
        abort_unless(
            $request->user() &&
            $request->user()->rol === 'dueno',
            403,
            'No tienes permiso para realizar esta operación.'
        );
    }

    /**
     * Mostrar los centros del dueño.
     */
    public function index(Request $request)
    {
        $this->verificarDueno($request);

        $centros = $request->user()
            ->centros()
            ->with('materiales')
            ->latest()
            ->get();

        return view('ecomap.centros.index', compact('centros'));
    }

    /**
     * Mostrar formulario de registro.
     */
    public function create(Request $request)
    {
        $this->verificarDueno($request);

        $materiales = Material::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('ecomap.centros.create', compact('materiales'));
    }

    /**
     * Registrar un nuevo centro.
     */
    public function store(Request $request)
    {
        $this->verificarDueno($request);

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string'],
            'direccion' => ['required', 'string', 'max:200'],
            'colonia' => ['nullable', 'string', 'max:100'],
            'municipio' => ['required', 'string', 'max:100'],
            'estado' => ['required', 'string', 'max:100'],
            'codigo_postal' => ['nullable', 'string', 'max:10'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'correo' => ['nullable', 'email', 'max:120'],
            'horario' => ['nullable', 'string'],
            'latitud' => ['nullable', 'numeric', 'between:-90,90'],
            'longitud' => ['nullable', 'numeric', 'between:-180,180'],
            'materiales' => ['required', 'array', 'min:1'],
            'materiales.*' => [
                'required',
                'integer',
                Rule::exists('materiales', 'id')
                    ->where('activo', true),
            ],
        ]);

        DB::transaction(function () use ($request, $datos) {
            $centro = CentroAcopio::create([
                'fk_id_usuario' => $request->user()->id,
                'nombre' => $datos['nombre'],
                'descripcion' => $datos['descripcion'] ?? null,
                'direccion' => $datos['direccion'],
                'colonia' => $datos['colonia'] ?? null,
                'municipio' => $datos['municipio'],
                'estado' => $datos['estado'],
                'codigo_postal' => $datos['codigo_postal'] ?? null,
                'telefono' => $datos['telefono'] ?? null,
                'correo' => $datos['correo'] ?? null,
                'horario' => $datos['horario'] ?? null,
                'latitud' => $datos['latitud'] ?? null,
                'longitud' => $datos['longitud'] ?? null,
                'estado_validacion' => 'pendiente',
                'activo' => true,
            ]);

            $centro->materiales()->sync($datos['materiales']);
        });

        return redirect()
            ->route('centros.index')
            ->with('success', 'Centro registrado. Está pendiente de validación.');
    }

    /**
     * Mostrar información de un centro propio.
     */
 /**
 * Mostrar información de un centro propio.
 */
public function show(Request $request, string $id)
{
    $this->verificarDueno($request);

    $centro = $request->user()
        ->centros()
        ->with('materiales')
        ->findOrFail($id);

    $tieneSolicitudPendiente = SolicitudActualizacionCentro::where(
        'fk_id_centro',
        $centro->id
    )
        ->where('estado', 'pendiente')
        ->exists();

    return view(
        'ecomap.centros.show',
        compact('centro', 'tieneSolicitudPendiente')
    );
}

    /**
     * Mostrar formulario para solicitar una actualización.
     */
    public function edit(Request $request, string $id)
    {
        $this->verificarDueno($request);

        $centro = $request->user()
            ->centros()
            ->with('materiales')
            ->findOrFail($id);

        abort_unless(
            $centro->estado_validacion === 'validado' &&
            $centro->activo,
            403,
            'Solo puedes solicitar cambios en centros activos y validados.'
        );

        $tienePendiente = SolicitudActualizacionCentro::where(
            'fk_id_centro',
            $centro->id
        )
            ->where('estado', 'pendiente')
            ->exists();

        if ($tienePendiente) {
            return redirect()
                ->route('centros.show', $centro->id)
                ->with('error', 'Este centro ya tiene una solicitud de actualización pendiente.');
        }

        $materiales = Material::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view(
            'ecomap.centros.edit',
            compact('centro', 'materiales')
        );
    }

    /**
     * Guardar una solicitud de actualización.
     * No modifica directamente el centro.
     */
    public function update(Request $request, string $id)
    {
        $this->verificarDueno($request);

        $centro = $request->user()
            ->centros()
            ->findOrFail($id);

        abort_unless(
            $centro->estado_validacion === 'validado' &&
            $centro->activo,
            403,
            'Este centro no admite solicitudes de actualización.'
        );

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string'],
            'direccion' => ['required', 'string', 'max:200'],
            'colonia' => ['nullable', 'string', 'max:100'],
            'municipio' => ['required', 'string', 'max:100'],
            'estado' => ['required', 'string', 'max:100'],
            'codigo_postal' => ['nullable', 'string', 'max:10'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'correo' => ['nullable', 'email', 'max:120'],
            'horario' => ['nullable', 'string'],
            'latitud' => ['nullable', 'numeric', 'between:-90,90'],
            'longitud' => ['nullable', 'numeric', 'between:-180,180'],
            'materiales' => ['required', 'array', 'min:1'],
            'materiales.*' => [
                'required',
                'integer',
                Rule::exists('materiales', 'id')
                    ->where('activo', true),
            ],
        ]);

        $creada = DB::transaction(function () use ($request, $id, $datos) {

            // Bloquear el centro mientras se comprueba si existe una solicitud pendiente.
            $centro = $request->user()
                ->centros()
                ->whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $centro->estado_validacion === 'validado' &&
                $centro->activo,
                403,
                'Este centro no admite solicitudes de actualización.'
            );

            $tienePendiente = SolicitudActualizacionCentro::where(
                'fk_id_centro',
                $centro->id
            )
                ->where('estado', 'pendiente')
                ->exists();

            if ($tienePendiente) {
                return false;
            }

            $solicitud = SolicitudActualizacionCentro::create([
                'fk_id_centro' => $centro->id,
                'fk_id_usuario_solicita' => $request->user()->id,
                'datos_propuestos' => [
                    'nombre' => $datos['nombre'],
                    'descripcion' => $datos['descripcion'] ?? null,
                    'direccion' => $datos['direccion'],
                    'colonia' => $datos['colonia'] ?? null,
                    'municipio' => $datos['municipio'],
                    'estado' => $datos['estado'],
                    'codigo_postal' => $datos['codigo_postal'] ?? null,
                    'telefono' => $datos['telefono'] ?? null,
                    'correo' => $datos['correo'] ?? null,
                    'horario' => $datos['horario'] ?? null,
                    'latitud' => $datos['latitud'] ?? null,
                    'longitud' => $datos['longitud'] ?? null,
                ],
                'estado' => 'pendiente',
            ]);

            $solicitud->materiales()->sync($datos['materiales']);

            return true;
        });

        if (!$creada) {
            return redirect()
                ->route('centros.show', $id)
                ->with('error', 'Este centro ya tiene una solicitud de actualización pendiente.');
        }

        return redirect()
            ->route('centros.show', $id)
            ->with('success', 'Solicitud de actualización enviada.');
    }

    /**
     * La baja corresponde al administrador.
     */
    public function destroy(Request $request, string $id)
    {
        $this->verificarDueno($request);

        abort(403, 'La baja de centros corresponde al administrador.');
    }
}
