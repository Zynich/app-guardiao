<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketCommentMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Ticket $ticket,
        public readonly string $comment,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Protocolo {$this->ticket->protocol} — Nova atualização",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-comment',
        );
    }
}
