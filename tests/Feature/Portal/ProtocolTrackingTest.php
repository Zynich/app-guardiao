<?php

namespace Tests\Feature\Portal;

use App\Enums\TicketLogAction;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProtocolTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_cidadao_pode_consultar_protocolo_existente(): void
    {
        $ticket = Ticket::factory()->create();

        $response = $this->get("/protocolo?protocol={$ticket->protocol}");

        $response->assertOk()
                 ->assertSee($ticket->protocol);
    }

    public function test_protocolo_inexistente_exibe_mensagem_de_nao_encontrado(): void
    {
        $response = $this->get('/protocolo?protocol=2099-XXXXXX');

        $response->assertOk()
                 ->assertSee('não encontrado');
    }

    public function test_acesso_sem_protocolo_exibe_formulario_de_busca(): void
    {
        $response = $this->get('/protocolo');

        $response->assertOk();
    }

    public function test_apenas_logs_publicos_sao_exibidos(): void
    {
        $ticket = Ticket::factory()->create();

        TicketLog::create([
            'ticket_id' => $ticket->id,
            'action'    => TicketLogAction::COMENTARIO_ADICIONADO,
            'comment'   => 'Comentário visível ao cidadão',
            'is_public' => true,
        ]);

        TicketLog::create([
            'ticket_id' => $ticket->id,
            'action'    => TicketLogAction::COMENTARIO_ADICIONADO,
            'comment'   => 'Nota interna confidencial',
            'is_public' => false,
        ]);

        $response = $this->get("/protocolo?protocol={$ticket->protocol}");

        $response->assertOk()
                 ->assertSee('Comentário visível ao cidadão')
                 ->assertDontSee('Nota interna confidencial');
    }

    public function test_status_do_ticket_e_exibido_corretamente(): void
    {
        $ticket = Ticket::factory()->emAndamento()->create();

        $response = $this->get("/protocolo?protocol={$ticket->protocol}");

        $response->assertOk()
                 ->assertSee('Em Andamento');
    }
}
