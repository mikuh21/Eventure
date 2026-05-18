<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_questions', function (Blueprint $table): void {
            $table->id();
            $table->string('question');
            $table->string('field_key')->nullable()->unique();
            $table->string('type')->default('text');
            $table->string('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->boolean('is_required')->default(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(1);
            $table->foreignId('event_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('event_type')->default('all');
            $table->boolean('is_guest_question')->default(false);
            $table->timestamps();
        });

        DB::table('evaluation_questions')->insert([
            [
                'question' => 'Overall Rating',
                'field_key' => 'rating',
                'type' => 'rating',
                'placeholder' => null,
                'help_text' => 'Rate your overall event experience from 1 to 5.',
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Feedback',
                'field_key' => 'feedback',
                'type' => 'textarea',
                'placeholder' => 'Share your comments about the event.',
                'help_text' => 'Tell us what worked well and what should improve.',
                'is_required' => false,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_questions');
    }
};