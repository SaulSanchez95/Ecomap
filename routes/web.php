
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

});
