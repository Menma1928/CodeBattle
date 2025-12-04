<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'regla',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
