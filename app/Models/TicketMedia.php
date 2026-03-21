<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketMedia extends Model
{
    use SoftDeletes; 

    protected $fillable = [
        'ticket_id', 'user_id', 'file_path', 'file_type'
    ];

    // --- RELACIONAMENTOS ---

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
