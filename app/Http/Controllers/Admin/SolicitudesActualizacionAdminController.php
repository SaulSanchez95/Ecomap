<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\SolicitudActualizacionCentro;
use Illuminate\Http\Request;

class SolicitudesActualizacionAdminController extends Controller
{
    public function index(Request $request)
    {
        // Solo pueden acceder administradores.
        abort_unless(
            $request->user()?->esAdministrador(),
            403
        );

        // Obtener las solicitudes pendientes.
        $solicitudes = SolicitudActualizacionCentro::with([
            'centro.materiales',
            'solicitante',
            'materiales'
        ])
            ->where('estado', 'pendiente')
            ->latest()
            ->paginate(15);

        return view(
            'ecomap.admin.solicitudes-actualizacion',
            compact('solicitudes')
        );
    }

public function aprobar(Request $request, string $id)
{
    // Comprobar permisos del administrador
    abort_unless(
        $request->user()?->esAdministrador(),
        403
    );

    DB::transaction(function () use ($request, $id) {

        // Bloquear la solicitud durante la revisión
        $solicitud = SolicitudActualizacionCentro::whereKey($id)
            ->lockForUpdate()
            ->firstOrFail();

        abort_unless(
            $solicitud->estado === 'pendiente',
            409,
            'Esta solicitud ya fue revisada.'
        );

        // Obtener el centro correspondiente
        $centro = $solicitud->centro()
            ->lockForUpdate()
            ->firstOrFail();

        abort_unless(
            $centro->estado_validacion === 'validado'
                && $centro->activo,
            409,
            'El centro no está habilitado para actualizarse.'
        );

        // Campos que pueden actualizarse
        $camposPermitidos = [
            'nombre',
            'descripcion',
            'direccion',
            'colonia',
            'municipio',
            'estado',
            'codigo_postal',
            'telefono',
            'correo',
            'horario',
            'latitud',
            'longitud'
        ];

        $datos = array_intersect_key(
            $solicitud->datos_propuestos ?? [],
            array_flip($camposPermitidos)
        );

        // Aplicar los datos aprobados al centro
        $centro->update($datos);

        // Actualizar los materiales aceptados
        $materiales = $solicitud->materiales()
            ->pluck('materiales.id')
            ->all();

        $centro->materiales()->sync($materiales);

        // Registrar quién autorizó la actualización
        $solicitud->update([
            'estado' => 'aprobada',
            'fk_id_usuario_revisa' => $request->user()->id,
            'fecha_revision' => now()
        ]);
    });

    return redirect()
        ->route('admin.actualizaciones.index')
        ->with(
            'success',
            'Solicitud aprobada y centro actualizado correctamente.'
        );
}

public function rechazar(Request $request, string $id)
{
    // Solo los administradores pueden rechazar solicitudes
    abort_unless(
        $request->user()?->esAdministrador(),
        403
    );

    // El administrador debe indicar el motivo
    $datos = $request->validate([
        'observaciones_admin' => [
            'required',
            'string',
            'max:1000'
        ],
    ]);

    DB::transaction(function () use ($request, $id, $datos) {

        // Bloquear la solicitud durante la revisión
        $solicitud = SolicitudActualizacionCentro::whereKey($id)
            ->lockForUpdate()
            ->firstOrFail();

        // Solo se pueden rechazar solicitudes pendientes
        abort_unless(
            $solicitud->estado === 'pendiente',
            409,
            'Esta solicitud ya fue revisada.'
        );

        // Registrar el rechazo sin modificar el centro
        $solicitud->update([
            'estado' => 'rechazada',
            'fk_id_usuario_revisa' => $request->user()->id,
            'fecha_revision' => now(),
            'observaciones_admin' => $datos['observaciones_admin'],
        ]);
    });

    return redirect()
        ->route('admin.actualizaciones.index')
        ->with(
            'success',
            'Solicitud rechazada correctamente.'
        );
}

}
