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
            $teams = $event->teams;
            $requirements = Requirement::factory(10)->create([
                'event_id' => $event->id,
            ]);
            $requirementIDs = $requirements->pluck('id');
            foreach($teams as $team){
                $project = $team->project;
                if($project){
                    foreach($requirementIDs as $requirementID){
                    $project->requirements()->attach($requirementID, ['rating' => rand(1,10)]);
                    }
                }
            }
        }
    }
}
