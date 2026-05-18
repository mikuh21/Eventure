<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParticipantRequest;
use App\Http\Requests\UpdateParticipantRequest;
use App\Mail\ParticipantRegisteredMail;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ParticipantController extends Controller
{
    private function mobileDigitalIdUrl(string $token): string
    {
        $baseUrl = rtrim((string) config('app.public_url', config('app.url')), '/');

        return $baseUrl.'/p/'.rawurlencode($token);
    }

    public function index(Request $request, Event $event)
    {
        $wantsJson = $request->expectsJson() || $request->is('api/*');
        $eventsQuery = Event::query();
        
        // Event Staff can only see events they created
        if (auth()->user()->hasRole('event_staff')) {
            $eventsQuery->where('created_by', auth()->id());
        }
        
        $events = $eventsQuery->orderBy('start_date', 'asc')->get();

        $selectedEvent = null;
        $participants = collect();

        if ($request->filled('event_id')) {
            $selectedEvent = Event::find((int) $request->query('event_id'));

            // Event Staff can only view participants of events they created
            if ($selectedEvent && auth()->user()->hasRole('event_staff') && $selectedEvent->created_by !== auth()->id()) {
                if (!$wantsJson) {
                    return back()->with('error', 'You do not have access to this event\'s participants.');
                }
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            if ($selectedEvent) {
                $participants = Participant::where('event_id', $selectedEvent->id)
                    ->latest()
                    ->paginate(10)
                    ->appends($request->query());
            }
        }

        if ($wantsJson && ! $selectedEvent) {
            $selectedEvent = $event;
            $participants = $event->participants()
                ->latest()
                ->paginate(10)
                ->appends($request->query());
        }

        if (! $wantsJson) {
            return view('participants.index', [
                'event' => $event,
                'participants' => $participants,
                'events' => $events,
                'selectedEvent' => $selectedEvent,
            ]);
        }

        $payload = [
            'data' => $participants->items(),
            'meta' => [
                'current_page' => $participants->currentPage(),
                'per_page' => $participants->perPage(),
                'total' => $participants->total(),
                'last_page' => $participants->lastPage(),
                'filtered_event_id' => $selectedEvent?->id,
            ],
        ];

        return response()->json($payload);
    }

    public function create(Request $request, Event $event)
    {
        // Check permission: Event Staff can only add participants to their own events
        if (auth()->user()->hasRole('event_staff') && $event->created_by !== auth()->id()) {
            return $request->expectsJson() || $request->is('api/*')
                ? response()->json(['message' => 'Unauthorized'], 403)
                : back()->with('error', 'You do not have permission to add participants to this event.');
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Send name and email to register a participant for this event.',
                'event_id' => $event->id,
                'registration_open' => $event->isRegistrationOpen(),
            ]);
        }

        return view('participants.create', [
            'event' => $event,
        ]);
    }

    public function store(StoreParticipantRequest $request, Event $event)
    {
        // Check permission: Event Staff can only add participants to their own events
        if (auth()->user()->hasRole('event_staff') && $event->created_by !== auth()->id()) {
            return $request->expectsJson() || $request->is('api/*')
                ? response()->json(['message' => 'Unauthorized'], 403)
                : back()->with('error', 'You do not have permission to add participants to this event.');
        }

        $wantsJson = $request->expectsJson() || $request->is('api/*');
        $redirectTo = $request->input('redirect_to');

        if (! $event->isRegistrationOpen()) {
            $message = 'Registration is closed for this event.';

            if ($wantsJson) {
                return response()->json([
                    'message' => $message,
                ], 422);
            }

            if ($redirectTo) {
                return redirect($redirectTo)->withErrors(['registration' => $message]);
            }

            return redirect()
                ->route('events.participants.create', $event)
                ->withErrors(['registration' => $message]);
        }

        $participant = $event->participants()->create([
            ...$request->validated(),
            'attended' => false,
        ]);

        $mobileDigitalIdUrl = $this->mobileDigitalIdUrl($participant->digital_id_token);

        Mail::to($participant->email)->send(new ParticipantRegisteredMail(
            participant: $participant->fresh('event'),
            digitalIdUrl: $mobileDigitalIdUrl,
        ));

        if ($wantsJson) {
            return response()->json([
                'message' => "Registration successful. Participants' Digital ID is sent on their email.",
                'data' => $participant,
                'digital_id_url' => $mobileDigitalIdUrl,
            ], 201);
        }

        if ($redirectTo) {
            return redirect($redirectTo)
                ->with('status', "Registration successful. Participants' Digital ID is sent on their email.")
                ->with('participant_registered', [
                    'name' => $participant->name,
                    'email' => $participant->email,
                    'event' => $participant->event->title,
                    'digital_id_url' => $mobileDigitalIdUrl,
                ]);
        }

        return redirect()
            ->route('participants.confirmation.show', $participant)
            ->with('status', "Registration successful. Participants' Digital ID is sent on their email.");
    }

    public function show(Request $request, Event $event, Participant $participant)
    {
        $this->ensureParticipantBelongsToEvent($event, $participant);

        $wantsJson = $request->expectsJson() || $request->is('api/*');

        $participant = Participant::with(['event', 'evaluations'])
            ->withCount(['submissions', 'evaluations'])
            ->findOrFail($participant->id);

        if (! $participant->digital_id_token) {
            $participant->update([
                'digital_id_token' => Str::uuid()->toString(),
            ]);

            $participant->refresh();
        }

        if ($wantsJson) {
            return response()->json($participant);
        }

        return view('participants.show', [
            'event' => $event,
            'participant' => $participant,
        ]);
    }

    public function confirmation(Request $request, Participant $participant)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'participant_id' => $participant->id,
                'participant_name' => $participant->name,
                'event_name' => $participant->event->title,
                'digital_id_url' => $this->mobileDigitalIdUrl($participant->digital_id_token),
            ]);
        }

        return view('participants.confirmation', [
            'participant' => $participant,
        ]);
    }

    public function edit(Request $request, Event $event, Participant $participant)
    {
        // Check permission: Event Staff can only edit participants in their own events
        if (auth()->user()->hasRole('event_staff') && $event->created_by !== auth()->id()) {
            return $request->expectsJson() || $request->is('api/*')
                ? response()->json(['message' => 'Unauthorized'], 403)
                : back()->with('error', 'You do not have permission to modify participants in this event.');
        }

        $this->ensureParticipantBelongsToEvent($event, $participant);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json($participant);
        }

        return view('participants.edit', [
            'event' => $event,
            'participant' => $participant,
        ]);
    }

    public function update(UpdateParticipantRequest $request, Event $event, Participant $participant)
    {
        // Check permission: Event Staff can only update participants in their own events
        if (auth()->user()->hasRole('event_staff') && $event->created_by !== auth()->id()) {
            return $request->expectsJson() || $request->is('api/*')
                ? response()->json(['message' => 'Unauthorized'], 403)
                : back()->with('error', 'You do not have permission to modify participants in this event.');
        }

        $this->ensureParticipantBelongsToEvent($event, $participant);

        $wantsJson = $request->expectsJson() || $request->is('api/*');

        $participant->update($request->validated());

        if ($wantsJson) {
            return response()->json([
                'message' => 'Participant updated successfully.',
                'data' => $participant->fresh(),
            ]);
        }

        return redirect()
            ->route('events.participants.show', [$event, $participant])
            ->with('status', 'Participant updated successfully.');
    }

    public function destroy(Request $request, Event $event, Participant $participant)
    {
        // Check permission: Event Staff can only delete participants in their own events
        if (auth()->user()->hasRole('event_staff') && $event->created_by !== auth()->id()) {
            return $request->expectsJson() || $request->is('api/*')
                ? response()->json(['message' => 'Unauthorized'], 403)
                : back()->with('error', 'You do not have permission to modify participants in this event.');
        }

        $this->ensureParticipantBelongsToEvent($event, $participant);

        $wantsJson = $request->expectsJson() || $request->is('api/*');
        $redirectTo = $request->input('redirect_to');

        $participant->delete();

        if ($wantsJson) {
            return response()->json(null, 204);
        }

        if ($redirectTo) {
            return redirect($redirectTo)
                ->with('status', 'Participant deleted successfully.');
        }

        return redirect()
            ->route('events.participants.index', $event)
            ->with('status', 'Participant deleted successfully.');
    }

    public function resendDigitalIdEmail(Request $request, Event $event, Participant $participant)
    {
        $this->ensureParticipantBelongsToEvent($event, $participant);

        if (! $participant->digital_id_token) {
            $participant->update([
                'digital_id_token' => Str::uuid()->toString(),
            ]);

            $participant->refresh();
        }

        $digitalIdUrl = $this->mobileDigitalIdUrl($participant->digital_id_token);

        Mail::to($participant->email)->send(new ParticipantRegisteredMail(
            participant: $participant->loadMissing('event'),
            digitalIdUrl: $digitalIdUrl,
        ));

        $message = 'Digital ID email resent successfully.';

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => $message,
                'participant_id' => $participant->id,
                'email' => $participant->email,
                'digital_id_url' => $digitalIdUrl,
            ]);
        }

        return back()->with('status', $message);
    }

    private function ensureParticipantBelongsToEvent(Event $event, Participant $participant): void
    {
        abort_if($participant->event_id !== $event->id, 404);
    }
}
