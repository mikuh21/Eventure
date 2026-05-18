<?php

namespace Database\Seeders;

use App\Models\Participant;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ParticipantSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake();

        $events = Event::all();

        foreach ($events as $event) {
            $count = rand(6, 12);
            for ($i = 1; $i <= $count; $i++) {
                Participant::updateOrCreate([
                    'email' => 'event'.$event->id.'.participant'.$i.'@example.com',
                ], [
                    'name' => $faker->name(),
                    'event_id' => $event->id,
                    'digital_id_token' => (string) Str::uuid(),
                ]);
            }
        }
    }
}
