<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdministradorController extends Controller
{
    public function create(Request $request)
    {
        abort_unless(
            $request->user()?->esAdministrador(),
            403
        );

        return view('ecomap.admin.crear-administrador');
    }

    public function store(Request $request)
    {
        abort_unless(
            $request->user()?->esAdministrador(),
            403
        );

        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(12)],
        ]);

        User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
            'rol' => 'administrador',
            'fk_id_usuario_crea' => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Administrador registrado correctamente.');
    }
}
