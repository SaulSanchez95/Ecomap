<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateFirstAdmin extends Command
{
    protected $signature = 'ecomap:create-first-admin';

    protected $description = 'Crea la primera cuenta de administrador de EcoMap';

    public function handle(): int
    {
        // Comprobar que todavía no exista un administrador.
        if (User::where('rol', 'administrador')->exists()) {
            $this->error('Ya existe una cuenta de administrador.');
            return self::FAILURE;
        }

        $this->info('CREACIÓN DEL PRIMER ADMINISTRADOR DE ECOMAP');
        $this->newLine();

        // Solicitar los datos.
        $nombre = $this->ask('Nombre del administrador');
        $correo = $this->ask('Correo electrónico');

        // Validar nombre y correo.
        $validator = Validator::make(
            [
                'name' => $nombre,
                'email' => $correo,
            ],
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        // Solicitar contraseña sin mostrarla.
        $password = $this->secret('Escribe la contraseña');
        $passwordConfirmation = $this->secret('Confirma la contraseña');

        if (empty($password) || $password !== $passwordConfirmation) {
            $this->error('Las contraseñas están vacías o no coinciden.');
            return self::FAILURE;
        }

        if (strlen($password) < 12) {
            $this->error('La contraseña debe tener al menos 12 caracteres.');
            return self::FAILURE;
        }

        // Crear la cuenta administrativa.
        User::create([
            'name' => $nombre,
            'email' => $correo,
            'password' => Hash::make($password),
            'rol' => 'administrador',
            'telefono' => null,
            'fk_id_usuario_crea' => null,
        ]);

        $this->newLine();
        $this->info('Administrador creado correctamente.');
        $this->line('Correo: ' . $correo);

        return self::SUCCESS;
    }
}