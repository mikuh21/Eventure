<?php

namespace Database\Seeders;

use App\Models\Submission;
use App\Models\Participant;
use Illuminate\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake();

        $participants = Participant::all();

        foreach ($participants as $participant) {
            if (rand(0, 1) === 0) {
                continue; // not every participant submits
            }

            Submission::updateOrCreate([
                'participant_id' => $participant->id,
            ], [
                'title' => $faker->sentence(4),
                'file_path' => 'submissions/'.$participant->event_id.'/entry-'.$participant->id.'.pdf',
                'status' => collect(['pending', 'approved', 'rejected'])->random(),
            ]);
        }
    }
}
