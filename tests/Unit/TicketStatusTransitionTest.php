<?php

namespace Tests\Unit;

use App\Enums\TicketStatus;
use PHPUnit\Framework\TestCase;

class TicketStatusTransitionTest extends TestCase
{
    /** Retorna as transições válidas, copiando a lógica do TicketController */
    private function getAllowedTransitions(TicketStatus $current): array
    {
        return match($current) {
            TicketStatus::PENDENTE_TRIAGEM => [
                TicketStatus::EM_ANDAMENTO,
                TicketStatus::REJEITADO,
                TicketStatus::DUPLICADO,
            ],
            TicketStatus::EM_ANDAMENTO => [
                TicketStatus::RESOLVIDO,
                TicketStatus::CANCELADO,
            ],
            default => [],
        };
    }

    public function test_pendente_triagem_pode_ir_para_em_andamento(): void
    {
        $allowed = $this->getAllowedTransitions(TicketStatus::PENDENTE_TRIAGEM);
        $this->assertContains(TicketStatus::EM_ANDAMENTO, $allowed);
    }

    public function test_pendente_triagem_pode_ser_rejeitado(): void
    {
        $allowed = $this->getAllowedTransitions(TicketStatus::PENDENTE_TRIAGEM);
        $this->assertContains(TicketStatus::REJEITADO, $allowed);
    }

    public function test_pendente_triagem_pode_ser_marcado_como_duplicado(): void
    {
        $allowed = $this->getAllowedTransitions(TicketStatus::PENDENTE_TRIAGEM);
        $this->assertContains(TicketStatus::DUPLICADO, $allowed);
    }

    public function test_pendente_triagem_nao_pode_ir_diretamente_para_resolvido(): void
    {
        $allowed = $this->getAllowedTransitions(TicketStatus::PENDENTE_TRIAGEM);
        $this->assertNotContains(TicketStatus::RESOLVIDO, $allowed);
    }

    public function test_em_andamento_pode_ser_resolvido(): void
    {
        $allowed = $this->getAllowedTransitions(TicketStatus::EM_ANDAMENTO);
        $this->assertContains(TicketStatus::RESOLVIDO, $allowed);
    }

    public function test_em_andamento_pode_ser_cancelado(): void
    {
        $allowed = $this->getAllowedTransitions(TicketStatus::EM_ANDAMENTO);
        $this->assertContains(TicketStatus::CANCELADO, $allowed);
    }

    public function test_status_terminais_nao_tem_transicoes(): void
    {
        $terminais = [
            TicketStatus::RESOLVIDO,
            TicketStatus::REJEITADO,
            TicketStatus::CANCELADO,
            TicketStatus::DUPLICADO,
        ];

        foreach ($terminais as $status) {
            $allowed = $this->getAllowedTransitions($status);
            $this->assertEmpty(
                $allowed,
                "Status {$status->label()} deveria ser terminal mas tem transições."
            );
        }
    }

    public function test_todos_os_status_tem_label(): void
    {
        foreach (TicketStatus::cases() as $status) {
            $this->assertNotEmpty($status->label(), "Status {$status->value} sem label.");
        }
    }

    public function test_todos_os_status_tem_badge_classes(): void
    {
        foreach (TicketStatus::cases() as $status) {
            $this->assertNotEmpty($status->badgeClasses(), "Status {$status->value} sem badgeClasses.");
        }
    }
}
