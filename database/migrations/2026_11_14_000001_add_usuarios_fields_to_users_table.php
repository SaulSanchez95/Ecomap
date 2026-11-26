<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Módulo de usuarios - Anabel.
// Revisa con el equipo si ya existen columnas equivalentes antes de ejecutar migraciones.
return new class extends Migration
{
    public function up(): void
    {
        // Los modelos del repositorio ya utilizan estas columnas, pero la migración
        // inicial de users no las define. Se añaden solamente si faltan.
        $faltaRol = ! Schema::hasColumn('users', 'rol');
        $faltaTelefono = ! Schema::hasColumn('users', 'telefono');
        $faltaCreador = ! Schema::hasColumn('users', 'fk_id_usuario_crea');

        if (! $faltaRol && ! $faltaTelefono && ! $faltaCreador) {
            return;
        }

        Schema::table('users', function (Blueprint $table) use ($faltaRol, $faltaTelefono, $faltaCreador): void {
            if ($faltaRol) {
                $table->string('rol', 24)->default('ciudadano');
            }
            if ($faltaTelefono) {
                $table->string('telefono', 20)->nullable();
            }
            if ($faltaCreador) {
                // Se deja nullable; puede estar vacío en las cuentas autorregistradas.
                $table->unsignedBigInteger('fk_id_usuario_crea')->nullable();
            }
        });
    }

    public function down(): void
    {
        // No se borran columnas compartidas al revertir: podrían haber sido
        // creadas previamente por el equipo o estar ya en uso por otros módulos.
    }
};
