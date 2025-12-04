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
            $teams = Team::factory(10)->create([
                'event_id' => $event->id,
            ]);
            foreach ($teams as $team) {
                $userIDs = User::all()->random(5)->pluck('id');
                $team->users()->attach($userIDs);
            }
        }
    }
}
