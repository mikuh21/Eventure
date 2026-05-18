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
        Schema::table('evaluation_questions', function (Blueprint $table) {
            $table->string('section')->nullable()->after('event_type')->comment('Section name to group questions');
            $table->boolean('is_matrix')->default(false)->after('section')->comment('Whether this is a matrix/rating question');
            $table->json('matrix_items')->nullable()->after('is_matrix')->comment('Array of items for matrix questions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluation_questions', function (Blueprint $table) {
            $table->dropColumn(['section', 'is_matrix', 'matrix_items']);
        });
    }
};
