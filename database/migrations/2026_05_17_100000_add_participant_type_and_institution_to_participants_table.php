<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table): void {
            $table->string('participant_type')->nullable()->after('email');
            $table->string('institution')->nullable()->after('participant_type');
        });
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table): void {
            $table->dropColumn(['participant_type', 'institution']);
        });
    }
};
