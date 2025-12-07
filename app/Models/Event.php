<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\EventRule;
use App\Models\Requirement;


class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'direccion',
        'estado',
        'url_imagen',
        'admin_id',
    ];

    /**
     * Los atributos que deben ser casteados a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    /**
     * Boot del modelo para actualizar estados automáticamente
     */
    protected static function booted()
    {
        // Actualizar estado automáticamente cuando se recupera un evento
        static::retrieved(function ($event) {
            if (in_array($event->estado, ['pendiente', 'activo', 'en_calificacion'])) {
                $currentState = $event->getCurrentState();
                if ($event->estado !== $currentState) {
                    $event->updateQuietly(['estado' => $currentState]);
                }
            }
        });
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function eventRules(): HasMany
    {
        return $this->hasMany(EventRule::class);
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(Requirement::class);
    }

    public function juries(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_jury', 'event_id', 'user_id')->withTimestamps();
    }

    /**
     * Determinar si el evento está activo según las fechas
     */
    public function isActive(): bool
    {
        $now = now();
        return $now->between($this->fecha_inicio, $this->fecha_fin);
    }

    /**
     * Determinar si el evento ya finalizó según la fecha
     */
    public function hasEnded(): bool
    {
        return now()->gt($this->fecha_fin);
    }

    /**
     * Determinar si el evento aún no ha comenzado
     */
    public function isPending(): bool
    {
        return now()->lt($this->fecha_inicio);
    }

    /**
     * Obtener el estado actual del evento basado en fechas
     */
    public function getCurrentState(): string
    {
        // Si el estado es 'finalizado', respetarlo (fue cerrado manualmente)
        if ($this->estado === 'finalizado') {
            return 'finalizado';
        }

        // Determinar estado automático según fechas
        if ($this->isPending()) {
            return 'pendiente';
        }

        if ($this->isActive()) {
            return 'activo';
        }

        // Si ya pasó la fecha de fin, cambiar a calificación automáticamente
        if ($this->hasEnded()) {
            return 'en_calificacion';
        }

        return 'pendiente';
    }

    /**
     * Verificar si se pueden editar calificaciones
     */
    public function canEditRatings(): bool
    {
        return $this->estado === 'en_calificacion';
    }

    /**
     * Verificar si se puede editar información del proyecto (GitHub, etc.)
     */
    public function canEditProjects(): bool
    {
        return in_array($this->estado, ['activo', 'en_calificacion']);
    }

    /**
     * Verificar si se pueden aceptar solicitudes de unión a equipos
     */
    public function canJoinTeams(): bool
    {
        return $this->estado === 'pendiente';
    }
}

