<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],

            'telefono' => [
                'nullable',
                'string',
                'max:20',
            ],

            'rol' => [
                'required',
                'string',
                Rule::in(['ciudadano', 'dueno']),
            ],

            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'telefono' => $input['telefono'] ?? null,
            'rol' => $input['rol'],
            'password' => Hash::make($input['password']),
            'fk_id_usuario_crea' => null,
        ]);
    }
}
