<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table): void {
            $table->enum('type', ['standard', 'conference'])->default('standard')->after('id');
            $table->string('event_title')->nullable()->after('title');
            $table->string('conference_title')->nullable()->after('event_title');
            $table->string('theme')->nullable()->after('conference_title');
            $table->dateTime('start_registration')->nullable()->after('event_date');
            $table->dateTime('end_registration')->nullable()->after('start_registration');
            $table->string('poster_path')->nullable()->after('location');
            $table->string('template_file_path')->nullable()->after('poster_path');
            $table->json('keywords')->nullable()->after('template_file_path');
            $table->timestamp('survey_activated_at')->nullable()->after('keywords');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table): void {
            $table->dropColumn([
                'type',
                'event_title',
                'conference_title',
                'theme',
                'start_registration',
                'end_registration',
                'poster_path',
                'template_file_path',
                'keywords',
                'survey_activated_at',
            ]);
        });
    }
};
