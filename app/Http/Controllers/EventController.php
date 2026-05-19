<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $wantsJson = $request->expectsJson() || $request->is('api/*');
        $search = trim((string) $request->string('search'));
        $typeFilter = trim((string) $request->string('type'));
        $registrationFilter = trim((string) $request->string('registration'));
        $now = now();

        $eventsQuery = Event::query();

        if ($search !== '') {
            $eventsQuery->where(function ($query) use ($search): void {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($typeFilter !== '') {
            $normalizedType = strtolower($typeFilter);

            if ($normalizedType === 'student_event') {
                $normalizedType = 'school_event';
            }

            $allowedTypes = ['school_event', 'conference'];
            if (in_array($normalizedType, $allowedTypes, true)) {
                $eventsQuery->where('type', $normalizedType);
            }
        }

        if ($registrationFilter !== '') {
            $normalizedRegistration = strtolower($registrationFilter);

            if (in_array($normalizedRegistration, ['open', 'closed'], true)) {
                $eventsQuery->where(function ($query) use ($normalizedRegistration, $now): void {
                    $query->where(function ($windowQuery) use ($normalizedRegistration, $now): void {
                        $windowQuery->whereNotNull('start_registration')
                            ->whereNotNull('end_registration');

                        if ($normalizedRegistration === 'open') {
                            $windowQuery->where('start_registration', '<=', $now)
                                ->where('end_registration', '>=', $now);
                        } else {
                            $windowQuery->where(function ($closedWindowQuery) use ($now): void {
                                $closedWindowQuery->where('start_registration', '>', $now)
                                    ->orWhere('end_registration', '<', $now);
                            });
                        }
                    })->orWhere(function ($legacyQuery) use ($normalizedRegistration, $now): void {
                        $legacyQuery->where(function ($missingWindowQuery): void {
                            $missingWindowQuery->whereNull('start_registration')
                                ->orWhereNull('end_registration');
                        });

                        if ($normalizedRegistration === 'open') {
                            $legacyQuery->whereDate('start_date', '>=', $now->toDateString());
                        } else {
                            $legacyQuery->whereDate('start_date', '<', $now->toDateString());
                        }
                    });
                });
            }
        }

        $events = $eventsQuery
            ->orderBy('start_date', 'asc')
            ->paginate(10);

        $overview = [
            'total_events' => Event::count(),
            'student_events' => Event::query()->where('type', 'standard')->count(),
            'conference_events' => Event::query()->where('type', 'conference')->count(),
        ];

        if (! $wantsJson) {
            return view('events.index', [
                'events' => $events,
                'overview' => $overview,
            ]);
        }

        $payload = [
            'data' => collect($events->items())->map(function (Event $event): array {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'type' => $event->type,
                    'attendance_type' => $event->attendance_type,
                    'event_title' => $event->event_title,
                    'conference_title' => $event->conference_title,
                    'theme' => $event->theme,
                    'description' => $event->description,
                    'start_date' => $event->start_date,
                    'end_date' => $event->end_date,
                    'start_registration' => $event->start_registration,
                    'end_registration' => $event->end_registration,
                    'registration_open' => $event->isRegistrationOpen(),
                    'location' => $event->location,
                    'poster_url' => $event->poster_path ? 'https://sesmcvjwmkphgkzawewn.supabase.co/storage/v1/object/public/event-posters/' . $event->poster_path : null,
                    'template_download_url' => $event->template_file_path ? route('events.template.download', $event) : null,
                    'keywords' => $event->keywords,
                    'created_at' => $event->created_at,
                    'updated_at' => $event->updated_at,
                    'average_rating' => $event->averageRating(),
                ];
            })->values()->all(),
            'meta' => [
                'current_page' => $events->currentPage(),
                'per_page' => $events->perPage(),
                'total' => $events->total(),
                'last_page' => $events->lastPage(),
            ],
            'overview' => $overview,
        ];

        return response()->json($payload);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Send event type and matching title fields, registration window, location, and optional poster/template.',
            ]);
        }

        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $wantsJson = $request->expectsJson() || $request->is('api/*');

        $validated = $request->validated();

        $data = $this->buildEventData($validated, $request);
        // Set created_by to current user
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        $event = Event::create($data);

        if ($wantsJson) {
            return response()->json([
                'message' => 'Event created successfully.',
                'data' => $event,
            ], 201);
        }

        return redirect()
            ->route('events.show', $event)
            ->with('status', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Event $event)
    {
        $wantsJson = $request->expectsJson() || $request->is('api/*');

        $event->loadCount('participants');
        $event->setAttribute('average_rating', $event->averageRating());
        $event->setAttribute('registration_open', $event->isRegistrationOpen());
        $event->setAttribute('template_download_url', $event->template_file_path ? route('events.template.download', $event) : null);
        $event->setAttribute('poster_url', $event->poster_path ? 'https://sesmcvjwmkphgkzawewn.supabase.co/storage/v1/object/public/event-posters/' . $event->poster_path : null);

        if (! $wantsJson) {
            $participants = $event->participants()->latest()->paginate(10);

            return view('events.show', [
                'event' => $event,
                'participants' => $participants,
            ]);
        }

        return response()->json($event);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Event $event)
    {
        // Check permission: Event Staff can only edit their own events
        if (auth()->user()->hasRole('event_staff') && $event->created_by !== auth()->id()) {
            return $request->expectsJson() || $request->is('api/*')
                ? response()->json(['message' => 'Unauthorized'], 403)
                : back()->with('error', 'You do not have permission to modify this event.');
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json($event);
        }

        return view('events.edit', [
            'event' => $event,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        // Check permission: Event Staff can only update their own events
        if (auth()->user()->hasRole('event_staff') && $event->created_by !== auth()->id()) {
            return $request->expectsJson() || $request->is('api/*')
                ? response()->json(['message' => 'Unauthorized'], 403)
                : back()->with('error', 'You do not have permission to modify this event.');
        }

        $wantsJson = $request->expectsJson() || $request->is('api/*');

        $validated = $request->validated();

        // Set updated_by when updating
        $data = $this->buildEventData($validated, $request, $event);
        $data['updated_by'] = auth()->id();

        $event->update($data);

        if ($wantsJson) {
            return response()->json([
                'message' => 'Event updated successfully.',
                'data' => $event->fresh(),
            ]);
        }

        return redirect()
            ->route('events.show', $event)
            ->with('status', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Event $event)
    {
        // Check permission: Event Staff can only delete their own events
        if (auth()->user()->hasRole('event_staff') && $event->created_by !== auth()->id()) {
            return $request->expectsJson() || $request->is('api/*')
                ? response()->json(['message' => 'Unauthorized'], 403)
                : back()->with('error', 'You do not have permission to modify this event.');
        }

        $wantsJson = $request->expectsJson() || $request->is('api/*');

        if ($event->poster_path) {
            Storage::disk('event-posters')->delete($event->poster_path);
        }

        if ($event->template_file_path) {
            Storage::disk('event-templates')->delete($event->template_file_path);
        }

        $event->delete();

        if ($wantsJson) {
            return response()->json(null, 204);
        }

        return redirect()
            ->route('events.index')
            ->with('status', 'Event deleted successfully.');
    }

    public function submissions(Request $request, Event $event)
    {
        $wantsJson = $request->expectsJson() || $request->is('api/*');

        $submissions = $event->submissions()
            ->with('participant:id,name,event_id')
            ->latest()
            ->paginate(10);

        if ($wantsJson) {
            return response()->json([
                'data' => $submissions->items(),
                'meta' => [
                    'current_page' => $submissions->currentPage(),
                    'per_page' => $submissions->perPage(),
                    'total' => $submissions->total(),
                    'last_page' => $submissions->lastPage(),
                ],
            ]);
        }

        return view('submissions.event-index', [
            'event' => $event,
            'submissions' => $submissions,
        ]);
    }

    public function downloadTemplate(Event $event)
    {
        abort_if($event->type !== 'conference', 404);
        abort_if(! $event->template_file_path, 404, 'No template file uploaded for this conference event.');

        $extension = pathinfo($event->template_file_path, PATHINFO_EXTENSION);
        return Storage::disk('event-templates')->download($event->template_file_path, 'Conference-Paper-Template.' . $extension);
    }

    private function buildEventData(array $validated, Request $request, ?Event $event = null): array
    {
        $data = [
            'type' => $validated['type'] ?? $event?->type ?? 'school_event',
            'attendance_type' => $validated['attendance_type'] ?? $event?->attendance_type ?? 'face_to_face',
            'event_title' => $validated['event_title'] ?? $event?->event_title,
            'conference_title' => $validated['conference_title'] ?? $event?->conference_title,
            'theme' => $validated['theme'] ?? $event?->theme,
            'description' => $validated['description'] ?? $event?->description,
            'department' => $validated['department'] ?? $event?->department,
            'program' => $validated['program'] ?? $event?->program,
            'start_date' => $validated['start_date'] ?? $event?->start_date,
            'end_date' => $validated['end_date'] ?? $event?->end_date,
            'start_registration' => $validated['start_registration'] ?? $event?->start_registration,
            'end_registration' => $validated['end_registration'] ?? $event?->end_registration,
            'location' => $validated['location'] ?? $event?->location,
            'keywords' => $this->normalizeKeywords($validated['keywords'] ?? $event?->keywords),
        ];

        if ($data['type'] === 'school_event') {
            $data['conference_title'] = null;
            $data['theme'] = null;

            if (! $data['event_title']) {
                $data['event_title'] = $event?->event_title;
            }
        }

        if ($data['type'] === 'conference') {
            $data['event_title'] = null;

            if (! $data['conference_title']) {
                $data['conference_title'] = $event?->conference_title;
            }
        }

        if ($data['type'] === 'school_event' && ! $data['event_title']) {
            throw ValidationException::withMessages([
                'event_title' => 'The school event title field is required.',
            ]);
        }

        if ($data['type'] === 'conference' && ! $data['conference_title']) {
            throw ValidationException::withMessages([
                'conference_title' => 'The conference title field is required for conference events.',
            ]);
        }

        if ($request->hasFile('poster')) {
            if ($event?->poster_path) {
                Storage::disk('event-posters')->delete($event->poster_path);
            }

            $filename = Str::random(40) . '.' . $request->file('poster')->getClientOriginalExtension();
            Storage::disk('event-posters')->putFileAs('', $request->file('poster'), $filename);
            $data['poster_path'] = $filename;
        }

        if ($request->hasFile('template_file')) {
            if ($event?->template_file_path) {
                Storage::disk('event-templates')->delete($event->template_file_path);
            }

            $filename = Str::random(40) . '.' . $request->file('template_file')->getClientOriginalExtension();
            Storage::disk('event-templates')->putFileAs('', $request->file('template_file'), $filename);
            $data['template_file_path'] = $filename;
        }

        if ($data['type'] !== 'conference') {
            if ($event?->template_file_path) {
                Storage::disk('s3')->delete($event->template_file_path);
            }

            $data['template_file_path'] = null;
            $data['keywords'] = null;
        }

        return $data;
    }

    private function normalizeKeywords(mixed $keywords): ?array
    {
        if ($keywords === null || $keywords === '') {
            return null;
        }

        if (is_array($keywords)) {
            return array_values(array_filter(array_map(
                static fn ($value): string => trim((string) $value),
                $keywords
            )));
        }

        if (is_string($keywords)) {
            $parts = array_map('trim', explode(',', $keywords));

            return array_values(array_filter($parts));
        }

        return null;
    }
}
