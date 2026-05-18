<?php

namespace App\Http\Requests;

use App\Models\EvaluationQuestion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEvaluationQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $question = $this->route('evaluation_question');

        return [
            'question' => ['required', 'string', 'max:255'],
            'field_key' => ['nullable', Rule::in(array_keys(EvaluationQuestion::fieldKeyOptions())), Rule::unique('evaluation_questions', 'field_key')->ignore($question?->id)],
            'type' => ['required', Rule::in(array_keys(EvaluationQuestion::typeOptions()))],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'help_text' => ['nullable', 'string', 'max:1000'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:1'],
            'event_id' => ['nullable', 'integer', 'exists:events,id'],
            'event_type' => ['required', Rule::in(array_keys(EvaluationQuestion::eventTypeOptions()))],
            'is_guest_question' => ['nullable', 'boolean'],
            'section' => ['nullable', 'string', 'max:255'],
            'matrix_items' => ['nullable', 'array'],
            'matrix_items.*' => ['string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $matrixItems = $this->input('matrix_items');
        if (is_string($matrixItems)) {
            $decoded = json_decode($matrixItems, true);
            if (is_array($decoded)) {
                $matrixItems = $decoded;
            }
        }

        $existingEventId = $this->route('evaluation_question')?->event_id;
        $eventId = $this->has('event_id') ? $this->input('event_id') : $existingEventId;

        $this->merge([
            'field_key' => $this->filled('field_key') ? $this->input('field_key') : null,
            'is_required' => $this->boolean('is_required'),
            'is_active' => $this->boolean('is_active', true),
            'is_guest_question' => $this->boolean('is_guest_question'),
            'event_id' => $eventId,
            'event_type' => EvaluationQuestion::normalizeEventType($this->input('event_type', 'all')),
            'matrix_items' => $matrixItems,
        ]);
    }
}