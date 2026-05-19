<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Convert existing 'standard' values to 'school_event'
        DB::table('events')->where('type', 'standard')->update(['type' => 'school_event']);

        // Update the enum to use 'school_event' instead of 'standard'
        Schema::table('events', function (Blueprint $table): void {
            $table->enum('type', ['school_event', 'conference'])->default('school_event')->change();
        });
    }

    public function down(): void
    {
        // Revert back to 'standard'
        DB::table('events')->where('type', 'school_event')->update(['type' => 'standard']);

        Schema::table('events', function (Blueprint $table): void {
            $table->enum('type', ['standard', 'conference'])->default('standard')->change();
        });
    }
};
