<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluation_questions', function (Blueprint $table): void {
            $table->dropUnique('evaluation_questions_field_key_unique');
            $table->unique(['event_id', 'field_key'], 'evaluation_questions_event_id_field_key_unique');
        });
    }

    public function down(): void
    {
        Schema::table('evaluation_questions', function (Blueprint $table): void {
            $table->dropUnique('evaluation_questions_event_id_field_key_unique');
            $table->unique('field_key');
        });
    }
};
