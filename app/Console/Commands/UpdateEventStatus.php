<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;

class UpdateEventStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza automáticamente el estado de los eventos basándose en sus fechas de inicio y fin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Actualizando estado de eventos...');

        // Obtener todos los eventos que no están finalizados manualmente
        $events = Event::whereIn('estado', ['pendiente', 'activo', 'en_calificacion'])->get();

        $updated = 0;

        foreach ($events as $event) {
            $currentState = $event->getCurrentState();
            
            // Solo actualizar si el estado calculado es diferente al actual
            if ($event->estado !== $currentState) {
                $oldState = $event->estado;
                $event->update(['estado' => $currentState]);
                
                $this->line("Evento '{$event->nombre}': {$oldState} → {$currentState}");
                $updated++;
            }
        }

        if ($updated > 0) {
            $this->info("✓ {$updated} evento(s) actualizado(s)");
        } else {
            $this->info('No hay eventos que actualizar');
        }

        return 0;
    }
}
