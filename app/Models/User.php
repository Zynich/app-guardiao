<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'cpf', 'phone', 'badge_number',
        'is_active', 'last_login_at',
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
            'last_login_at'     => 'datetime',
            'is_active'         => 'boolean',
            'password'          => 'hashed',
            'role'              => \App\Enums\UserRole::class,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === \App\Enums\UserRole::ADMIN;
    }

    public function isDespachante(): bool
    {
        return $this->role === \App\Enums\UserRole::DESPACHANTE;
    }

    public function isAgente(): bool
    {
        return $this->role === \App\Enums\UserRole::AGENTE;
    }

    public function hasRole(\App\Enums\UserRole ...$roles): bool
    {
        return in_array($this->role, $roles, true);
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
