<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake();

        $admin = User::query()->first();

        for ($i = 1; $i <= 5; $i++) {
            Event::updateOrCreate([
                'title' => $faker->unique()->sentence(3),
            ], [
                'description' => $faker->paragraph(),
                'event_date' => $faker->dateTimeBetween('+1 days', '+90 days')->format('Y-m-d'),
                'location' => $faker->city(),
                'created_by' => $admin?->id,
            ]);
        }
    }
}
