<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Add new date columns
            $table->date('start_date')->nullable()->after('event_date');
            $table->date('end_date')->nullable()->after('start_date');
            
            // Make event_date nullable for backward compatibility
            $table->date('event_date')->nullable()->change();
        });

        // Copy existing event_date values to start_date
        \DB::statement('UPDATE events SET start_date = event_date WHERE start_date IS NULL AND event_date IS NOT NULL');
        
        // Copy start_date to end_date if end_date is null
        \DB::statement('UPDATE events SET end_date = start_date WHERE end_date IS NULL AND start_date IS NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
            $table->date('event_date')->nullable(false)->change();
        });
    }
};
