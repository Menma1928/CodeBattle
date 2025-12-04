<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


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
    ];

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function requirements(): HasMany
    {
        return $this->hasMany(Requirement::class);
    }
}
