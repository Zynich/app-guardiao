<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes; 

    protected $fillable = [
        'protocol', 'citizen_name', 'citizen_email', 'citizen_phone', 
        'citizen_cpf', 'category_id', 'user_id', 'created_by_id', 'status', 
        'priority', 'description', 'address', 'reference_point', 
        'latitude', 'longitude', 'due_date'
    ];

    protected $casts = [
        'status' => \App\Enums\TicketStatus::class,
        'priority' => \App\Enums\Priority::class,
        'due_date' => 'datetime',
    ];

    // --- RELACIONAMENTOS ---

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Agente responsável pelo chamado.
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Agente que criou o chamado (se aplicável).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function logs()
    {
        return $this->hasMany(TicketLog::class);
    }

    public function media()
    {
        return $this->hasMany(TicketMedia::class);
    }
}
