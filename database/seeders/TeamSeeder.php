<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\User;
use App\Models\Event;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = Event::all();
        foreach($events as $event){
            // Verificar cuántos equipos ya tiene este evento
            $existingTeamsCount = $event->teams()->count();
            $targetTeamsCount = 10;
            
            if ($existingTeamsCount < $targetTeamsCount) {
                $teamsToCreate = $targetTeamsCount - $existingTeamsCount;
                $teams = Team::factory($teamsToCreate)->create([
                    'event_id' => $event->id,
                ]);
                
                foreach ($teams as $team) {
                    // Solo agregar usuarios si el equipo no tiene miembros
                    if ($team->users()->count() === 0) {
                        $userIDs = User::all()->random(min(5, User::count()))->pluck('id');
                        $team->users()->attach($userIDs);
                    }
                }
            }
        }
    }
}
