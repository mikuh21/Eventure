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
        // Seed users and admin accounts first (events reference users via created_by)
        $this->call([
            AdminUserSeeder::class,
            UsersSeeder::class,
        ]);

        // Seed core domain data in dependency order
        $this->call([
            EventSeeder::class,
            GuestSeeder::class,
            ParticipantSeeder::class,
            SubmissionSeeder::class,
            EvaluationSeeder::class,
            GuestEvaluationSeeder::class,
            EvaluationQuestionSeeder::class,
        ]);
    }
}