<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvaluationQuestionRequest;
use App\Http\Requests\UpdateEvaluationQuestionRequest;
use App\Models\EvaluationQuestion;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EvaluationQuestionController extends Controller
{
    public function index(): View
    {
        $events = Event::registrationOpen()
            ->orderBy('start_date')
            ->withCount(['participants', 'guests'])
            ->get();

        return view('admin.evaluation-questions.index', [
            'events' => $events,
        ]);
    }

    public function create(): View
    {
        $backUrl = url()->previous() !== url()->current() ? url()->previous() : route('admin.evaluation-questions.index');
        
        return view('admin.evaluation-questions.create', [
            'question' => new EvaluationQuestion([
                'is_required' => true,
                'is_active' => true,
                'sort_order' => (EvaluationQuestion::query()->max('sort_order') ?? 0) + 1,
                'event_type' => 'all',
            ]),
            'typeOptions' => EvaluationQuestion::typeOptions(),
            'fieldKeyOptions' => EvaluationQuestion::fieldKeyOptions(),
            'eventTypeOptions' => EvaluationQuestion::eventTypeOptions(),
            'events' => Event::orderBy('start_date', 'asc')->get(),
            'backUrl' => $backUrl,
        ]);
    }

    public function store(StoreEvaluationQuestionRequest $request)
    {
        $question = EvaluationQuestion::create($request->validated());

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Evaluation question created successfully.',
                'question' => $question,
            ], 201);
        }

        return redirect()
            ->route('admin.evaluation-questions.index')
            ->with('status', 'Evaluation question created successfully.');
    }

    public function edit(EvaluationQuestion $evaluationQuestion): View
    {
        $backUrl = url()->previous() !== url()->current() ? url()->previous() : route('admin.evaluation-questions.index');
        
        return view('admin.evaluation-questions.edit', [
            'question' => $evaluationQuestion,
            'typeOptions' => EvaluationQuestion::typeOptions(),
            'fieldKeyOptions' => EvaluationQuestion::fieldKeyOptions(),
            'eventTypeOptions' => EvaluationQuestion::eventTypeOptions(),
            'events' => Event::orderBy('start_date', 'asc')->get(),
            'backUrl' => $backUrl,
        ]);
    }

    public function update(UpdateEvaluationQuestionRequest $request, EvaluationQuestion $evaluationQuestion)
    {
        $evaluationQuestion->update($request->validated());

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Evaluation question updated successfully.',
                'question' => $evaluationQuestion,
            ], 200);
        }

        return redirect()
            ->route('admin.evaluation-questions.index')
            ->with('status', 'Evaluation question updated successfully.');
    }

    public function destroy(\Illuminate\Http\Request $request, EvaluationQuestion $evaluationQuestion)
    {
        $evaluationQuestion->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Evaluation question deleted successfully.'], 204);
        }

        return redirect()
            ->route('admin.evaluation-questions.index')
            ->with('status', 'Evaluation question deleted successfully.');
    }

    public function toggleStatus(EvaluationQuestion $evaluationQuestion): RedirectResponse
    {
        $evaluationQuestion->update(['is_active' => ! $evaluationQuestion->is_active]);

        return redirect()
            ->route('admin.evaluation-questions.index')
            ->with('status', 'Question ' . ($evaluationQuestion->is_active ? 'enabled' : 'disabled') . ' successfully.');
    }

    public function loadDefaultQuestions(): RedirectResponse
    {
        foreach (Event::all() as $event) {
            EvaluationQuestion::createDefaultFormForEvent($event);
        }

        return redirect()
            ->route('admin.evaluation-questions.index')
            ->with('status', 'Default evaluation forms have been loaded for all events.');
    }

    private function defaultQuestionTemplates(): array
    {
        return [
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
    }
}