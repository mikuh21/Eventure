<?php

namespace Database\Seeders;

use App\Models\Guest;
use App\Models\Event;
use Illuminate\Database\Seeder;

class GuestSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake();

        $events = Event::all();

        foreach ($events as $event) {
            $count = rand(1, 3);
            for ($i = 0; $i < $count; $i++) {
                Guest::updateOrCreate([
                    'event_id' => $event->id,
                    'email' => $faker->unique()->safeEmail(),
                ], [
                    'name' => $faker->name(),
                    'role' => collect(['speaker', 'panelist', 'moderator'])->random(),
                    'bio' => $faker->paragraph(),
                ]);
            }
        }
    }
}
