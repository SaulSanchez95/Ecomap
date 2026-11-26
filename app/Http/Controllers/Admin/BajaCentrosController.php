<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CentroAcopio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BajaCentrosController extends Controller
{
    public function store(Request $request)
    {
        // Solo administradores
        abort_unless(
            $request->user()?->esAdministrador(),
            403
        );

        // Validar los centros seleccionados
        $datos = $request->validate([
            'centros' => ['required', 'array', 'min:1', 'max:100'],
            'centros.*' => ['required', 'integer', 'distinct'],
        ]);

        $ids = $datos['centros'];

        $resultado = DB::transaction(function () use ($ids) {

            $centros = CentroAcopio::whereIn('id', $ids)
                ->lockForUpdate()
                ->get();

            // Todos los centros deben existir
            if ($centros->count() !== count($ids)) {
                return false;
            }

            foreach ($centros as $centro) {

                // Registrar la baja
                $centro->activo = false;
                $centro->fecha_baja = now();
                $centro->save();

                // Baja lógica
                $centro->delete();
            }

            return true;
        });

        if (!$resultado) {
            return back()->with(
                'error',
                'La selección contiene centros no válidos.'
            );
        }

        return redirect()
            ->route('admin.centros.index')
            ->with('success', 'Centros dados de baja correctamente.');
    }
}
