<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CentroAcopio;
use Illuminate\Http\Request;

class CentroValidacionController extends Controller
{
    /**
     * Mostrar los centros de acopio pendientes de validación.
     */
    public function index(Request $request)
    {
        // Verificar que el usuario sea administrador.
        abort_unless(
            $request->user()?->esAdministrador(),
            403,
            'No tienes permisos para consultar esta sección.'
        );

        // Obtener centros pendientes junto con sus dueños y materiales.
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

    /**
     * Aprobar y activar un centro de acopio.
     */
    public function aprobar(Request $request, string $id)
    {
        // Verificar permisos del administrador.
        abort_unless(
            $request->user()?->esAdministrador(),
            403,
            'No tienes permisos para aprobar centros.'
        );

        // Aprobar únicamente centros pendientes.
        $actualizados = CentroAcopio::whereKey($id)
            ->where('estado_validacion', 'pendiente')
            ->update([
                'estado_validacion' => 'validado',
                'activo' => true,
                'fk_id_usuario_valida' => $request->user()->id,
                'fecha_validacion' => now(),
            ]);

        // Evitar aprobar centros inexistentes o ya revisados.
        abort_unless(
            $actualizados === 1,
            404,
            'El centro ya fue revisado o no existe.'
        );

        return redirect()
            ->route('admin.centros.pendientes')
            ->with(
                'success',
                'Centro aprobado y activado correctamente.'
            );
    }

    /**
     * Rechazar un centro de acopio.
     */
    public function rechazar(Request $request, string $id)
    {
        // Verificar permisos del administrador.
        abort_unless(
            $request->user()?->esAdministrador(),
            403,
            'No tienes permisos para rechazar centros.'
        );

        // Solicitar un motivo de rechazo.
        $datos = $request->validate([
            'observaciones_admin' => [
                'required',
                'string',
                'max:1000'
            ],
        ]);

        // Rechazar únicamente centros pendientes.
        $actualizados = CentroAcopio::whereKey($id)
            ->where('estado_validacion', 'pendiente')
            ->update([
                'estado_validacion' => 'rechazado',
                'activo' => false,
                'fk_id_usuario_valida' => $request->user()->id,
                'fecha_validacion' => now(),
                'observaciones_admin' => $datos['observaciones_admin'],
            ]);

        // Evitar rechazar centros inexistentes o ya revisados.
        abort_unless(
            $actualizados === 1,
            404,
            'El centro ya fue revisado o no existe.'
        );

        return redirect()
            ->route('admin.centros.pendientes')
            ->with(
                'success',
                'Centro rechazado correctamente.'
            );
    }
}
