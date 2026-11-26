<?php

namespace App\Http\Controllers;

use App\Models\CentroAcopio;
use App\Models\RegistroAcopioDiario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegistroAcopioDiarioController extends Controller
{
    // Verifica que el usuario sea dueño.
    private function verificarDueno(Request $request): void
    {
        abort_unless(
            $request->user() &&
            $request->user()->rol === 'dueno',
            403,
            'No tienes permiso para realizar esta operación.'
        );
    }

    // Muestra el historial de registros de los centros del dueño.
    public function index(Request $request)
    {
        $this->verificarDueno($request);

        $registros = RegistroAcopioDiario::whereHas('centro', function ($query) use ($request) {
            $query->where('fk_id_usuario', $request->user()->id);
        })
            ->with([
                'centro',
                'material',
                'usuarioRegistra',
            ])
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->get();

        return view(
            'ecomap.registros.index',
            compact('registros')
        );
    }

    // Muestra el formulario para registrar material recibido.
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

        return view(
            'ecomap.registros.create',
            compact('centros')
        );
    }

    // Guarda un registro de material recibido.
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

            'fk_id_material' => [
                'required',
                'integer',
                Rule::exists('materiales', 'id')
                    ->where('activo', true),
            ],

            'cantidad_recibida' => [
                'required',
                'numeric',
                'gt:0',
                'decimal:0,3',
            ],

            'fecha' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'observaciones' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        // Recupera el centro y comprueba nuevamente su estado.
        $centro = CentroAcopio::with('materiales')
            ->where('fk_id_usuario', $request->user()->id)
            ->where('estado_validacion', 'validado')
            ->where('activo', true)
            ->findOrFail($datos['fk_id_centro']);

        // Comprueba que el centro acepte el material seleccionado.
        abort_unless(
            $centro->materiales->contains(
                'id',
                (int) $datos['fk_id_material']
            ),
            422,
            'Este centro no acepta el material seleccionado.'
        );

        RegistroAcopioDiario::create([
            'fk_id_centro' => $datos['fk_id_centro'],
            'fk_id_material' => $datos['fk_id_material'],
            'fk_id_usuario_registra' => $request->user()->id,
            'cantidad_recibida' => $datos['cantidad_recibida'],
            'fecha' => $datos['fecha'],
            'observaciones' => $datos['observaciones'] ?? null,
        ]);

        return redirect()
            ->route('registros.index')
            ->with(
                'success',
                'El registro de acopio se guardó correctamente.'
            );
    }

    // Muestra los detalles de un registro.
    public function show(Request $request, string $id)
    {
        $this->verificarDueno($request);

        $registro = RegistroAcopioDiario::whereHas('centro', function ($query) use ($request) {
            $query->where('fk_id_usuario', $request->user()->id);
        })
            ->with([
                'centro',
                'material',
                'usuarioRegistra',
            ])
            ->findOrFail($id);

        return view(
            'ecomap.registros.show',
            compact('registro')
        );
    }
}
