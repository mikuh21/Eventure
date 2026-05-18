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
        if (! Schema::hasTable('evaluation_questions')) {
            return;
        }

        Schema::table('evaluation_questions', function (Blueprint $table) {
            if (! Schema::hasColumn('evaluation_questions', 'event_id')) {
                $table->foreignId('event_id')->nullable()->after('sort_order')->constrained()->cascadeOnDelete();
            }

            if (! Schema::hasColumn('evaluation_questions', 'event_type')) {
                $table->string('event_type')->default('all')->after('event_id'); // school_event, conference, all
            }

            if (! Schema::hasColumn('evaluation_questions', 'is_guest_question')) {
                $table->boolean('is_guest_question')->default(false)->after('event_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('evaluation_questions')) {
            return;
        }

        Schema::table('evaluation_questions', function (Blueprint $table) {
            if (Schema::hasColumn('evaluation_questions', 'is_guest_question')) {
                $table->dropColumn('is_guest_question');
            }

            if (Schema::hasColumn('evaluation_questions', 'event_type')) {
                $table->dropColumn('event_type');
            }

            if (Schema::hasColumn('evaluation_questions', 'event_id')) {
                $table->dropForeign(['event_id']);
                $table->dropColumn('event_id');
            }
        });
    }
};
