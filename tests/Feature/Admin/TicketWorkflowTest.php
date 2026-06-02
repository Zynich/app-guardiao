<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $despachante;
    private User $agente;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin       = User::factory()->admin()->create();
        $this->despachante = User::factory()->despachante()->create();
        $this->agente      = User::factory()->agente()->create();
    }

    // ── Acesso ──────────────────────────────────────────────────────────────

    public function test_acesso_sem_login_redireciona_para_login(): void
    {
        $this->get('/admin/tickets')->assertRedirect('/login');
    }

    public function test_admin_pode_listar_todos_os_tickets(): void
    {
        Ticket::factory()->count(5)->create();

        $this->actingAs($this->admin)
             ->get('/admin/tickets')
             ->assertOk();
    }

    public function test_agente_ve_apenas_tickets_atribuidos_a_ele(): void
    {
        $atribuido    = Ticket::factory()->create(['user_id' => $this->agente->id]);
        $naoAtribuido = Ticket::factory()->create(['user_id' => null]);

        $response = $this->actingAs($this->agente)
                         ->get('/admin/tickets');

        $response->assertOk()
                 ->assertSee($atribuido->protocol)
                 ->assertDontSee($naoAtribuido->protocol);
    }

    public function test_usuario_inativo_nao_consegue_acessar_painel(): void
    {
        $inativo = User::factory()->inactive()->create();

        $this->actingAs($inativo)
             ->get('/admin/tickets')
             ->assertRedirect('/login');
    }

    // ── Criação ──────────────────────────────────────────────────────────────

    public function test_despachante_pode_criar_ticket(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->despachante)
                         ->post('/admin/tickets', [
                             'category_id'   => $category->id,
                             'description'   => 'Poste apagado na esquina.',
                             'address'       => 'Rua XV de Novembro, 500',
                             'citizen_name'  => 'Maria Souza',
                             'citizen_email' => 'maria@example.com',
                         ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tickets', [
            'citizen_name' => 'Maria Souza',
            'created_by_id' => $this->despachante->id,
        ]);
    }

    public function test_agente_nao_pode_criar_ticket(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->agente)
             ->post('/admin/tickets', [
                 'category_id'   => $category->id,
                 'description'   => 'Teste',
                 'address'       => 'Rua X, 1',
                 'citizen_name'  => 'José',
                 'citizen_email' => 'jose@example.com',
             ])
             ->assertForbidden();
    }

    // ── Workflow de Status ───────────────────────────────────────────────────

    public function test_transicao_valida_pendente_para_em_andamento(): void
    {
        $ticket = Ticket::factory()->create(['status' => 'pendente_triagem']);

        $this->actingAs($this->admin)
             ->patch("/admin/tickets/{$ticket->id}/status", ['status' => 'em_andamento'])
             ->assertRedirect();

        $this->assertDatabaseHas('tickets', [
            'id'     => $ticket->id,
            'status' => 'em_andamento',
        ]);
    }

    public function test_transicao_valida_em_andamento_para_resolvido(): void
    {
        $ticket = Ticket::factory()->emAndamento()->create();

        $this->actingAs($this->admin)
             ->patch("/admin/tickets/{$ticket->id}/status", ['status' => 'resolvido'])
             ->assertRedirect();

        $this->assertDatabaseHas('tickets', [
            'id'     => $ticket->id,
            'status' => 'resolvido',
        ]);
    }

    public function test_transicao_invalida_e_rejeitada(): void
    {
        // Não se pode ir de pendente_triagem direto para resolvido
        $ticket = Ticket::factory()->create(['status' => 'pendente_triagem']);

        $this->actingAs($this->admin)
             ->patch("/admin/tickets/{$ticket->id}/status", ['status' => 'resolvido'])
             ->assertRedirect();

        // Status NÃO deve ter mudado
        $this->assertDatabaseHas('tickets', [
            'id'     => $ticket->id,
            'status' => 'pendente_triagem',
        ]);
    }

    public function test_mudanca_de_status_gera_log(): void
    {
        $ticket = Ticket::factory()->create(['status' => 'pendente_triagem']);

        $this->actingAs($this->admin)
             ->patch("/admin/tickets/{$ticket->id}/status", ['status' => 'em_andamento']);

        $this->assertDatabaseHas('ticket_logs', [
            'ticket_id' => $ticket->id,
            'action'    => 'status_alterado',
            'old_value' => 'pendente_triagem',
            'new_value' => 'em_andamento',
            'user_id'   => $this->admin->id,
        ]);
    }

    // ── Comentários ──────────────────────────────────────────────────────────

    public function test_despachante_pode_adicionar_comentario(): void
    {
        $ticket = Ticket::factory()->create();

        $this->actingAs($this->despachante)
             ->post("/admin/tickets/{$ticket->id}/comments", [
                 'comment'   => 'Equipe enviada ao local.',
                 'is_public' => '1',
             ])
             ->assertRedirect();

        $this->assertDatabaseHas('ticket_logs', [
            'ticket_id' => $ticket->id,
            'comment'   => 'Equipe enviada ao local.',
            'is_public' => true,
        ]);
    }

    // ── Atribuição ───────────────────────────────────────────────────────────

    public function test_admin_pode_atribuir_agente_ao_ticket(): void
    {
        $ticket = Ticket::factory()->create(['user_id' => null]);

        $this->actingAs($this->admin)
             ->patch("/admin/tickets/{$ticket->id}/assign", ['user_id' => $this->agente->id])
             ->assertRedirect();

        $this->assertDatabaseHas('tickets', [
            'id'      => $ticket->id,
            'user_id' => $this->agente->id,
        ]);
    }
}
