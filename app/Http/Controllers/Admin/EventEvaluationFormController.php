<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EvaluationFormEnabledMail;
use App\Models\Event;
use App\Models\EvaluationQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class EventEvaluationFormController extends Controller
{
    public function index(Request $request): View
    {
        $schoolEventQuestions = $this->getQuestionsStats('school_event');
        $conferenceQuestions = $this->getQuestionsStats('conference');
        $selectedEvent = null;
        $hasEventIdColumn = Schema::hasColumn('evaluation_questions', 'event_id');

        if ($request->filled('event_id')) {
            $selectedEvent = Event::find($request->query('event_id'));

            if ($selectedEvent) {
                if ($hasEventIdColumn) {
                    $selectedEvent->load('evaluationQuestions');
                } else {
                    $selectedEvent->setRelation('evaluationQuestions', EvaluationQuestion::query()
                        ->where('event_type', $selectedEvent->type)
                        ->orWhere('event_type', 'all')
                        ->get()
                    );
                }
            }
        }

        $eventsQuery = Event::query()
            ->orderByRaw("CASE WHEN start_date::date = (NOW() AT TIME ZONE 'Asia/Manila')::date THEN 0 WHEN end_date::date < (NOW() AT TIME ZONE 'Asia/Manila')::date THEN 2 ELSE 1 END ASC")
            ->orderBy('start_date', 'asc')
            ->withCount(['participants', 'guests'])
            ->when($hasEventIdColumn, fn ($query) => $query->with(['evaluationQuestions']))
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->query('search') . '%');
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->query('type'));
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $status = $request->query('status');

                if ($status === 'open') {
                    $query->whereNotNull('evaluation_form_enabled_at');
                } elseif ($status === 'closed') {
                    $query->whereNull('evaluation_form_enabled_at');
                }
            });

        $events = $eventsQuery->get();

        $overview = [
            'total_events' => Event::count(),
            'forms_open' => Event::where('evaluation_form_enabled', true)->count(),
            'forms_closed' => Event::where('evaluation_form_enabled', false)->count(),
        ];

        return view('admin.evaluation-forms.index', [
            'events' => $events,
            'schoolEventQuestions' => $schoolEventQuestions,
            'conferenceQuestions' => $conferenceQuestions,
            'selectedEvent' => $selectedEvent,
            'overview' => $overview,
        ]);
    }

    public function enable(Event $event): RedirectResponse
    {
        // Get attended participants who haven't completed their evaluation
        $attendedParticipants = $event->participants()
            ->where('attended', true)
            ->whereDoesntHave('evaluations')
            ->get();

        if ($attendedParticipants->isEmpty()) {
            return redirect()
                ->route('admin.event-evaluation-forms.index')
                ->with('warning', 'No participants to notify (all have either not attended or already completed their evaluation).');
        }

        // Enable the form
        $event->enableEvaluationForm();

        // Send emails to eligible participants
        $this->sendNotificationEmails($event, $attendedParticipants);

        return redirect()
            ->route('admin.event-evaluation-forms.index')
            ->with('status', 'Evaluation form opened successfully. Emails sent to ' . $attendedParticipants->count() . ' participants.');
    }

    public function disable(Event $event): RedirectResponse
    {
        $event->disableEvaluationForm();

        return redirect()
            ->route('admin.event-evaluation-forms.index')
            ->with('status', 'Evaluation form closed successfully.');
    }

    public function loadDefaultForm(Event $event): RedirectResponse
    {
        if (Schema::hasColumn('evaluation_questions', 'event_id')) {
            $event->evaluationQuestions()->delete();
        }

        EvaluationQuestion::createDefaultFormForEvent($event);

        return redirect()
            ->back()
            ->with('status', 'Default evaluation form loaded for "' . $event->title . '".');
    }

    public function clear(Event $event): RedirectResponse
    {
        $hasEventIdColumn = Schema::hasColumn('evaluation_questions', 'event_id');

        if ($hasEventIdColumn) {
            $event->evaluationQuestions()->delete();
        } else {
            // If no event_id column, we can't clear specific event questions
            // This would require a different approach or migration
            return redirect()
                ->back()
                ->with('error', 'Cannot clear form: database schema needs update.');
        }

        return redirect()
            ->back()
            ->with('status', 'Evaluation form cleared for "' . $event->title . '".');
    }

    private function getQuestionsStats(string $eventType): array
    {
        return [
            'total' => \App\Models\EvaluationQuestion::forEventType($eventType)->participantQuestions()->count(),
            'active' => \App\Models\EvaluationQuestion::forEventType($eventType)->participantQuestions()->active()->count(),
            'guest' => \App\Models\EvaluationQuestion::forEventType($eventType)->guestQuestions()->active()->count(),
        ];
    }

    private function sendNotificationEmails(Event $event, $participants): void
    {
        foreach ($participants as $participant) {
            try {
                Mail::to($participant->email)->queue(new EvaluationFormEnabledMail($event, $participant));
                // 600ms delay = max ~1.6 emails/sec, safely under Resend's 2/sec limit
                usleep(600000);
            } catch (\Exception $e) {
                \Log::error('Evaluation form email failed for participant ' . $participant->id . ': ' . $e->getMessage());
                // Continue to next participant even if one fails
                continue;
            }
        }
    }
}
