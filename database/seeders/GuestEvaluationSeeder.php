<?php

namespace Database\Seeders;

use App\Models\GuestEvaluation;
use App\Models\Guest;
use Illuminate\Database\Seeder;

class GuestEvaluationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake();

        $guests = Guest::all();

        foreach ($guests as $guest) {
            if (rand(0, 1) === 0) {
                continue; // not every guest gets evaluations
            }

            GuestEvaluation::create([
                'guest_id' => $guest->id,
                'rating' => $faker->numberBetween(2, 5),
                'feedback' => $faker->optional()->sentence(12),
            ]);
        }
    }
}
