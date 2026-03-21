<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes; 

    protected $fillable = [
        'parent_id', 'name', 'description', 'priority', 'sla_hours', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => \App\Enums\Priority::class, 
    ];

    // --- RELACIONAMENTOS ---

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
