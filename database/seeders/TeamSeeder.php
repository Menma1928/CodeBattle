<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\User;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = Team::factory(20)->create();
        foreach ($teams as $team) {
            $userIDs = User::all()->random(5)->pluck('id');
            $team->users()->attach($userIDs);
        }
    }
}
