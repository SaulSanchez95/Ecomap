<?php

namespace App\Http\Controllers\Usuarios;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

// Módulo de usuarios - Anabel.
class RedirigirUsuarioController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $destino = match ($request->user()->rol) {
            'ciudadano' => 'ciudadano',
            'dueno' => 'encargado',
            // La interfaz administrativa corresponde a otro módulo del equipo.
            'administrador' => 'inicio',
            default => abort(403, 'Tu cuenta no tiene un rol válido.'),
        };

        return redirect()->route($destino);
    }
}
