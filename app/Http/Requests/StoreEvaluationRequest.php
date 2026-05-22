<?php

namespace App\Http\Requests;

use App\Models\EvaluationQuestion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;

class StoreEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'feedback' => ['nullable', 'string'],
            'answers' => ['nullable', 'array'],
            'guest_answers' => ['nullable', 'array'],
        ];

        foreach ($this->activeQuestions() as $question) {
            if ($question->is_matrix) {
                $rules['answers.'.$question->id] = ['nullable', 'array'];
                $rules['answers.'.$question->id.'.*'] = ['nullable', 'integer', 'between:1,5'];
            } else {
                $rules['answers.'.$question->id] = $this->rulesForQuestion($question->type, $question->is_required);
            }
        }

        if (count($rules) === 4) {
            $rules['rating'][0] = 'required';
        }

        return $rules;
    }

    private function activeQuestions()
    {
        if (! Schema::hasTable('evaluation_questions')) {
            return collect();
        }

        $participant = $this->route('participant');
        if (!$participant) {
            return collect();
        }

        $event = $participant->event;

        return EvaluationQuestion::query()
            ->where('is_active', true)
            ->where('is_guest_question', false)
            ->forEventType($event->type)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    private function rulesForQuestion(string $type, bool $required): array
    {
        $rules = [$required ? 'required' : 'nullable'];

        return match ($type) {
            EvaluationQuestion::TYPE_RATING => [...$rules, 'integer', 'between:1,5'],
            EvaluationQuestion::TYPE_LIKERT => [...$rules, 'integer', 'between:1,5'],
            EvaluationQuestion::TYPE_TEXT => [...$rules, 'string', 'max:255'],
            default => [...$rules, 'string', 'max:2000'],
        };
    }
}
