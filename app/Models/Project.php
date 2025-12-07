<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Team;
use App\Models\Requirement;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;   

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado_validacion',
        'url_archivo',
        'github_url',
        'fecha_subida',
        'team_id',
    ];

    protected $casts = [
        'fecha_subida' => 'datetime',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function requirements(): BelongsToMany
    {
        return $this->belongsToMany(Requirement::class, 'project_requirement')
            ->withPivot('rating')
            ->withTimestamps();
    }

    public function juryRatings(): HasMany
    {
        return $this->hasMany(ProjectJuryRequirement::class);
    }

    /**
     * Calcular el promedio global del proyecto
     */
    public function getAverageRating(): float
    {
        // Obtener el promedio de los ratings en la tabla project_requirement
        $average = $this->requirements()->avg('project_requirement.rating');
        return round($average ?? 0, 2);
    }

    /**
     * Obtener el promedio de un requisito específico
     */
    public function getRequirementAverage($requirementId): float
    {
        $requirement = $this->requirements()
            ->where('requirement_id', $requirementId)
            ->first();

        return round($requirement?->pivot?->rating ?? 0, 2);
    }
}
