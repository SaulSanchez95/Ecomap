<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CentroAcopio;
use Illuminate\Http\Request;

class CentroValidacionController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(
            $request->user()?->esAdministrador(),
            403
        );

        $centrosPendientes = CentroAcopio::with([
            'dueno',
            'materiales'
        ])
            ->where('estado_validacion', 'pendiente')
            ->orderByDesc('created_at')
            ->get();

        return view(
            'ecomap.admin.centros-pendientes',
            compact('centrosPendientes')
        );
    }

public function rechazar(Request $request, string $id)
{
    // Verificar permisos del administrador
    abort_unless(
        $request->user()?->esAdministrador(),
        403
    );

    // Solicitar un motivo de rechazo
    $datos = $request->validate([
        'observaciones_admin' => [
            'required',
            'string',
            'max:1000'
        ],
    ]);

    // Rechazar únicamente centros pendientes
    $actualizados = CentroAcopio::whereKey($id)
        ->where('estado_validacion', 'pendiente')
        ->update([
            'estado_validacion' => 'rechazado',
            'activo' => false,
            'fk_id_usuario_valida' => $request->user()->id,
            'fecha_validacion' => now(),
            'observaciones_admin' => $datos['observaciones_admin'],
        ]);

    abort_unless(
        $actualizados === 1,
        404,
        'El centro ya fue revisado o no existe.'
    );

    return redirect()
        ->route('admin.centros.pendientes')
        ->with('success', 'Centro rechazado correctamente.');
}

}
