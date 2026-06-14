<?php

namespace App\Mail;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Ticket $ticket,
        public readonly TicketStatus $newStatus,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Protocolo {$this->ticket->protocol} — Status atualizado",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-status',
        );
    }
}
