
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CentroAcopioController;
use App\Http\Controllers\CampaniaController;
use App\Http\Controllers\SolicitudAcopioController;
use App\Http\Controllers\SolicitudActualizacionCentroController;
use App\Http\Controllers\RegistroAcopioDiarioController;

// Página principal de EcoMap
Route::view('/', 'ecomap.inicio')->name('inicio');

// Portal del ciudadano
Route::view('/ciudadano', 'ecomap.ciudadano')->name('ciudadano');

// Panel del encargado
Route::view('/encargado', 'ecomap.encargado')->name('encargado');

// Módulos protegidos de EcoMap
Route::middleware('auth')->group(function () {

    // Centros de acopio
    Route::resource(
        'centros',
        CentroAcopioController::class
    )->only([
        'index',
        'create',
        'store',
        'show',
        'edit',
        'update'
    ]);

    // Campañas
    Route::resource(
        'campanias',
        CampaniaController::class
    );

    // Solicitudes de acopio
    Route::get(
        'solicitudes/create',
        [SolicitudAcopioController::class, 'create']
    )->name('solicitudes.create');

    Route::post(
        'solicitudes',
        [SolicitudAcopioController::class, 'store']
    )->name('solicitudes.store');

    Route::get(
        'solicitudes',
        [SolicitudAcopioController::class, 'index']
    )->name('solicitudes.index');

    Route::get(
        'solicitudes/{id}',
        [SolicitudAcopioController::class, 'show']
    )->name('solicitudes.show');

    // Solicitudes de actualización de centros
    Route::get(
        'actualizaciones',
        [SolicitudActualizacionCentroController::class, 'index']
    )->name('actualizaciones.index');

    Route::get(
        'actualizaciones/{id}',
        [SolicitudActualizacionCentroController::class, 'show']
    )->name('actualizaciones.show');

    // Registros diarios de acopio
    Route::resource(
        'registros',
        RegistroAcopioDiarioController::class
    )->only([
        'index',
        'create',
        'store',
        'show'
    ]);

    // Panel de administración de EcoMap
    Route::get(
        '/admin',
        [\App\Http\Controllers\Admin\DashboardController::class, 'index']
    )->name('admin.dashboard');

    // Centros pendientes de validación
    Route::get(
        '/admin/centros-pendientes',
        [\App\Http\Controllers\Admin\CentroValidacionController::class, 'index']
    )->name('admin.centros.pendientes');

    // Aprobar un centro nuevo
    Route::post(
        '/admin/centros/{id}/aprobar',
        [\App\Http\Controllers\Admin\CentroValidacionController::class, 'aprobar']
    )->name('admin.centros.aprobar');

    // Rechazar un centro nuevo
    Route::post(
        '/admin/centros/{id}/rechazar',
        [\App\Http\Controllers\Admin\CentroValidacionController::class, 'rechazar']
    )->name('admin.centros.rechazar');

    // Solicitudes de actualización de centros
    Route::get(
        '/admin/solicitudes-actualizacion',
        [\App\Http\Controllers\Admin\SolicitudesActualizacionAdminController::class, 'index']
    )->name('admin.actualizaciones.index');

    // Aprobar una solicitud de actualización
    Route::post(
        '/admin/solicitudes-actualizacion/{id}/aprobar',
        [\App\Http\Controllers\Admin\SolicitudesActualizacionAdminController::class, 'aprobar']
    )->name('admin.actualizaciones.aprobar');

    // Rechazar una solicitud de actualización
    Route::post(
        '/admin/solicitudes-actualizacion/{id}/rechazar',
        [\App\Http\Controllers\Admin\SolicitudesActualizacionAdminController::class, 'rechazar']
    )->name('admin.actualizaciones.rechazar');

    // Mostrar formulario para crear administradores
    Route::get(
        '/admin/administradores/crear',
        [\App\Http\Controllers\Admin\AdministradorController::class, 'create']
    )->name('admin.administradores.create');

    // Guardar un nuevo administrador

    // Lista de usuarios registrados
    Route::get(
        '/admin/usuarios',
        [\App\Http\Controllers\Admin\ListadosController::class, 'usuarios']
    )->name('admin.usuarios.index');

    // Dar de baja a los usuarios seleccionados
    Route::post(
        '/admin/usuarios/baja',
        [\App\Http\Controllers\Admin\BajaUsuariosController::class, 'store']
    )->name('admin.usuarios.baja');

    // Lista de administradores registrados
    Route::get(
        '/admin/administradores',
        [\App\Http\Controllers\Admin\ListadosController::class, 'administradores']
    )->name('admin.administradores.index');

    // Dar de baja a los administradores seleccionados
    Route::post(
        '/admin/administradores/baja',
        [\App\Http\Controllers\Admin\BajaAdministradoresController::class, 'store']
    )->name('admin.administradores.baja');


    // Lista de todos los centros de acopio
    Route::get(
        '/admin/centros',
        [\App\Http\Controllers\Admin\ListadosController::class, 'centros']
    )->name('admin.centros.index');

    // Dar de baja centros de acopio seleccionados
    Route::post(
        '/admin/centros/baja',
        [\App\Http\Controllers\Admin\BajaCentrosController::class, 'store']
    )->name('admin.centros.baja');

    Route::post(
        '/admin/administradores',
        [\App\Http\Controllers\Admin\AdministradorController::class, 'store']
    )->name('admin.administradores.store');

});
