<?php

namespace App\Http\Controllers;

use App\Models\SolicitudActualizacionCentro;
use Illuminate\Http\Request;

class SolicitudActualizacionCentroController extends Controller
{
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

    // Muestra las solicitudes de actualización del dueño.
    public function index(Request $request)
    {
        $this->verificarDueno($request);

        $solicitudes = SolicitudActualizacionCentro::where(
            'fk_id_usuario_solicita',
            $request->user()->id
        )
            ->with([
                'centro',
                'solicitante',
                'revisor',
                'materiales',
            ])
            ->latest()
            ->get();

        return view(
            'ecomap.actualizaciones.index',
            compact('solicitudes')
        );
    }

    // Muestra los detalles de una solicitud del dueño.
    public function show(Request $request, string $id)
    {
        $this->verificarDueno($request);

        $solicitud = SolicitudActualizacionCentro::where(
            'fk_id_usuario_solicita',
            $request->user()->id
        )
            ->with([
                'centro',
                'solicitante',
                'revisor',
                'materiales',
            ])
            ->findOrFail($id);

        return view(
            'ecomap.actualizaciones.show',
            compact('solicitud')
        );
    }
}