<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE events DROP CONSTRAINT IF EXISTS events_type_check');
        DB::statement("ALTER TABLE events ADD CONSTRAINT events_type_check CHECK (type IN ('school_event', 'conference', 'event'))");
    }

    public function down(): void
    {
        if (DB::table('events')->where('type', 'event')->exists()) {
            throw new RuntimeException('Cannot revert event type constraint while event records exist.');
        }

        DB::statement('ALTER TABLE events DROP CONSTRAINT IF EXISTS events_type_check');
        DB::statement("ALTER TABLE events ADD CONSTRAINT events_type_check CHECK (type IN ('school_event', 'conference'))");
    }
};