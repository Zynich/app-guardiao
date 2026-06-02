<?php

namespace App\Enums;

enum Priority: string
{
    case BAIXA   = 'baixa';
    case MEDIA   = 'media';
    case ALTA    = 'alta';
    case URGENTE = 'urgente';

    public function label(): string
    {
        return match($this) {
            self::BAIXA   => 'Baixa',
            self::MEDIA   => 'Média',
            self::ALTA    => 'Alta',
            self::URGENTE => 'Urgente',
        };
    }

    public function badgeClasses(): string
    {
        return match($this) {
            self::BAIXA   => 'bg-zinc-500/10 text-zinc-400 border-zinc-500/30',
            self::MEDIA   => 'bg-blue-500/10 text-blue-400 border-blue-500/30',
            self::ALTA    => 'bg-orange-500/10 text-orange-400 border-orange-500/30',
            self::URGENTE => 'bg-red-500/10 text-red-400 border-red-500/30',
        };
    }
}