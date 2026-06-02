<?php

namespace App\Enums;

enum TicketStatus: string
{
    case PENDENTE_TRIAGEM = 'pendente_triagem';
    case EM_ANDAMENTO     = 'em_andamento';
    case RESOLVIDO        = 'resolvido';
    case REJEITADO        = 'rejeitado';
    case CANCELADO        = 'cancelado';
    case DUPLICADO        = 'duplicado';

    public function label(): string
    {
        return match($this) {
            self::PENDENTE_TRIAGEM => 'Aguardando Análise',
            self::EM_ANDAMENTO     => 'Em Andamento',
            self::RESOLVIDO        => 'Resolvido',
            self::REJEITADO        => 'Rejeitado',
            self::CANCELADO        => 'Cancelado',
            self::DUPLICADO        => 'Duplicado',
        };
    }

    /** Retorna classes Tailwind para o badge de status. */
    public function badgeClasses(): string
    {
        return match($this) {
            self::PENDENTE_TRIAGEM => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/30',
            self::EM_ANDAMENTO     => 'bg-blue-500/10 text-blue-400 border-blue-500/30',
            self::RESOLVIDO        => 'bg-green-500/10 text-green-400 border-green-500/30',
            self::REJEITADO        => 'bg-red-500/10 text-red-400 border-red-500/30',
            self::CANCELADO        => 'bg-zinc-500/10 text-zinc-400 border-zinc-500/30',
            self::DUPLICADO        => 'bg-orange-500/10 text-orange-400 border-orange-500/30',
        };
    }
}