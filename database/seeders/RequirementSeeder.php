<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Requirement;
use App\Models\Event;
use App\Models\Team;


class RequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = Event::all();
        foreach($events as $event){
            // Verificar cuántos requisitos ya tiene este evento
            $existingRequirementsCount = $event->requirements()->count();
            $targetRequirementsCount = 10;
            
            if ($existingRequirementsCount < $targetRequirementsCount) {
                $requirementsToCreate = $targetRequirementsCount - $existingRequirementsCount;
                $newRequirements = Requirement::factory($requirementsToCreate)->create([
                    'event_id' => $event->id,
                ]);
            }
            
            // Obtener todos los requisitos del evento
            $requirementIDs = $event->requirements->pluck('id');
            $teams = $event->teams;
            
            foreach($teams as $team){
                $project = $team->project;
                if($project){
                    foreach($requirementIDs as $requirementID){
                        // Solo adjuntar si no existe la relación
                        if (!$project->requirements()->where('requirement_id', $requirementID)->exists()) {
                            $project->requirements()->attach($requirementID, ['rating' => rand(1,10)]);
                        }
                    }
                }
            }
        }
    }
}
