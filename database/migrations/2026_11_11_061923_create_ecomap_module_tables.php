
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. USUARIOS Y ROLES
        |--------------------------------------------------------------------------
        */

        Schema::table('users', function (Blueprint $table) {
            $table->string('rol', 20)->default('ciudadano');
            $table->string('telefono', 20)->nullable();

            // Administrador que creó esta cuenta.
            $table->foreignId('fk_id_usuario_crea')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });

        /*
        |--------------------------------------------------------------------------
        | 2. MATERIALES
        |--------------------------------------------------------------------------
        */

        Schema::create('materiales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 80)->unique();
            $table->text('descripcion')->nullable();
            $table->string('unidad_medida', 20)->default('kg');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 3. CENTROS DE ACOPIO
        |--------------------------------------------------------------------------
        */

        Schema::create('centros_acopio', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fk_id_usuario')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('nombre', 120);
            $table->text('descripcion')->nullable();
            $table->string('direccion', 200);
            $table->string('colonia', 100)->nullable();
            $table->string('municipio', 100);
            $table->string('estado', 100)->default('Colima');
            $table->string('codigo_postal', 10)->nullable();

            $table->string('telefono', 20)->nullable();
            $table->string('correo', 120)->nullable();
            $table->text('horario')->nullable();

            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();

            $table->string('estado_validacion', 20)->default('pendiente');
            $table->boolean('activo')->default(true);

            $table->foreignId('fk_id_usuario_valida')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('fecha_validacion')->nullable();
            $table->text('observaciones_admin')->nullable();
            $table->timestamp('fecha_baja')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        /*
        |--------------------------------------------------------------------------
        | 4. MATERIALES ACEPTADOS POR CENTRO
        |--------------------------------------------------------------------------
        */

        Schema::create('centro_acopio_material', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fk_id_centro')
                ->constrained('centros_acopio')
                ->cascadeOnDelete();

            $table->foreignId('fk_id_material')
                ->constrained('materiales')
                ->restrictOnDelete();

            $table->unique(
                ['fk_id_centro', 'fk_id_material'],
                'centro_material_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 5. CAMPAÑAS
        |--------------------------------------------------------------------------
        */

        Schema::create('campanias', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fk_id_centro')
                ->constrained('centros_acopio')
                ->restrictOnDelete();

            $table->string('titulo', 120);
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->string('estado', 20)->default('borrador');

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 6. MATERIALES DE CADA CAMPAÑA
        |--------------------------------------------------------------------------
        */

        Schema::create('campania_material', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fk_id_campania')
                ->constrained('campanias')
                ->cascadeOnDelete();

            $table->foreignId('fk_id_material')
                ->constrained('materiales')
                ->restrictOnDelete();

            $table->decimal('cantidad_solicitada', 12, 3)->nullable();

            $table->unique(
                ['fk_id_campania', 'fk_id_material'],
                'campania_material_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 7. SOLICITUDES INFORMATIVAS DE ACOPIO
        |--------------------------------------------------------------------------
        */

        Schema::create('solicitudes_acopio', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fk_id_centro')
                ->constrained('centros_acopio')
                ->restrictOnDelete();

            $table->foreignId('fk_id_usuario')
                ->constrained('users')
                ->restrictOnDelete();

            $table->date('fecha_entrega_estimada')->nullable();
            $table->text('comentarios')->nullable();

            // Aviso informativo, no requiere aprobación del dueño.
            $table->string('estado', 20)->default('notificada');

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 8. MATERIALES DE LAS SOLICITUDES DE ACOPIO
        |--------------------------------------------------------------------------
        */

        Schema::create('solicitud_acopio_material', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fk_id_solicitud')
                ->constrained('solicitudes_acopio')
                ->cascadeOnDelete();

            $table->foreignId('fk_id_material')
                ->constrained('materiales')
                ->restrictOnDelete();

            $table->decimal('cantidad_estimada', 12, 3);

            $table->unique(
                ['fk_id_solicitud', 'fk_id_material'],
                'solicitud_material_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 9. SOLICITUDES DE ACTUALIZACIÓN DE CENTROS
        |--------------------------------------------------------------------------
        */

        Schema::create('solicitudes_actualizacion_centro', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fk_id_centro')
                ->constrained('centros_acopio')
                ->restrictOnDelete();

            $table->foreignId('fk_id_usuario_solicita')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('fk_id_usuario_revisa')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Datos propuestos sin modificar el centro original.
            $table->json('datos_propuestos');

            $table->string('estado', 20)->default('pendiente');
            $table->text('observaciones_admin')->nullable();
            $table->timestamp('fecha_revision')->nullable();

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 10. MATERIALES PROPUESTOS EN UNA ACTUALIZACIÓN
        |--------------------------------------------------------------------------
        */

        Schema::create('solicitud_actualizacion_material', function (Blueprint $table) {
            $table->id();

            // Se declara por separado para asignar un nombre corto a la FK.
            $table->unsignedBigInteger('fk_id_solicitud_actualizacion');

            $table->foreign(
                'fk_id_solicitud_actualizacion',
                'sam_solicitud_fk'
            )
                ->references('id')
                ->on('solicitudes_actualizacion_centro')
                ->cascadeOnDelete();

            $table->foreignId('fk_id_material')
                ->constrained('materiales')
                ->restrictOnDelete();

            $table->unique(
                ['fk_id_solicitud_actualizacion', 'fk_id_material'],
                'actualizacion_material_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 11. REGISTROS REALES DE ACOPIO
        |--------------------------------------------------------------------------
        */

        Schema::create('registros_acopio_diario', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fk_id_centro')
                ->constrained('centros_acopio')
                ->restrictOnDelete();

            $table->foreignId('fk_id_material')
                ->constrained('materiales')
                ->restrictOnDelete();

            $table->foreignId('fk_id_usuario_registra')
                ->constrained('users')
                ->restrictOnDelete();

            $table->decimal('cantidad_recibida', 12, 3);
            $table->date('fecha');
            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->index(
                ['fk_id_centro', 'fecha'],
                'registro_centro_fecha_index'
            );
        });
    }

    public function down(): void
    {
        // Eliminar primero las tablas dependientes.
        Schema::dropIfExists('registros_acopio_diario');
        Schema::dropIfExists('solicitud_actualizacion_material');
        Schema::dropIfExists('solicitudes_actualizacion_centro');
        Schema::dropIfExists('solicitud_acopio_material');
        Schema::dropIfExists('solicitudes_acopio');
        Schema::dropIfExists('campania_material');
        Schema::dropIfExists('campanias');
        Schema::dropIfExists('centro_acopio_material');
        Schema::dropIfExists('centros_acopio');
        Schema::dropIfExists('materiales');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['fk_id_usuario_crea']);

            $table->dropColumn([
                'rol',
                'telefono',
                'fk_id_usuario_crea',
            ]);
        });
    }
};
