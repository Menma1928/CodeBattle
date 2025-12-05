<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectJuryRequirement extends Model
{
    use HasFactory;

    protected $table = 'project_jury_requirement';

    protected $fillable = [
        'project_id',
        'requirement_id',
        'jury_id',
        'rating',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function requirement(): BelongsTo
    {
        return $this->belongsTo(Requirement::class);
    }

    public function jury(): BelongsTo
    {
        return $this->belongsTo(User::class, 'jury_id');
    }
}
