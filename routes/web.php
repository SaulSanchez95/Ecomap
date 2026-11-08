<?php

use Illuminate\Support\Facades\Route;

// Página principal de EcoMap
Route::view('/', 'ecomap.inicio')->name('inicio');

// Portal del ciudadano
Route::view('/ciudadano', 'ecomap.ciudadano')->name('ciudadano');

// Panel del encargado
Route::view('/encargado', 'ecomap.encargado')->name('encargado');