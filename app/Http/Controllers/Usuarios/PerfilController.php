<?php

namespace App\Http\Controllers\Usuarios;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

// Módulo de usuarios - Anabel.
class PerfilController
{
    public function edit(): View
    {
        return view('auth.perfil');
    }

    public function update(Request $request): RedirectResponse
    {
        $usuario = $request->user();

        $datos = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($usuario->getKey()),
            ],
            'telefono' => ['nullable', 'string', 'max:20'],
        ])->validate();

        if ($usuario->email !== $datos['email']) {
            $usuario->email_verified_at = null;
        }

        $usuario->name = $datos['name'];
        $usuario->email = $datos['email'];
        $usuario->telefono = $datos['telefono'] ?? null;
        // No se permite editar el rol desde el formulario público.
        $usuario->save();

        return redirect()->route('perfil')->with('status', 'perfil-actualizado');
    }
}
