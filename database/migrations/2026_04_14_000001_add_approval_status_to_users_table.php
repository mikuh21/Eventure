<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'approval_status')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->string('approval_status')->default('approved')->after('role');
            });
        }

        DB::table('users')
            ->where('role', 'admin')
            ->update(['approval_status' => 'approved']);

        DB::table('users')
            ->where('role', 'event_staff')
            ->whereNull('approval_status')
            ->update(['approval_status' => 'approved']);

        DB::table('users')
            ->whereNull('approval_status')
            ->update(['approval_status' => 'approved']);
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'approval_status')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('approval_status');
        });
    }
};