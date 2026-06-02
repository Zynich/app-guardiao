<?php

namespace App\Enums;

enum TicketLogAction: string
{
    case CRIADO               = 'criado';
    case STATUS_ALTERADO      = 'status_alterado';
    case PRIORIDADE_ALTERADA  = 'prioridade_alterada';
    case COMENTARIO_ADICIONADO = 'comentario_adicionado';
    case COMENTARIO_REMOVIDO  = 'comentario_removido';
    case MIDIA_ADICIONADA     = 'midia_adicionada';

    public function label(): string
    {
        return match($this) {
            self::CRIADO               => 'Solicitação registrada',
            self::STATUS_ALTERADO      => 'Status atualizado',
            self::PRIORIDADE_ALTERADA  => 'Prioridade alterada',
            self::COMENTARIO_ADICIONADO => 'Comentário adicionado',
            self::COMENTARIO_REMOVIDO  => 'Comentário removido',
            self::MIDIA_ADICIONADA     => 'Foto adicionada',
        };
    }
}