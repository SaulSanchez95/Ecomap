<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BajaUsuariosController extends Controller
{
    public function store(Request $request)
    {
        // Solo los administradores pueden dar de baja.
        abort_unless(
            $request->user()?->esAdministrador(),
            403
        );

        // Validar los usuarios seleccionados.
        $datos = $request->validate([
            'usuarios' => ['required', 'array', 'min:1', 'max:100'],
            'usuarios.*' => ['required', 'integer', 'distinct'],
        ]);

        $usuarios = User::whereIn('id', $datos['usuarios'])->get();

        // No permitir bajas de administradores desde esta pantalla.
        if (
            $usuarios->count() !== count($datos['usuarios']) ||
            $usuarios->contains(
                fn ($usuario) => !in_array(
                    $usuario->rol,
                    ['ciudadano', 'dueno'],
                    true
                )
            )
        ) {
            return back()->with(
                'error',
                'La selección contiene usuarios no válidos.'
            );
        }

        // Evitar dejar centros activos sin su propietario.
        foreach ($usuarios as $usuario) {
            if (
                $usuario->esDueno() &&
                $usuario->centros()->where('activo', true)->exists()
            ) {
                return back()->with(
                    'error',
                    'No puedes dar de baja a un dueño con centros activos.'
                );
            }
        }

        DB::transaction(function () use ($usuarios) {
            foreach ($usuarios as $usuario) {
                $usuario->delete();
            }
        });

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuarios dados de baja correctamente.');
    }
}
