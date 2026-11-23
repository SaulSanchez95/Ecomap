<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse;

class RoleBasedLoginResponse implements LoginResponse
{
    public function toResponse($request)
    {
        $usuario = $request->user();

        if ($usuario && $usuario->esAdministrador()) {
            return redirect()->intended(
                route('admin.dashboard')
            );
        }

        return redirect()->intended(
            route('inicio')
        );
    }
}
