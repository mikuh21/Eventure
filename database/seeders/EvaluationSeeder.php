<?php

namespace Database\Seeders;

use App\Models\Evaluation;
use App\Models\Participant;
use Illuminate\Database\Seeder;

class EvaluationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake();

        $participants = Participant::all();

        foreach ($participants as $participant) {
            if (rand(0, 1) === 0) {
                continue; // not every participant leaves an evaluation
            }

            Evaluation::updateOrCreate([
                'participant_id' => $participant->id,
            ], [
                'rating' => $faker->numberBetween(2, 5),
                'feedback' => $faker->optional()->sentence(10),
            ]);
        }
    }
}
