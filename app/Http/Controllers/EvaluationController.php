<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEvaluationRequest;
use App\Http\Requests\UpdateEvaluationRequest;
use App\Models\Event;
use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class EvaluationController extends Controller
{
    public function index(Request $request, Participant $participant)
    {
        $wantsJson = $request->expectsJson() || $request->is('api/*');

        $evaluations = $participant->evaluations()->latest()->paginate(10);

        if ($wantsJson) {
            return response()->json([
                'data' => $evaluations->items(),
                'meta' => [
                    'current_page' => $evaluations->currentPage(),
                    'per_page' => $evaluations->perPage(),
                    'total' => $evaluations->total(),
                    'last_page' => $evaluations->lastPage(),
                ],
            ]);
        }

        return view('evaluations.index', [
            'participant' => $participant,
            'evaluations' => $evaluations,
        ]);
    }

    public function create(Request $request, Participant $participant)
    {
        $wantsJson = $request->expectsJson() || $request->is('api/*');
        $event = $participant->event;

        // Check if participant has already submitted an evaluation
        if ($participant->evaluations()->exists()) {
            $message = 'You have already submitted your evaluation for this event.';

            return $wantsJson
                ? response()->json(['message' => $message], 422)
                : redirect()->route('participants.digital-id.show', $participant)->with('info', $message);
        }

        if (! $event->isSurveyActive()) {
            $message = 'Survey is not active yet. It becomes available after the event ends.';

            return $wantsJson
                ? response()->json(['message' => $message], 422)
                : redirect()->route('participants.evaluations.index', $participant)->withErrors(['survey' => $message]);
        }

        if (! $event->isEvaluationFormEnabled()) {
            $message = 'Evaluation form is not available yet.';

            return $wantsJson
                ? response()->json(['message' => $message], 422)
                : redirect()->route('participants.evaluations.index', $participant)->withErrors(['form' => $message]);
        }

        if (! $participant->attended) {
            $message = 'Cannot submit evaluation until attendance is recorded for this participant.';

            return $wantsJson
                ? response()->json(['message' => $message], 422)
                : redirect()->route('participants.evaluations.index', $participant)->withErrors(['attendance' => $message]);
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Send evaluation answers to create an evaluation.',
                'participant_id' => $participant->id,
                'event_id' => $event->id,
                'survey_active' => true,
                'questions' => $this->activeQuestions($event),
            ]);
        }

        return view('evaluations.create', [
            'participant' => $participant,
            'event' => $event,
            'questions' => $this->activeQuestions($event),
        ]);
    }

    public function store(StoreEvaluationRequest $request, Participant $participant)
    {
        $wantsJson = $request->expectsJson() || $request->is('api/*');
        $event = $participant->event;

        // Check if participant has already submitted an evaluation
        if ($participant->evaluations()->exists()) {
            $message = 'You have already submitted your evaluation for this event.';

            return $wantsJson
                ? response()->json(['message' => $message], 422)
                : redirect()->route('participants.digital-id.show', $participant)->with('info', $message);
        }

        if (! $event->isSurveyActive()) {
            $message = 'Survey is not active yet. It becomes available after the event ends.';

            return $wantsJson
                ? response()->json(['message' => $message], 422)
                : redirect()->route('participants.evaluations.index', $participant)->withErrors(['survey' => $message]);
        }

        if (! $event->isEvaluationFormEnabled()) {
            $message = 'Evaluation form is not available yet.';

            return $wantsJson
                ? response()->json(['message' => $message], 422)
                : redirect()->route('participants.evaluations.index', $participant)->withErrors(['form' => $message]);
        }

        if (! $participant->attended) {
            $message = 'Cannot submit evaluation until attendance is recorded for this participant.';

            return $wantsJson
                ? response()->json(['message' => $message], 422)
                : redirect()->route('participants.evaluations.index', $participant)->withErrors(['attendance' => $message]);
        }

        $validated = $request->validated();

        // Event 36: answers use fake IDs (e36_*), save them as-is bypassing prepareEvaluationPayload
        if ($event->id === 36) {
            $answers = $validated['answers'] ?? [];
            $feedback = $validated['feedback'] ?? ($answers['e36_18'] ?? null);
            $evaluation = $participant->evaluations()->create([
                'rating' => null,
                'feedback' => $feedback,
                'answers' => $answers,
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Evaluation submitted successfully'], 201);
            }
            return redirect()->back()->with('success', 'Evaluation submitted successfully');
        }

        $evaluation = $participant->evaluations()->create($this->prepareEvaluationPayload($validated, $event));

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Evaluation submitted successfully'], 201);
        }

        if ($wantsJson) {
            return response()->json([
                'message' => 'Evaluation created successfully.',
                'data' => $evaluation,
            ], 201);
        }

        return redirect()->back()->with('success', 'Evaluation submitted successfully');
    }

    public function show(Request $request, Participant $participant, Evaluation $evaluation)
    {
        $this->ensureEvaluationBelongsToParticipant($participant, $evaluation);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json($evaluation);
        }

        return view('evaluations.show', [
            'participant' => $participant,
            'evaluation' => $evaluation,
        ]);
    }

    public function edit(Request $request, Participant $participant, Evaluation $evaluation)
    {
        $this->ensureEvaluationBelongsToParticipant($participant, $evaluation);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json($evaluation);
        }

        return view('evaluations.edit', [
            'participant' => $participant,
            'event' => $participant->event,
            'evaluation' => $evaluation,
            'questions' => $this->activeQuestions($participant->event),
        ]);
    }

    public function update(UpdateEvaluationRequest $request, Participant $participant, Evaluation $evaluation)
    {
        $this->ensureEvaluationBelongsToParticipant($participant, $evaluation);

        $wantsJson = $request->expectsJson() || $request->is('api/*');

        $evaluation->update($this->prepareEvaluationPayload($request->validated(), $participant->event, $evaluation));

        if ($wantsJson) {
            return response()->json([
                'message' => 'Evaluation updated successfully.',
                'data' => $evaluation->fresh(),
            ]);
        }

        return redirect()
            ->route('participants.evaluations.show', [$participant, $evaluation])
            ->with('status', 'Evaluation updated successfully.');
    }

    public function destroy(Request $request, Participant $participant, Evaluation $evaluation)
    {
        $this->ensureEvaluationBelongsToParticipant($participant, $evaluation);

        $wantsJson = $request->expectsJson() || $request->is('api/*');

        $evaluation->delete();

        if ($wantsJson) {
            return response()->json(null, 204);
        }

        return redirect()
            ->route('participants.evaluations.index', $participant)
            ->with('status', 'Evaluation deleted successfully.');
    }

    public function eventAverage(Event $event)
    {
        return response()->json([
            'event_id' => $event->id,
            'average_rating' => $event->averageRating(),
        ]);
    }

    private function ensureEvaluationBelongsToParticipant(Participant $participant, Evaluation $evaluation): void
    {
        abort_if($evaluation->participant_id !== $participant->id, 404);
    }

    private function activeQuestions(Event $event)
    {
        if (! Schema::hasTable('evaluation_questions')) {
            return collect();
        }

        return $event->getActiveEvaluationQuestions();
    }

    private function prepareEvaluationPayload(array $validated, Event $event, ?Evaluation $existingEvaluation = null): array
    {
        $questions = $this->activeQuestions($event);
        $answers = $existingEvaluation?->answers ?? [];
        $rating = $validated['rating'] ?? $existingEvaluation?->rating;
        $feedback = $validated['feedback'] ?? $existingEvaluation?->feedback;

        $matrixRatings = [];

        foreach ($questions as $question) {
            $value = data_get($validated, 'answers.'.$question->id);

            if ($value === null && $question->field_key && array_key_exists($question->field_key, $validated)) {
                $value = $validated[$question->field_key];
            }

            if ($value !== null) {
                $answers[$question->id] = $value;
            } elseif (array_key_exists($question->id, $answers) && $question->is_required === false) {
                $answers[$question->id] = $answers[$question->id];
            }

            if ($question->field_key === 'rating' && isset($answers[$question->id])) {
                $rating = (int) $answers[$question->id];
            }

            if ($question->field_key === 'feedback' && isset($answers[$question->id])) {
                $feedback = (string) $answers[$question->id];
            }

            if ($question->is_matrix && $question->type === EvaluationQuestion::TYPE_LIKERT && is_array($value)) {
                foreach ($value as $matrixValue) {
                    if (is_numeric($matrixValue)) {
                        $matrixRatings[] = (int) $matrixValue;
                    }
                }
            }
        }

        if ($rating === null && count($matrixRatings) > 0) {
            $rating = (int) round(array_sum($matrixRatings) / count($matrixRatings));
        }

        return [
            'rating' => $rating,
            'feedback' => $feedback,
            'answers' => $answers,
        ];
    }
}
