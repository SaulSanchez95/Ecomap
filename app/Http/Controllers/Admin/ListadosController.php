<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CentroAcopio;
use Illuminate\Http\Request;

class ListadosController extends Controller
{
    // Mostrar ciudadanos y dueños registrados
    public function usuarios(Request $request)
    {
        abort_unless(
            $request->user()?->esAdministrador(),
            403
        );

        $usuarios = User::where(
            'rol', '!=', 'administrador'
        )
            ->orderByDesc('created_at')
            ->paginate(15);

        return view(
            'ecomap.admin.usuarios',
            compact('usuarios')
        );
    }

    // Mostrar administradores registrados
    public function administradores(Request $request)
    {
        abort_unless(
            $request->user()?->esAdministrador(),
            403
        );

        $administradores = User::where(
            'rol', 'administrador'
        )
            ->orderByDesc('created_at')
            ->paginate(15);

        return view(
            'ecomap.admin.administradores',
            compact('administradores')
        );
    }

    // Mostrar todos los centros registrados
    public function centros(Request $request)
    {
        abort_unless(
            $request->user()?->esAdministrador(),
            403
        );

        $centros = CentroAcopio::with([
            'dueno',
            'materiales'
        ])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view(
            'ecomap.admin.centros-registrados',
            compact('centros')
        );
    }
}
