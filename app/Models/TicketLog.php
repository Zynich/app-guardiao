<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketLog extends Model
{
    use SoftDeletes; 

    protected $fillable = [
        'ticket_id', 'user_id', 'action', 'old_value', 'new_value', 'comment', 'is_public'
    ];

    protected $casts = [
        'action' => \App\Enums\TicketLogAction::class,
        'is_public' => 'boolean',
    ];

    // --- RELACIONAMENTOS ---

    // Um log pertence a um ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // Um log foi feito por um usuário (pode ser nulo se for automático)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function logCreated($ticketId, $userId = null)
    {
        return self::create([
            'ticket_id' => $ticketId,
            'user_id' => $userId, // Nasce null se foi o cidadão fantasma que abriu pelo site
            'action' => \App\Enums\TicketLogAction::CRIADO,
            'is_public' => true, // O cidadão precisa ver que a quest começou
        ]);
    }

    // O Buff/Nerf de Prioridade
    public static function logPriority($ticketId, $userId, $oldPriority, $newPriority)
    {
        return self::create([
            'ticket_id' => $ticketId,
            'user_id' => $userId,
            'action' => \App\Enums\TicketLogAction::PRIORIDADE_ALTERADA,
            'old_value' => $oldPriority,
            'new_value' => $newPriority,
            'is_public' => false, 
        ]);
    }

    // Método para criar um log de status
    public static function logStatus($ticketId, $userId, $oldStatus, $newStatus)
    {
        return self::create([
            'ticket_id' => $ticketId,
            'user_id' => $userId,
            'action' => \App\Enums\TicketLogAction::STATUS_ALTERADO,
            'old_value' => $oldStatus,
            'new_value' => $newStatus,
            'is_public' => true, // Status é sempre público
        ]);
    }

    // Método para criar um comentário (público ou privado)
    public static function logComment($ticketId, $userId, $comment, $isPublic = false)
    {
        return self::create([
            'ticket_id' => $ticketId,
            'user_id' => $userId,
            'action' => \App\Enums\TicketLogAction::COMENTARIO_ADICIONADO,
            'comment' => $comment,
            'is_public' => $isPublic,
        ]);
    }

    // O Rastro da Lixeira (Quando o agente apaga um comentário)
    public static function logCommentRemoved($ticketId, $userId)
    {
        return self::create([
            'ticket_id' => $ticketId,
            'user_id' => $userId,
            'action' => \App\Enums\TicketLogAction::COMENTARIO_REMOVIDO,
            'is_public' => false, 
        ]);
    }

    public static function logMediaAdded($ticketId, $userId = null, $fileName = null)
    {
        return self::create([
            'ticket_id' => $ticketId,
            'user_id' => $userId, 
            'action' => \App\Enums\TicketLogAction::MIDIA_ADICIONADA,
            'new_value' => $fileName, 
            'is_public' => true, 
        ]);
    }
}
