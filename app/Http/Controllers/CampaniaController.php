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
    private function verificarDueno(Request $request): void
    {
        abort_unless(
            $request->user() &&
            $request->user()->rol === 'dueno',
            403,
            'No tienes permiso para realizar esta operación.'
        );
    }

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

        abort_unless(
            count($idsActivos) === count($ids),
            422,
            'Uno o más materiales ya no están disponibles.'
        );

        $resultado = [];

        foreach ($materiales as $material) {
            $resultado[(int) $material['id']] = [
                'cantidad_solicitada' => $material['cantidad'],
            ];
        }

        return $resultado;
    }

    public function index(Request $request)
    {
        $this->verificarDueno($request);

        $campanias = Campania::whereHas('centro', function ($query) use ($request) {
            $query->where('fk_id_usuario', $request->user()->id);
        })
            ->with(['centro', 'materiales'])
            ->latest()
            ->get();

        return view('ecomap.campanias.index', compact('campanias'));
    }

    public function create(Request $request)
    {
        $this->verificarDueno($request);

        $centros = $request->user()
            ->centros()
            ->where('estado_validacion', 'validado')
            ->where('activo', true)
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
            'titulo' => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string'],
            'fecha_inicio' => ['required', 'date', 'after_or_equal:today'],
            'fecha_fin' => [
                'required',
                'date',
                'after_or_equal:fecha_inicio',
            ],
            'materiales' => ['required', 'array', 'min:1'],
            'materiales.*' => ['required', 'array'],
            'materiales.*.id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('materiales', 'id')->where('activo', true),
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

            $centro = CentroAcopio::whereKey($datos['fk_id_centro'])
                ->where('fk_id_usuario', $request->user()->id)
                ->where('estado_validacion', 'validado')
                ->where('activo', true)
                ->lockForUpdate()
                ->firstOrFail();

            $materiales = $this->validarMaterialesActivos(
                $datos['materiales']
            );

            $campania = Campania::create([
                'fk_id_centro' => $centro->id,
                'titulo' => $datos['titulo'],
                'descripcion' => $datos['descripcion'] ?? null,
                'fecha_inicio' => $datos['fecha_inicio'],
                'fecha_fin' => $datos['fecha_fin'],
                'estado' => 'borrador',
            ]);

            $campania->materiales()->sync($materiales);
        });

        return redirect()
            ->route('campanias.index')
            ->with('success', 'Campaña registrada correctamente.');
    }

    public function show(Request $request, string $id)
    {
        $this->verificarDueno($request);

        $campania = Campania::whereHas('centro', function ($query) use ($request) {
            $query->where('fk_id_usuario', $request->user()->id);
        })
            ->with(['centro', 'materiales'])
            ->findOrFail($id);

        return view('ecomap.campanias.show', compact('campania'));
    }

    public function edit(Request $request, string $id)
    {
        $this->verificarDueno($request);

        $campania = Campania::whereHas('centro', function ($query) use ($request) {
            $query->where('fk_id_usuario', $request->user()->id);
        })
            ->with(['centro', 'materiales'])
            ->findOrFail($id);

        abort_unless(
            $campania->estado === 'borrador',
            403,
            'Solo puedes modificar campañas que estén en borrador.'
        );

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

    public function update(Request $request, string $id)
    {
        $this->verificarDueno($request);

        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'materiales' => ['required', 'array', 'min:1'],
            'materiales.*' => ['required', 'array'],
            'materiales.*.id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('materiales', 'id')->where('activo', true),
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

            // Comprobar la fecha de inicio usando la campaña bloqueada.
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

            // Validar los materiales activos seleccionados.
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

            $campania->update([
                'titulo' => $datos['titulo'],
                'descripcion' => $datos['descripcion'] ?? null,
                'fecha_inicio' => $datos['fecha_inicio'],
                'fecha_fin' => $datos['fecha_fin'],
            ]);

            // Sincronizar sin perder los materiales inactivos anteriores.
            $campania->materiales()->sync($materiales);

            return $campania;
        });

        return redirect()
            ->route('campanias.show', $campania->id)
            ->with('success', 'Campaña actualizada correctamente.');
    }

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
            ->with('success', 'Campaña eliminada correctamente.');
    }
}
