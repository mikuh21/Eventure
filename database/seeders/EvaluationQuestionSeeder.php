<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\EvaluationQuestion;

class EvaluationQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'question' => 'Overall satisfaction with the event',
                'field_key' => 'rating',
                'type' => EvaluationQuestion::TYPE_LIKERT,
                'placeholder' => null,
                'help_text' => 'Choose how satisfied you were with the event overall.',
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 1,
                'event_type' => EvaluationQuestion::EVENT_TYPE_ALL,
                'is_guest_question' => false,
            ],
            [
                'question' => 'How clear and helpful were the event presentations?',
                'field_key' => null,
                'type' => EvaluationQuestion::TYPE_LIKERT,
                'placeholder' => null,
                'help_text' => 'Rate the clarity and usefulness of the presentations.',
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 2,
                'event_type' => EvaluationQuestion::EVENT_TYPE_ALL,
                'is_guest_question' => false,
            ],
            [
                'question' => 'How would you rate the overall organization of the event?',
                'field_key' => null,
                'type' => EvaluationQuestion::TYPE_LIKERT,
                'placeholder' => null,
                'help_text' => 'Rate the event organization, logistics, and coordination.',
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 3,
                'event_type' => EvaluationQuestion::EVENT_TYPE_ALL,
                'is_guest_question' => false,
            ],
            [
                'question' => 'What did you like most about the event?',
                'field_key' => 'feedback',
                'type' => EvaluationQuestion::TYPE_TEXTAREA,
                'placeholder' => 'Enter your answer here...',
                'help_text' => 'Your comments help us improve future events.',
                'is_required' => false,
                'is_active' => true,
                'sort_order' => 4,
                'event_type' => EvaluationQuestion::EVENT_TYPE_ALL,
                'is_guest_question' => false,
            ],
            [
                'question' => 'How likely are you to recommend this event to others?',
                'field_key' => null,
                'type' => EvaluationQuestion::TYPE_LIKERT,
                'placeholder' => null,
                'help_text' => 'A higher score means a stronger recommendation.',
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 5,
                'event_type' => EvaluationQuestion::EVENT_TYPE_ALL,
                'is_guest_question' => false,
            ],
            [
                'question' => 'Guest speaker performance',
                'field_key' => null,
                'type' => EvaluationQuestion::TYPE_LIKERT,
                'placeholder' => null,
                'help_text' => 'Rate the guest speaker performance if applicable.',
                'is_required' => false,
                'is_active' => true,
                'sort_order' => 6,
                'event_type' => EvaluationQuestion::EVENT_TYPE_ALL,
                'is_guest_question' => true,
            ],
        ];

        foreach ($templates as $template) {
            EvaluationQuestion::updateOrCreate([
                'question' => $template['question'],
            ], $template);
        }
    }
}
