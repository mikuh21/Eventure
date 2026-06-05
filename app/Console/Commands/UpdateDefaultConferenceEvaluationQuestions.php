<?php

namespace App\Console\Commands;

use App\Models\EvaluationQuestion;
use Illuminate\Console\Command;

class UpdateDefaultConferenceEvaluationQuestions extends Command
{
    protected $signature = 'eventure:update-default-conference-questions';

    protected $description = 'Update default Session Feedback and Open-ended Feedback questions for conference events';

    public function handle()
    {
        $this->info('🔄 Updating default conference evaluation questions...');

        try {
            // Start transaction
            \DB::beginTransaction();

            // Step 1: Delete existing default Session Feedback questions for conference
            $deletedSessionCount = EvaluationQuestion::whereNull('event_id')
                ->where('section', 'Session Feedback')
                ->where('event_type', 'conference')
                ->delete();

            $this->info("✓ Deleted {$deletedSessionCount} existing default Session Feedback questions");

            // Step 2: Delete existing default Open-ended Feedback questions for conference
            $deletedOpenCount = EvaluationQuestion::whereNull('event_id')
                ->where('section', 'Open-ended Feedback')
                ->where('event_type', 'conference')
                ->delete();

            $this->info("✓ Deleted {$deletedOpenCount} existing default Open-ended Feedback questions");

            // Step 3: Insert new default Session Feedback questions (Likert scale)
            $sessionFeedbackQuestions = [
                ['sort_order' => 1, 'question' => 'The objectives of CONVERGE 2026 were clearly communicated.'],
                ['sort_order' => 2, 'question' => 'The activities and sessions aligned with the event objectives.'],
                ['sort_order' => 3, 'question' => 'The event provided valuable learning and insights.'],
                ['sort_order' => 4, 'question' => 'The event highlighted important Sustainable Development Goals (SDGs).'],
                ['sort_order' => 5, 'question' => 'The speakers demonstrated expertise in their respective topics.'],
                ['sort_order' => 6, 'question' => 'The presentations were engaging and informative.'],
                ['sort_order' => 7, 'question' => 'The event program was well-organized and easy to follow.'],
                ['sort_order' => 8, 'question' => 'The allotted time for each session was appropriate.'],
                ['sort_order' => 9, 'question' => 'Overall, I am satisfied with CONVERGE 2026.'],
                ['sort_order' => 10, 'question' => 'I would recommend participation in future CONVERGE events.'],
            ];

            foreach ($sessionFeedbackQuestions as $q) {
                EvaluationQuestion::create([
                    'event_id' => null,
                    'question' => $q['question'],
                    'type' => 'likert',
                    'is_required' => true,
                    'is_active' => true,
                    'is_matrix' => false,
                    'is_guest_question' => false,
                    'event_type' => 'conference',
                    'section' => 'Session Feedback',
                    'sort_order' => $q['sort_order'],
                ]);
            }

            $this->info("✓ Created 10 new default Session Feedback questions (Likert scale)");

            // Step 4: Insert new default Open-ended Feedback questions (Textarea)
            $openEndedQuestions = [
                ['sort_order' => 1, 'question' => 'What did you like most about CONVERGE 2026?'],
                ['sort_order' => 2, 'question' => 'What aspects of the event could be improved?'],
                ['sort_order' => 3, 'question' => 'Additional comments and suggestions'],
            ];

            foreach ($openEndedQuestions as $q) {
                EvaluationQuestion::create([
                    'event_id' => null,
                    'question' => $q['question'],
                    'type' => 'textarea',
                    'is_required' => false,
                    'is_active' => true,
                    'is_matrix' => false,
                    'is_guest_question' => false,
                    'event_type' => 'conference',
                    'section' => 'Open-ended Feedback',
                    'sort_order' => $q['sort_order'],
                ]);
            }

            $this->info("✓ Created 3 new default Open-ended Feedback questions (Textarea)");

            // Commit transaction
            \DB::commit();

            $this->info("\n✅ Successfully updated default conference evaluation questions!");
            $this->info("Total questions created: 13 (10 Likert + 3 Textarea)");
            $this->info("These are now the DEFAULT questions for all future conference events.");

            return 0;
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->error("❌ Error occurred: {$e->getMessage()}");
            $this->error("Transaction rolled back. No changes were made.");
            return 1;
        }
    }
}
