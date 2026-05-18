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
        \DB::table('evaluation_questions')
            ->where('question', 'NU Lipa Email Address')
            ->update(['question' => 'Email']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::table('evaluation_questions')
            ->where('question', 'Email')
            ->update(['question' => 'NU Lipa Email Address']);
    }
};
