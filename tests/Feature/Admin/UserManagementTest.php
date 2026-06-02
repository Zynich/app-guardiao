<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $despachante;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin       = User::factory()->admin()->create();
        $this->despachante = User::factory()->despachante()->create();
    }

    public function test_admin_pode_listar_funcionarios(): void
    {
        $this->actingAs($this->admin)
             ->get('/admin/users')
             ->assertOk();
    }

    public function test_despachante_nao_pode_acessar_gestao_de_usuarios(): void
    {
        $this->actingAs($this->despachante)
             ->get('/admin/users')
             ->assertForbidden();
    }

    public function test_admin_pode_criar_novo_funcionario(): void
    {
        $this->actingAs($this->admin)
             ->post('/admin/users', [
                 'name'      => 'Carlos Agente',
                 'email'     => 'carlos@prefeitura.gov.br',
                 'password'  => 'senha123',
                 'role'      => 'agente_campo',
                 'is_active' => '1',
             ])
             ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'carlos@prefeitura.gov.br',
            'role'  => 'agente_campo',
        ]);
    }

    public function test_email_duplicado_e_rejeitado(): void
    {
        User::factory()->create(['email' => 'existente@test.com']);

        $this->actingAs($this->admin)
             ->post('/admin/users', [
                 'name'     => 'Outro Usuário',
                 'email'    => 'existente@test.com',
                 'password' => 'senha123',
                 'role'     => 'despachante',
             ])
             ->assertSessionHasErrors('email');
    }

    public function test_admin_pode_desativar_conta_de_funcionario(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->actingAs($this->admin)
             ->patch("/admin/users/{$user->id}/toggle-active")
             ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id'        => $user->id,
            'is_active' => false,
        ]);
    }

    public function test_admin_nao_pode_desativar_a_propria_conta(): void
    {
        $this->actingAs($this->admin)
             ->patch("/admin/users/{$this->admin->id}/toggle-active")
             ->assertRedirect();

        // Conta do admin deve permanecer ativa
        $this->assertDatabaseHas('users', [
            'id'        => $this->admin->id,
            'is_active' => true,
        ]);
    }

    public function test_usuario_inativo_e_deslogado_ao_tentar_acessar(): void
    {
        $inativo = User::factory()->inactive()->create();

        $this->actingAs($inativo)
             ->get('/admin/dashboard')
             ->assertRedirect('/login');
    }

    public function test_admin_pode_excluir_funcionario(): void
    {
        $user = User::factory()->create();

        $this->actingAs($this->admin)
             ->delete("/admin/users/{$user->id}")
             ->assertRedirect(route('admin.users.index'));

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }
}
