<?php

namespace Database\Seeders;

use App\Models\Evaluation;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);
        $this->call(EvaluationQuestionSeeder::class);

        User::query()->updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'role' => 'participant',
            'approval_status' => User::APPROVAL_APPROVED,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ]);

        $events = collect([
            [
                'title' => 'City Innovation Hackathon',
                'description' => 'Build practical solutions for city services and smart mobility.',
                'event_date' => now()->addDays(10)->toDateString(),
                'location' => 'Lagos',
            ],
            [
                'title' => 'Green Future Challenge',
                'description' => 'Submit ideas and prototypes for sustainability and recycling.',
                'event_date' => now()->addDays(24)->toDateString(),
                'location' => 'Abuja',
            ],
            [
                'title' => 'Education Tech Sprint',
                'description' => 'Create tools that improve student learning outcomes.',
                'event_date' => now()->addDays(38)->toDateString(),
                'location' => 'Port Harcourt',
            ],
        ])->map(static function (array $event): Event {
            return Event::query()->updateOrCreate(
                ['title' => $event['title']],
                $event,
            );
        });

        foreach ($events as $event) {
            for ($i = 1; $i <= 6; $i++) {
                $participant = Participant::query()->updateOrCreate([
                    'email' => 'event'.$event->id.'.participant'.$i.'@example.com',
                ], [
                    'name' => fake()->name(),
                    'event_id' => $event->id,
                    'digital_id_token' => (string) Str::uuid(),
                ]);

                Submission::query()->updateOrCreate([
                    'participant_id' => $participant->id,
                ], [
                    'title' => 'Submission '.$i.' for '.$event->title,
                    'file_path' => 'submissions/event-'.$event->id.'/entry-'.$i.'.pdf',
                    'status' => collect(['pending', 'approved', 'rejected'])->random(),
                ]);

                Evaluation::query()->updateOrCreate([
                    'participant_id' => $participant->id,
                ], [
                    'rating' => fake()->numberBetween(2, 5),
                    'feedback' => fake()->sentence(12),
                ]);
            }
        }
    }
}