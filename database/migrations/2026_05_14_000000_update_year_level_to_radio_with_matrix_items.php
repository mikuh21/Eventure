<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update Year Level questions to use radio type with matrix items
        DB::table('evaluation_questions')
            ->where('question', 'Year Level')
            ->update([
                'type' => 'radio',
                'matrix_items' => json_encode(['First Year', 'Second Year', 'Third Year', 'Fourth Year']),
                'is_matrix' => false,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert Year Level questions back to text type
        DB::table('evaluation_questions')
            ->where('question', 'Year Level')
            ->update([
                'type' => 'text',
                'matrix_items' => null,
                'is_matrix' => false,
            ]);
    }
};
