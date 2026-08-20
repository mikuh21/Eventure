<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParticipantRequest;
use App\Http\Requests\UpdateParticipantRequest;
use App\Mail\ParticipantRegisteredMail;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ParticipantController extends Controller
{
    private function mobileDigitalIdUrl(string $token): string
    {
        $baseUrl = rtrim((string) config('app.public_url', config('app.url')), '/');

        return $baseUrl.'/p/'.rawurlencode($token);
    }

    public function index(Request $request, ?Event $event = null)
    {
        $wantsJson = $request->expectsJson() || $request->is('api/*');
        $search = trim((string) $request->string('search'));
        $attendanceFilter = trim((string) $request->string('attendance'));
        $participantTypeFilter = trim((string) $request->string('participant_type'));
        $eventsQuery = Event::query();
        
        // Event Staff can only see events they created
        if (auth()->check() && auth()->user()->hasRole('event_staff')) {
            $eventsQuery->where('created_by', auth()->id());
        }
        
        $events = $eventsQuery->get()->sort(function (Event $a, Event $b) {
            if ($a->filterSortPriority() !== $b->filterSortPriority()) {
                return $a->filterSortPriority() <=> $b->filterSortPriority();
            }

            return match ($a->filterSortPriority()) {
                1 => $b->start_date <=> $a->start_date,
                2 => $a->start_date <=> $b->start_date,
                default => $b->start_date <=> $a->start_date,
            };
        })->values();

        $selectedEvent = null;
        $participants = collect();

        if ($request->has('event_id') || $event) {
            $selectedEvent = $request->has('event_id')
                ? Event::find((int) $request->query('event_id'))
                : $event;

            // Event Staff can only view participants of events they created
            if ($selectedEvent && auth()->check() && auth()->user()->hasRole('event_staff') && $selectedEvent->created_by !== auth()->id()) {
                if (!$wantsJson) {
                    return back()->with('error', 'You do not have access to this event\'s participants.');
                }
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            if ($selectedEvent) {
                Participant::where('event_id', $selectedEvent->id)
                    ->whereNull('digital_id_token')
                    ->cursor()
                    ->each(function (Participant $participant): void {
                        $participant->update([
                            'digital_id_token' => Str::uuid()->toString(),
                        ]);
                    });

                $participantsQuery = Participant::query()
                    ->where('event_id', $selectedEvent->id);

                if ($search !== '') {
                    $participantsQuery->where(function ($query) use ($search): void {
                        $query->where('name', 'ilike', "%{$search}%")
                            ->orWhere('email', 'ilike', "%{$search}%");
                    });
                }

                if ($attendanceFilter !== '') {
                    $normalizedAttendance = strtolower($attendanceFilter);

                    if (in_array($normalizedAttendance, ['attended', 'not_attended'], true)) {
                        $participantsQuery->where('attended', $normalizedAttendance === 'attended');
                    }
                }

                if ($participantTypeFilter !== '') {
                    $normalizedParticipantType = strtolower($participantTypeFilter);

                    if (in_array($normalizedParticipantType, ['faculty', 'student'], true)) {
                        $participantsQuery->where('participant_type', $normalizedParticipantType);
                    }
                }

                $participants = $participantsQuery
                    ->latest()
                    ->paginate(10)
                    ->withQueryString();
            }
        }

        if ($wantsJson && ! $selectedEvent && $event) {
            $selectedEvent = $event;
            $participantsQuery = $event->participants();

            if ($search !== '') {
                $participantsQuery->where(function ($query) use ($search): void {
                    $query->where('name', 'ilike', "%{$search}%")
                        ->orWhere('email', 'ilike', "%{$search}%");
                });
            }

            if ($attendanceFilter !== '') {
                $normalizedAttendance = strtolower($attendanceFilter);

                if (in_array($normalizedAttendance, ['attended', 'not_attended'], true)) {
                    $participantsQuery->where('attended', $normalizedAttendance === 'attended');
                }
            }

            if ($participantTypeFilter !== '') {
                $normalizedParticipantType = strtolower($participantTypeFilter);

                if (in_array($normalizedParticipantType, ['faculty', 'student'], true)) {
                    $participantsQuery->where('participant_type', $normalizedParticipantType);
                }
            }

            $participants = $participantsQuery
                ->latest()
                ->get();
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
            'data' => $participants->values()->all(),
            'meta' => [
                'current_page' => 1,
                'per_page' => $participants->count(),
                'total' => $participants->count(),
                'last_page' => 1,
                'filtered_event_id' => $selectedEvent?->id,
            ],
        ];

        return response()->json($payload);
    }

    public function create(Request $request, Event $event)
    {
        // Check permission: Event Staff can only add participants to their own events
        if (auth()->check() && auth()->user()->hasRole('event_staff') && $event->created_by !== auth()->id()) {
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
        if (auth()->check() && auth()->user()->hasRole('event_staff') && $event->created_by !== auth()->id()) {
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

        $validatedData = $request->validated();
        $photoPath = $this->handlePhotoStorage($request);

        $participant = $event->participants()->create([
            ...$validatedData,
            'photo_path' => $photoPath,
            'attended' => false,
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        if (! $participant->digital_id_token) {
            $participant->update([
                'digital_id_token' => Str::uuid()->toString(),
            ]);
        }

        $digitalIdUrl = $this->mobileDigitalIdUrl($participant->digital_id_token);
        Mail::to($participant->email)->send(new ParticipantRegisteredMail(
            participant: $participant->loadMissing('event'),
            digitalIdUrl: $digitalIdUrl,
        ));

        if ($wantsJson) {
            return response()->json([
                'message' => 'Registration successful and digital ID email sent.',
                'data' => $participant,
            ], 201);
        }

        if ($redirectTo) {
            $isAdminOrStaff = auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('event_staff'));
            return redirect($redirectTo)
                ->with('status', 'Registration successful and digital ID email sent.')
                ->with('participant_registered', [
                    'name' => $participant->name,
                    'email' => $participant->email,
                    'participant_type' => $participant->participant_type,
                    'institution' => $participant->institution,
                    'college' => $participant->college,
                    'event' => $participant->event->title,
                    'registered_by_admin' => $isAdminOrStaff,
                ]);
        }

        return redirect()
            ->route('participants.confirmation.show', $participant)
            ->with('status', 'Registration successful and digital ID email sent.');
    }

    public function publicStore(Request $request)
    {
        $validated = $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'name' => ['required', 'string', 'max:255'],
            'participant_type' => ['required', Rule::in(['faculty', 'student'])],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('participants', 'email')->where(fn ($query) => $query->where('event_id', $request->input('event_id'))),
            ],
            'institution' => ['required', 'string', 'max:255'],
            'college' => ['nullable', Rule::in(['SACE', 'SABM', 'SAHS', 'SHS', 'N/A'])],
                    'photo' => ['nullable', 'image', 'max:5120'],
        ], [
            'email.unique' => 'Email is already registered for this event.',
        ]);

        $event = Event::findOrFail($validated['event_id']);

        if (! $event->isRegistrationOpen()) {
            return response()->json([
                'message' => 'Registration is closed for this event.',
            ], 422);
        }

        $participant = $event->participants()->create([
            ...$validated,
            'status' => 'pending',
            'attended' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registration submitted! You will receive your Digital ID via email once your registration is approved.',
            'data' => $participant,
        ], 201);
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
        // Load participant with event relationship
        $participant = Participant::with('event')->findOrFail($participant->id);

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
        if (auth()->check() && auth()->user()->hasRole('event_staff') && $event->created_by !== auth()->id()) {
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

    public function update(\Illuminate\Http\Request $request, Event $event, Participant $participant)
    {
        // Check permission: Event Staff can only update participants in their own events
        if (auth()->check() && auth()->user()->hasRole('event_staff') && $event->created_by !== auth()->id()) {
            return $request->expectsJson() || $request->is('api/*')
                ? response()->json(['message' => 'Unauthorized'], 403)
                : back()->with('error', 'You do not have permission to modify participants in this event.');
        }

        $this->ensureParticipantBelongsToEvent($event, $participant);

        $wantsJson = $request->expectsJson() || $request->is('api/*');

        // Attendance-only toggle from JS
        if ($wantsJson && $request->has('attended') && !$request->has('name')) {
            $participant->update(['attended' => filter_var($request->input('attended'), FILTER_VALIDATE_BOOLEAN)]);
            return response()->json([
                'message' => 'Attendance updated successfully.',
                'data' => $participant->fresh(),
                'attended' => $participant->fresh()->attended,
            ]);
        }

        // Full update — validate manually
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'participant_type' => ['required', \Illuminate\Validation\Rule::in(['faculty', 'student'])],
            'email' => [
                'required', 'string', 'email', 'max:255',
                \Illuminate\Validation\Rule::unique('participants', 'email')
                    ->where(fn ($q) => $q->where('event_id', $event->id))
                    ->ignore($participant->id),
            ],
            'institution' => ['required', 'string', 'max:255'],
            'attended' => ['boolean'],
        ]);
        $participant->update($validated);

        if ($wantsJson) {
            return response()->json([
                'message' => 'Participant updated successfully.',
                'data' => $participant->fresh(),
                'attended' => $participant->attended,
            ]);
        }

        return redirect()
            ->route('events.participants.show', [$event, $participant])
            ->with('status', 'Participant updated successfully.');
    }

    public function bulkAttendance(Request $request, Event $event)
    {
        if (auth()->user()->hasRole('event_staff') && $event->created_by !== auth()->id()) {
            return response()->json(['message' => 'You do not have permission to modify participants in this event.'], 403);
        }

        $validated = $request->validate([
            'participant_ids' => ['nullable', 'array', 'required_without:select_all'],
            'participant_ids.*' => ['integer', 'distinct'],
            'select_all' => ['sometimes', 'boolean'],
            'search' => ['nullable', 'string', 'max:255'],
            'attendance' => ['nullable', Rule::in(['attended', 'not_attended'])],
            'participant_type' => ['nullable', Rule::in(['faculty', 'student'])],
        ]);

        $selectAll = (bool) ($validated['select_all'] ?? false);
        $participantQuery = Participant::query()->where('event_id', $event->id);

        if ($selectAll) {
            $search = trim((string) ($validated['search'] ?? ''));
            if ($search !== '') {
                $participantQuery->where(function ($query) use ($search): void {
                    $query->where('name', 'ilike', "%{$search}%")
                        ->orWhere('email', 'ilike', "%{$search}%");
                });
            }

            if (($validated['attendance'] ?? '') !== '') {
                $participantQuery->where('attended', $validated['attendance'] === 'attended');
            }

            if (($validated['participant_type'] ?? '') !== '') {
                $participantQuery->where('participant_type', $validated['participant_type']);
            }
        } else {
            $participantIds = $validated['participant_ids'] ?? [];
            $matchingIds = (clone $participantQuery)->whereIn('id', $participantIds)->pluck('id');

            if ($matchingIds->count() !== count($participantIds)) {
                return response()->json(['message' => 'One or more selected participants do not belong to this event.'], 422);
            }

            $participantQuery->whereIn('id', $participantIds);
        }

        $selectedCount = (clone $participantQuery)->count();
        $alreadyAttendedCount = (clone $participantQuery)->where('attended', true)->count();

        DB::transaction(function () use ($participantQuery): void {
            $participantQuery->where('attended', false)->update(['attended' => true]);
        });

        $updatedCount = $selectedCount - $alreadyAttendedCount;

        return response()->json([
            'message' => $updatedCount > 0
                ? "{$updatedCount} participant(s) marked as attended."
                : 'All selected participants were already marked as attended.',
            'selected_count' => $selectedCount,
            'updated_count' => $updatedCount,
            'already_attended_count' => $alreadyAttendedCount,
        ]);
    }

    public function destroy(Request $request, Event $event, Participant $participant)
    {
        // Check permission: Event Staff can only delete participants in their own events
        if (auth()->check() && auth()->user()->hasRole('event_staff') && $event->created_by !== auth()->id()) {
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

    public function approve(Request $request, Event $event, Participant $participant)
    {
        $this->ensureParticipantBelongsToEvent($event, $participant);

        // Authorization: admin or event_staff who owns the event
        if (! auth()->check() || (! auth()->user()->hasRole('admin') && (! auth()->user()->hasRole('event_staff') || $event->created_by !== auth()->id()))) {
            return $request->expectsJson() || $request->is('api/*')
                ? response()->json(['message' => 'Unauthorized'], 403)
                : back()->with('error', 'You do not have permission to approve this participant.');
        }

        $participant->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

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

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Participant approved and digital ID email sent.',
                'participant' => [
                    'id' => $participant->id,
                    'name' => $participant->name,
                    'digital_id_token' => $participant->digital_id_token,
                    'qr_url' => route('participants.digital-id.show', $participant, false),
                    'resend_url' => route('events.participants.resend-digital-id', [$event, $participant]),
                ],
            ]);
        }

        return back()->with('status', 'Participant approved and digital ID email sent.');
    }

    public function deny(Request $request, Event $event, Participant $participant)
    {
        $this->ensureParticipantBelongsToEvent($event, $participant);

        // Authorization: admin or event_staff who owns the event
        if (! auth()->check() || (! auth()->user()->hasRole('admin') && (! auth()->user()->hasRole('event_staff') || $event->created_by !== auth()->id()))) {
            return $request->expectsJson() || $request->is('api/*')
                ? response()->json(['message' => 'Unauthorized'], 403)
                : back()->with('error', 'You do not have permission to deny this participant.');
        }

        $participant->delete();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Participant denied and removed.']);
        }

        return back()->with('status', 'Participant denied and removed.');
    }

    private function ensureParticipantBelongsToEvent(Event $event, Participant $participant): void
    {
        abort_if($participant->event_id !== $event->id, 404);
    }

    private function handlePhotoStorage(Request $request): ?string
    {
        if (!$request->hasFile('photo')) {
            return null;
        }

        $filename = Str::random(40) . '.' . $request->file('photo')->getClientOriginalExtension();
        Storage::disk('participant-photos')->putFileAs('', $request->file('photo'), $filename);
        return $filename;
    }
}
