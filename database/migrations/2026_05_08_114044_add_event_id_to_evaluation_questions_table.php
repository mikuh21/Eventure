<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluation_questions', function (Blueprint $table) {
            if (! Schema::hasColumn('evaluation_questions', 'event_id')) {
                $table->unsignedBigInteger('event_id')->nullable()->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('evaluation_questions', function (Blueprint $table) {
            if (Schema::hasColumn('evaluation_questions', 'event_id')) {
                $table->dropColumn('event_id');
            }
        });
    }
};