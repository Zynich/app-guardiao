<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id', 'name', 'description', 'priority', 'sla_hours', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => \App\Enums\Priority::class, 
    ];

    // --- RELACIONAMENTOS ---

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
