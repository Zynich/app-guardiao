<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'cpf',
        'phone',
        'badge_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => \App\Enums\UserRole::class,
        ];
    }

    // --- RELACIONAMENTOS ---

    /**
     * Chamados atribuídos a este usuário (agente).
     */
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    /**
     * Chamados criados por este usuário (agente).
     */
    public function createdTickets()
    {
        return $this->hasMany(Ticket::class, 'created_by_id');
    }

    /**
     * Logs de ações realizadas por este usuário.
     */
    public function logs()
    {
        return $this->hasMany(TicketLog::class);
    }
}
