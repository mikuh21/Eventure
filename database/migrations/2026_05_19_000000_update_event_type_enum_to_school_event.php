<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First drop the old constraint if it exists
        DB::statement('ALTER TABLE events DROP CONSTRAINT IF EXISTS events_type_check');

        // Convert existing 'standard' values to 'school_event'
        DB::table('events')->where('type', 'standard')->update(['type' => 'school_event']);

        // Update the enum constraint using PostgreSQL-compatible statements
        DB::statement("ALTER TABLE events ALTER COLUMN type TYPE varchar(255)");
        DB::statement("ALTER TABLE events ALTER COLUMN type SET DEFAULT 'school_event'");
        DB::statement("ALTER TABLE events ADD CONSTRAINT events_type_check CHECK (type IN ('school_event', 'conference'))");
    }

    public function down(): void
    {
        // Revert back to 'standard'
        DB::table('events')->where('type', 'school_event')->update(['type' => 'standard']);

        DB::statement('ALTER TABLE events DROP CONSTRAINT IF EXISTS events_type_check');
        DB::statement("ALTER TABLE events ALTER COLUMN type TYPE varchar(255)");
        DB::statement("ALTER TABLE events ALTER COLUMN type SET DEFAULT 'standard'");
        DB::statement("ALTER TABLE events ALTER COLUMN type SET NOT NULL");
        DB::statement("ALTER TABLE events ADD CONSTRAINT events_type_check CHECK (type IN ('standard', 'conference'))");
    }
};
