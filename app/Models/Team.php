<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;
    protected $fillable = [
        'nombre',
        'descripcion',
        'posicion',
        'url_banner',
        'event_id',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('rol')->withTimestamps();
    }
    public function project(): HasOne
    {
        return $this->hasOne(Project::class);
    }

    /**
     * Get the leader of the team
     */
    public function leader()
    {
        return $this->users()->wherePivot('rol', 'lider')->first();
    }

    /**
     * Check if a user is the leader of this team
     */
    public function isLeader($userId): bool
    {
        return $this->users()
            ->wherePivot('user_id', $userId)
            ->wherePivot('rol', 'lider')
            ->exists();
    }

}
