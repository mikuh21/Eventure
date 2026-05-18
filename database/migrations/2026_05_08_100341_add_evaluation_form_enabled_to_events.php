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
            $table->boolean('evaluation_form_enabled')->default(false)->after('survey_activated_at');
            $table->dateTime('evaluation_form_enabled_at')->nullable()->after('evaluation_form_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('evaluation_form_enabled');
            $table->dropColumn('evaluation_form_enabled_at');
        });
    }
};
