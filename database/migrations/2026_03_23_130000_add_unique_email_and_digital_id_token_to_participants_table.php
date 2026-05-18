<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            if (! Schema::hasColumn('participants', 'digital_id_token')) {
                $table->string('digital_id_token', 64)->nullable()->after('email');
            }

            if (! $this->indexExists('participants', 'participants_event_id_email_unique')) {
                $table->unique(['event_id', 'email']);
            }

            if (! $this->indexExists('participants', 'participants_digital_id_token_unique')) {
                $table->unique(['digital_id_token']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            if ($this->indexExists('participants', 'participants_event_id_email_unique')) {
                $table->dropUnique(['event_id', 'email']);
            }

            if ($this->indexExists('participants', 'participants_digital_id_token_unique')) {
                $table->dropUnique(['digital_id_token']);
            }

            if (Schema::hasColumn('participants', 'digital_id_token')) {
                $table->dropColumn('digital_id_token');
            }
        });
    }

    private function indexExists(string $table, string $index): bool
    {
        $connection = config('database.default');

        if ($connection === 'sqlite') {
            $indexes = collect(DB::select("PRAGMA index_list(`{$table}`)"))->pluck('name');
            return $indexes->contains($index);
        }

        if ($connection === 'pgsql') {
            $result = DB::select("
                SELECT indexname FROM pg_indexes
                WHERE tablename = ? AND indexname = ?
            ", [$table, $index]);
            return !empty($result);
        }

        // MySQL / MariaDB fallback
        $result = DB::select('SHOW INDEX FROM ' . $table . ' WHERE Key_name = ?', [$index]);
        return !empty($result);
    }
};