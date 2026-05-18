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
                'password' => Hash::make('password'),
                'role' => 'event_staff',
                'approval_status' => 'approved',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'approval_status' => 'approved',
            ]
        );
    }
}
