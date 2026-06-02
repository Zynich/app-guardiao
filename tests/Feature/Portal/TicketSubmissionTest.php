<?php

namespace Tests\Feature\Portal;

use App\Mail\TicketProtocolMail;
use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TicketSubmissionTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = Category::factory()->create();
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'category_id'   => $this->category->id,
            'description'   => 'Buraco enorme na calçada em frente ao mercado.',
            'address'       => 'Rua das Flores, 123, Canoas',
            'citizen_name'  => 'João da Silva',
            'citizen_email' => 'joao@example.com',
        ], $overrides);
    }

    public function test_cidadao_pode_registrar_ocorrencia_com_dados_validos(): void
    {
        Mail::fake();

        $response = $this->postJson('/ocorrencias', $this->validPayload());

        $response->assertCreated()
                 ->assertJsonStructure(['protocol']);

        $protocol = $response->json('protocol');
        $this->assertMatchesRegularExpression('/^\d{4}-[A-Z0-9]{6}$/', $protocol);

        $this->assertDatabaseHas('tickets', [
            'protocol'      => $protocol,
            'citizen_name'  => 'João da Silva',
            'citizen_email' => 'joao@example.com',
            'status'        => 'pendente_triagem',
        ]);
    }

    public function test_protocolo_gerado_e_unico(): void
    {
        Mail::fake();

        $r1 = $this->postJson('/ocorrencias', $this->validPayload());
        $r2 = $this->postJson('/ocorrencias', $this->validPayload());

        $r1->assertCreated();
        $r2->assertCreated();

        $this->assertNotEquals($r1->json('protocol'), $r2->json('protocol'));
    }

    public function test_email_de_confirmacao_e_enviado_ao_cidadao(): void
    {
        Mail::fake();

        $this->postJson('/ocorrencias', $this->validPayload())
             ->assertCreated();

        Mail::assertSent(TicketProtocolMail::class, function ($mail) {
            return $mail->hasTo('joao@example.com');
        });
    }

    public function test_retorna_422_quando_campos_obrigatorios_faltam(): void
    {
        $response = $this->postJson('/ocorrencias', []);

        $response->assertUnprocessable()
                 ->assertJsonValidationErrors(['category_id', 'description', 'address', 'citizen_name', 'citizen_email']);
    }

    public function test_retorna_422_com_email_invalido(): void
    {
        $response = $this->postJson('/ocorrencias', $this->validPayload([
            'citizen_email' => 'nao-e-um-email',
        ]));

        $response->assertUnprocessable()
                 ->assertJsonValidationErrors(['citizen_email']);
    }

    public function test_retorna_422_com_categoria_inexistente(): void
    {
        $response = $this->postJson('/ocorrencias', $this->validPayload([
            'category_id' => 99999,
        ]));

        $response->assertUnprocessable()
                 ->assertJsonValidationErrors(['category_id']);
    }

    public function test_descricao_longa_demais_e_rejeitada(): void
    {
        $response = $this->postJson('/ocorrencias', $this->validPayload([
            'description' => str_repeat('a', 1001),
        ]));

        $response->assertUnprocessable()
                 ->assertJsonValidationErrors(['description']);
    }

    public function test_upload_de_foto_e_salvo(): void
    {
        Mail::fake();
        Storage::fake('public');

        $response = $this->postJson('/ocorrencias', array_merge(
            $this->validPayload(),
            ['photos' => [UploadedFile::fake()->image('foto.jpg', 800, 600)]]
        ));

        $response->assertCreated();

        $ticket = Ticket::where('protocol', $response->json('protocol'))->firstOrFail();
        $this->assertCount(1, $ticket->media);
    }

    public function test_rate_limiting_bloqueia_apos_3_tentativas(): void
    {
        Mail::fake();

        for ($i = 0; $i < 3; $i++) {
            $this->postJson('/ocorrencias', $this->validPayload())->assertCreated();
        }

        $this->postJson('/ocorrencias', $this->validPayload())
             ->assertStatus(429);
    }

    public function test_ticket_nasce_com_status_pendente_triagem(): void
    {
        Mail::fake();

        $response = $this->postJson('/ocorrencias', $this->validPayload())
                         ->assertCreated();

        $ticket = Ticket::where('protocol', $response->json('protocol'))->firstOrFail();
        $this->assertEquals('pendente_triagem', $ticket->status->value);
    }

    public function test_log_de_criacao_e_registrado(): void
    {
        Mail::fake();

        $response = $this->postJson('/ocorrencias', $this->validPayload())
                         ->assertCreated();

        $ticket = Ticket::where('protocol', $response->json('protocol'))->firstOrFail();
        $this->assertDatabaseHas('ticket_logs', [
            'ticket_id' => $ticket->id,
            'action'    => 'criado',
            'is_public' => true,
        ]);
    }
}
