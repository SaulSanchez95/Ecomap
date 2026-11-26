<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BajaAdministradoresController extends Controller
{
    public function store(Request $request)
    {
        // Solo administradores pueden realizar bajas
        abort_unless(
            $request->user()?->esAdministrador(),
            403
        );

        $datos = $request->validate([
            'administradores' => ['required', 'array', 'min:1', 'max:100'],
            'administradores.*' => ['required', 'integer', 'distinct'],
        ]);

        $ids = array_map('intval', $datos['administradores']);

        // No permitir que el administrador se dé de baja a sí mismo
        if (in_array((int) $request->user()->id, $ids, true)) {
            return back()->with(
                'error',
                'No puedes dar de baja tu propia cuenta.'
            );
        }

        return DB::transaction(function () use ($ids) {

            // Bloquear los registros mientras se realiza la operación
            $administradores = User::where('rol', 'administrador')
                ->lockForUpdate()
                ->get();

            $seleccionados = $administradores->whereIn('id', $ids);

            // Validar que todos los seleccionados sean administradores activos
            if ($seleccionados->count() !== count($ids)) {
                return back()->with(
                    'error',
                    'La selección contiene administradores no válidos.'
                );
            }

            // Evitar dejar el sistema sin administradores
            if ($administradores->count() <= $seleccionados->count()) {
                return back()->with(
                    'error',
                    'Debe permanecer al menos un administrador activo.'
                );
            }

            foreach ($seleccionados as $administrador) {
                $administrador->delete();
            }

            return redirect()
                ->route('admin.administradores.index')
                ->with(
                    'success',
                    'Administradores dados de baja correctamente.'
                );
        });
    }
}
