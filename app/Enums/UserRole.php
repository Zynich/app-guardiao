<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN      = 'admin';
    case DESPACHANTE = 'despachante';
    case AGENTE     = 'agente_campo';

    public function label(): string
    {
        return match($this) {
            self::ADMIN      => 'Administrador',
            self::DESPACHANTE => 'Despachante',
            self::AGENTE     => 'Agente de Campo',
        };
    }

    public function badgeClasses(): string
    {
        return match($this) {
            self::ADMIN      => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
            self::DESPACHANTE => 'bg-blue-500/10 text-blue-400 border-blue-500/30',
            self::AGENTE     => 'bg-zinc-500/10 text-zinc-400 border-zinc-500/30',
        };
    }
}