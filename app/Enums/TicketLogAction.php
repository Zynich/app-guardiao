<?php

namespace App\Enums;

enum TicketLogAction: string
{
    case CRIADO = 'criado'; 
    case STATUS_ALTERADO = 'status_alterado';
    case PRIORIDADE_ALTERADA = 'prioridade_alterada';
    case COMENTARIO_ADICIONADO = 'comentario_adicionado';
    case COMENTARIO_REMOVIDO = 'comentario_removido'; // Quando o agente apaga uma mensagem
    case MIDIA_ADICIONADA = 'midia_adicionada'; // Se o cara anexar foto nova depois
}