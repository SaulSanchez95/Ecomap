<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {

$usuario = request()->user();

abort_unless($usuario && $usuario->esAdministrador(), 403);

        // Total de usuarios registrados
        $totalUsuarios = DB::table('users')->count();

        // Total de centros de acopio
        $totalCentros = DB::table('centros_acopio')->count();

        // Centros pendientes de validación
        $centrosPendientes = DB::table('centros_acopio')
            ->where('estado_validacion', 'pendiente')
            ->count();

        // Enviar estadísticas al panel administrativo
        return view('ecomap.admin.dashboard', compact(
            'totalUsuarios',
            'totalCentros',
            'centrosPendientes'
        ));
    }
}
