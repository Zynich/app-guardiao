<?php

namespace App\Enums;

enum TicketStatus: string
{
    case PENDENTE_TRIAGEM = 'pendente_triagem'; // INÍCIO DA TRIAGEM
    case EM_ANDAMENTO = 'em_andamento'; // TICKET EM ANDAMENTO   
    case RESOLVIDO = 'resolvido'; // TICKET RESOLVIDO   
    case REJEITADO = 'rejeitado'; // REJEITADO (MEME, TROTE, ETC)    
    case CANCELADO = 'cancelado'; // A PREFEITURA OU CIDADÃO CANCELAM O CHAMADO   
    case DUPLICADO = 'duplicado'; // O MESMO CIDADÃO ABRIU O MESMO TICKET VÁRIAS VEZES (SE IDENTIFICADO)
}