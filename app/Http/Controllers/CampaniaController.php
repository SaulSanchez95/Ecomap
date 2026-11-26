<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use App\Models\CentroAcopio;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;

class CampaniaController extends Controller
{
    /**
     * Verificar que el usuario tenga el rol de dueño.
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
     * Comprobar que los materiales seleccionados estén activos.
     */
    private function validarMaterialesActivos(array $materiales): array
    {
        $ids = collect($materiales)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $idsActivos = Material::whereIn('id', $ids)
            ->where('activo', true)
            ->lockForUpdate()
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if (count($idsActivos) !== count($ids)) {
            throw ValidationException::withMessages([
                'materiales' => 'Uno o más materiales ya no están disponibles.',
            ]);
        }

        $resultado = [];

        foreach ($materiales as $material) {
            $resultado[(int) $material['id']] = [
                'cantidad_solicitada' => $material['cantidad'],
            ];
        }

        return $resultado;
    }

    /**
     * Comprobar que el centro acepte todos los materiales seleccionados.
     */
    private function validarMaterialesAceptados(
        CentroAcopio $centro,
        array $materiales
    ): void {
        $idsAceptados = $centro->materiales()
            ->pluck('materiales.id')
            ->map(fn ($id) => (int) $id)
            ->all();

        foreach ($materiales as $material) {
            $idMaterial = (int) $material['id'];

            if (!in_array($idMaterial, $idsAceptados, true)) {
                throw ValidationException::withMessages([
                    'materiales' => 'El centro seleccionado no acepta uno de los materiales indicados.',
                ]);
            }
        }
    }

    /**
     * Mostrar las campañas de los centros pertenecientes al dueño.
     */
    public function index(Request $request)
    {
        $this->verificarDueno($request);

        $campanias = Campania::whereHas('centro', function ($query) use ($request) {
            $query->where('fk_id_usuario', $request->user()->id);
        })
            ->with(['centro', 'materiales'])
            ->latest()
            ->get();

        return view(
            'ecomap.campanias.index',
            compact('campanias')
        );
    }

    /**
     * Mostrar el formulario para crear una campaña.
     */
    public function create(Request $request)
    {
        $this->verificarDueno($request);

        $centros = $request->user()
            ->centros()
            ->where('estado_validacion', 'validado')
            ->where('activo', true)
            ->with('materiales')
            ->orderBy('nombre')
            ->get();

        $materiales = Material::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view(
            'ecomap.campanias.create',
            compact('centros', 'materiales')
        );
    }

    /**
     * Registrar una campaña.
     */
    public function store(Request $request)
    {
        $this->verificarDueno($request);

        $datos = $request->validate([
            'fk_id_centro' => [
                'required',
                'integer',
                Rule::exists('centros_acopio', 'id')
                    ->where('fk_id_usuario', $request->user()->id)
                    ->where('estado_validacion', 'validado')
                    ->where('activo', true),
            ],

            'titulo' => [
                'required',
                'string',
                'max:120',
            ],

            'descripcion' => [
                'nullable',
                'string',
            ],

            'fecha_inicio' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'fecha_fin' => [
                'required',
                'date',
                'after_or_equal:fecha_inicio',
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
                'max:999999999.999',
            ],
        ]);

        DB::transaction(function () use ($datos, $request) {

            // Recuperar y bloquear el centro seleccionado.
            $centro = CentroAcopio::whereKey($datos['fk_id_centro'])
                ->where('fk_id_usuario', $request->user()->id)
                ->where('estado_validacion', 'validado')
                ->where('activo', true)
                ->lockForUpdate()
                ->firstOrFail();

            // Validar que el centro acepte los materiales seleccionados.
            $this->validarMaterialesAceptados(
                $centro,
                $datos['materiales']
            );

            // Validar que todos los materiales sigan activos.
            $materiales = $this->validarMaterialesActivos(
                $datos['materiales']
            );

            // Crear la campaña inicialmente como borrador.
            $campania = Campania::create([
                'fk_id_centro' => $centro->id,
                'titulo' => $datos['titulo'],
                'descripcion' => $datos['descripcion'] ?? null,
                'fecha_inicio' => $datos['fecha_inicio'],
                'fecha_fin' => $datos['fecha_fin'],
                'estado' => 'borrador',
            ]);

            // Asociar los materiales y sus cantidades solicitadas.
            $campania->materiales()->sync($materiales);
        });

        return redirect()
            ->route('campanias.index')
            ->with(
                'success',
                'Campaña registrada correctamente.'
            );
    }

    /**
     * Mostrar los detalles de una campaña.
     */
    public function show(Request $request, string $id)
    {
        $this->verificarDueno($request);

        $campania = Campania::whereHas('centro', function ($query) use ($request) {
            $query->where('fk_id_usuario', $request->user()->id);
        })
            ->with(['centro', 'materiales'])
            ->findOrFail($id);

        return view(
            'ecomap.campanias.show',
            compact('campania')
        );
    }

    /**
     * Mostrar el formulario para editar una campaña.
     */
    public function edit(Request $request, string $id)
    {
        $this->verificarDueno($request);

        $campania = Campania::whereHas('centro', function ($query) use ($request) {
            $query->where('fk_id_usuario', $request->user()->id);
        })
            ->with(['centro', 'materiales'])
            ->findOrFail($id);

        // Solamente se pueden modificar campañas en borrador.
        abort_unless(
            $campania->estado === 'borrador',
            403,
            'Solo puedes modificar campañas que estén en borrador.'
        );

        // Mostrar materiales activos y los que ya están asociados,
        // aunque estos últimos se hayan desactivado.
        $materiales = Material::where('activo', true)
            ->orWhereIn(
                'id',
                $campania->materiales->pluck('id')
            )
            ->orderBy('nombre')
            ->get();

        return view(
            'ecomap.campanias.edit',
            compact('campania', 'materiales')
        );
    }

    /**
     * Actualizar una campaña que esté en borrador.
     */
    public function update(Request $request, string $id)
    {
        $this->verificarDueno($request);

        $datos = $request->validate([
            'titulo' => [
                'required',
                'string',
                'max:120',
            ],

            'descripcion' => [
                'nullable',
                'string',
            ],

            'fecha_inicio' => [
                'required',
                'date',
            ],

            'fecha_fin' => [
                'required',
                'date',
                'after_or_equal:fecha_inicio',
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
                'max:999999999.999',
            ],
        ]);

        $campania = DB::transaction(function () use ($request, $id, $datos) {

            // Recuperar y bloquear una campaña del dueño.
            $campania = Campania::whereHas('centro', function ($query) use ($request) {
                $query->where('fk_id_usuario', $request->user()->id);
            })
                ->whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $campania->estado === 'borrador',
                403,
                'Solo puedes modificar campañas que estén en borrador.'
            );

            // Comprobar que el centro relacionado exista.
            $campania->load('centro');

            $centro = $campania->centro;

            abort_unless(
                $centro,
                404,
                'No se encontró el centro de la campaña.'
            );

            // Comprobar las fechas.
            $fechaInicioNueva = Carbon::parse(
                $datos['fecha_inicio']
            )->toDateString();

            $fechaInicioOriginal = Carbon::parse(
                $campania->fecha_inicio
            )->toDateString();

            if (
                $fechaInicioNueva !== $fechaInicioOriginal &&
                $fechaInicioNueva < now()->toDateString()
            ) {
                throw ValidationException::withMessages([
                    'fecha_inicio' => 'No puedes cambiar la fecha de inicio por una fecha pasada.',
                ]);
            }

            // Comprobar que el centro acepte los materiales seleccionados.
            $this->validarMaterialesAceptados(
                $centro,
                $datos['materiales']
            );

            // Comprobar que los materiales seleccionados estén activos.
            $materiales = $this->validarMaterialesActivos(
                $datos['materiales']
            );

            // Recuperar los materiales inactivos que ya estaban asociados.
            $materialesInactivos = $campania->materiales()
                ->where('materiales.activo', false)
                ->get();

            // Conservar sus asociaciones y cantidades originales.
            foreach ($materialesInactivos as $material) {
                $materiales[$material->id] = [
                    'cantidad_solicitada' =>
                        $material->pivot->cantidad_solicitada,
                ];
            }

            // Actualizar los datos de la campaña.
            $campania->update([
                'titulo' => $datos['titulo'],
                'descripcion' => $datos['descripcion'] ?? null,
                'fecha_inicio' => $datos['fecha_inicio'],
                'fecha_fin' => $datos['fecha_fin'],
            ]);

            // Sincronizar materiales sin perder los inactivos anteriores.
            $campania->materiales()->sync($materiales);

            return $campania;
        });

        return redirect()
            ->route('campanias.show', $campania->id)
            ->with(
                'success',
                'Campaña actualizada correctamente.'
            );
    }

    /**
     * Eliminar una campaña que esté en borrador.
     */
    public function destroy(Request $request, string $id)
    {
        $this->verificarDueno($request);

        DB::transaction(function () use ($request, $id) {

            $campania = Campania::whereHas('centro', function ($query) use ($request) {
                $query->where('fk_id_usuario', $request->user()->id);
            })
                ->whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $campania->estado === 'borrador',
                403,
                'Solo puedes eliminar campañas que estén en borrador.'
            );

            $campania->delete();
        });

        return redirect()
            ->route('campanias.index')
            ->with(
                'success',
                'Campaña eliminada correctamente.'
            );
    }
}
