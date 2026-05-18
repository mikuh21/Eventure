<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'eventstaff@example.com'],
            [
                'name' => 'Event Staff',
                'password' => bcrypt('password123'),
                'role' => 'event_staff',
                'approval_status' => 'approved',
            ]
        );

        User::updateOrCreate(
            ['email' => 'events.inf233@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('Events.Inf233E!'),
                'role' => 'admin',
                'approval_status' => 'approved',
            ]
        );
    }
}
