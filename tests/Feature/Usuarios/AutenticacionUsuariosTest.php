<?php

namespace Tests\Feature\Usuarios;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Módulo de usuarios - Anabel.
class AutenticacionUsuariosTest extends TestCase
{
    use RefreshDatabase;

    public function test_ciudadano_puede_registrarse(): void
    {
        $this->post('/register', [
            'name' => 'Ana Ciudadana',
            'email' => 'ciudadana@example.test',
            'telefono' => '3121234567',
            'rol' => 'ciudadano',
            'password' => 'Contraseña123!',
            'password_confirmation' => 'Contraseña123!',
        ])->assertRedirect('/mi-espacio');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'ciudadana@example.test', 'rol' => 'ciudadano',
        ]);
    }

    public function test_no_se_puede_autorregistrar_administrador(): void
    {
        $this->post('/register', [
            'name' => 'Admin falso',
            'email' => 'admin-falso@example.test',
            'rol' => 'administrador',
            'password' => 'Contraseña123!',
            'password_confirmation' => 'Contraseña123!',
        ])->assertSessionHasErrors('rol');
        $this->assertDatabaseMissing('users', ['email' => 'admin-falso@example.test']);
    }

    public function test_portal_encargado_exige_rol(): void
    {
        $ciudadano = User::factory()->create(['rol' => 'ciudadano']);
        $this->actingAs($ciudadano)->get('/encargado')->assertForbidden();
        $this->actingAs($ciudadano)->get('/ciudadano')->assertOk();
    }

    public function test_usuario_no_autenticado_se_redirige_a_iniciar_sesion(): void
    {
        $this->get('/perfil')->assertRedirect('/login');
        $this->get('/encargado')->assertRedirect('/login');
    }

    public function test_actualizar_perfil_no_permite_cambiar_rol(): void
    {
        $usuario = User::factory()->create(['rol' => 'ciudadano']);
        $this->actingAs($usuario)->patch('/perfil', [
            'name' => 'Ana Actualizada',
            'email' => 'ana-actualizada@example.test',
            'telefono' => '3127654321',
            'rol' => 'administrador',
        ])->assertRedirect('/perfil');

        $this->assertDatabaseHas('users', [
            'id' => $usuario->id, 'name' => 'Ana Actualizada',
            'rol' => 'ciudadano',
        ]);
    }
}
