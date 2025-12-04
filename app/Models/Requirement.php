<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
use App\Models\Event;

class Requirement extends Model
{
    /** @use HasFactory<\Database\Factories\RequirementFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'event_id',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
    
    public function user(): BelongsToMany{
        return $this->belongsToMany(User::class);
    }
}
