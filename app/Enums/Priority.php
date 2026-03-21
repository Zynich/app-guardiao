<?php

namespace App\Enums;

enum Priority: string
{
    case BAIXA = 'baixa';
    case MEDIA = 'media';
    case ALTA = 'alta';
    case URGENTE = 'urgente';
}