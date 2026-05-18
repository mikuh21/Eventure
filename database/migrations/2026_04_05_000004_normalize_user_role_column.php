<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->string('role')->default('user')->after('email');
            });

            return;
        }

        DB::table('users')
            ->whereNull('role')
            ->update(['role' => 'user']);

        DB::table('users')
            ->whereNotIn('role', ['admin', 'event_staff', 'user'])
            ->update(['role' => 'user']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role VARCHAR(255) NOT NULL DEFAULT 'user'");
            return;
        }
        Schema::table('users', function (Blueprint $table): void {
            $table->string('role')->default('user')->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'role')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('event_staff') NOT NULL DEFAULT 'event_staff'");
            return;
        }
        Schema::table('users', function (Blueprint $table): void {
            $table->string('role')->default('event_staff')->change();
        });
    }
};
