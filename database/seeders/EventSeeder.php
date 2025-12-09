<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Solo crear eventos si no existen muchos ya
        $currentEventCount = Event::count();
        $targetEventCount = 20;
        
        if ($currentEventCount < $targetEventCount) {
            $eventsToCreate = $targetEventCount - $currentEventCount;
            Event::factory($eventsToCreate)->create();
        }
    }
}
