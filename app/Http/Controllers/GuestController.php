<?php

namespace App\Http\Controllers;

use App\Mail\GuestDigitalIdMail;
use App\Models\Event;
use App\Models\Guest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $eventsQuery = Event::query();
        
        // Event Staff can only see events they created
        if (auth()->user()->hasRole('event_staff')) {
            $eventsQuery->where('created_by', auth()->id());
        }
        
        $events = $eventsQuery->orderBy('start_date', 'asc')->get();
        $upcomingEvents = $events->filter(fn (Event $item) => ! $item->hasEnded())->values();

        $selectedEvent = $request->filled('event_id')
            ? Event::find((int) $request->query('event_id'))
            : null;

        // Event Staff can only view guests of events they created
        if ($selectedEvent && auth()->user()->hasRole('event_staff') && $selectedEvent->created_by !== auth()->id()) {
            return back()->with('error', 'You do not have access to this event\'s guests.');
        }

        if (! $this->guestModuleReady()) {
            return view('guests.index', [
                'events' => $events,
                'upcomingEvents' => $upcomingEvents,
                'selectedEvent' => null,
                'guests' => $this->emptyPaginator(),
                'guestModuleReady' => false,
            ]);
        }

        $selectedEvent = $request->filled('event_id')
            ? Event::find((int) $request->query('event_id'))
            : null;

        $guests = $selectedEvent
            ? Guest::query()
                ->where('event_id', $selectedEvent->id)
                ->latest()
                ->paginate(10)
                ->appends($request->query())
            : Guest::query()->whereRaw('1 = 0')->paginate(10);

        return view('guests.index', [
            'events' => $events,
            'upcomingEvents' => $upcomingEvents,
            'selectedEvent' => $selectedEvent,
            'guests' => $guests,
            'guestModuleReady' => true,
        ]);
    }

    public function create(Request $request)
    {
        if (! $this->guestModuleReady()) {
            return redirect()
                ->route('guests.index')
                ->withErrors(['guests' => 'Guest module tables are not ready yet. Please run migrations first.']);
        }

        return redirect()->route('guests.index', array_filter([
            'event_id' => $request->query('event_id'),
            'open_add_guest' => 1,
        ]));
    }

    public function publicStore(Request $request)
    {
        $validated = $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'bio' => ['nullable', 'string'],
            'conference_paper' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);

        $event = Event::findOrFail($validated['event_id']);

        if (! $event->isRegistrationOpen()) {
            return response()->json([
                'message' => 'Registration is closed for this event.',
            ], 422);
        }

        $validated['role'] = $event->type === 'conference' ? 'Presenter' : 'Exhibitor';
        $validated['status'] = 'pending';

        if ($request->hasFile('conference_paper')) {
            $originalFileName = $request->file('conference_paper')->getClientOriginalName();
            $validated['conference_paper_path'] = $request->file('conference_paper')
                ->store('conference_papers', 'event-templates');
            $validated['conference_paper_original_name'] = $originalFileName;
        }

        $guest = Guest::create($validated);

        return response()->json([
            'message' => 'Registration submitted! You will receive your Digital ID via email once your registration is approved.',
            'data' => $guest,
        ], 201);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! $this->guestModuleReady()) {
            return redirect()
                ->route('guests.index')
                ->withErrors(['guests' => 'Guest module tables are not ready yet. Please run migrations first.']);
        }

        $validated = $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'string', 'max:100'],
            'bio' => ['nullable', 'string'],
        ]);

        // Check permission: Event Staff can only add guests to their own events
        $event = Event::find($validated['event_id']);
        if (auth()->user()->hasRole('event_staff') && $event->created_by !== auth()->id()) {
            return redirect()
                ->route('guests.index', ['event_id' => $validated['event_id']])
                ->with('error', 'You do not have permission to add guests to this event.');
        }

        // Set role server-side based on event type
        $validated['role'] = $event->type === 'conference' ? 'Presenter' : 'Exhibitor';

        $validated['status'] = 'pending';

        $guest = Guest::create($validated);

        return redirect()
            ->route('guests.index', ['event_id' => $guest->event_id])
            ->with('status', 'Guest registration submitted successfully. Pending approval is required before a digital ID is issued.');
    }

    public function show(Guest $guest)
    {
        if (! $this->guestModuleReady()) {
            return redirect()
                ->route('guests.index')
                ->withErrors(['guests' => 'Guest module tables are not ready yet. Please run migrations first.']);
        }

        $guest->load('event');
        $guest->loadCount('evaluations');

        return view('guests.show', [
            'guest' => $guest,
            'averageRating' => $guest->averageRating(),
        ]);
    }

    public function downloadPaper(Guest $guest)
    {
        if (!$guest->conference_paper_path) {
            abort(404);
        }

        $originalName = $guest->conference_paper_original_name ?? basename($guest->conference_paper_path);
        $disk = Storage::disk('event-templates');

        if (!$disk->exists($guest->conference_paper_path)) {
            abort(404, 'File not found.');
        }

        try {
            $url = $disk->temporaryUrl(
                $guest->conference_paper_path,
                now()->addMinutes(5),
                [
                    'ResponseContentDisposition' => 'attachment; filename="' . $originalName . '"',
                ]
            );
            return redirect($url);
        } catch (\Exception $e) {
            return $disk->download($guest->conference_paper_path, $originalName);
        }
    }

    public function edit(Guest $guest)
    {
        if (! $this->guestModuleReady()) {
            return redirect()
                ->route('guests.index')
                ->withErrors(['guests' => 'Guest module tables are not ready yet. Please run migrations first.']);
        }

        $events = Event::query()->orderBy('start_date', 'asc')->get();

        return view('guests.edit', [
            'guest' => $guest,
            'events' => $events,
        ]);
    }

    public function update(Request $request, Guest $guest): RedirectResponse
    {
        if (! $this->guestModuleReady()) {
            return redirect()
                ->route('guests.index')
                ->withErrors(['guests' => 'Guest module tables are not ready yet. Please run migrations first.']);
        }

        // Check permission: Event Staff can only update guests in their own events
        $event = Event::find($guest->event_id);
        if (auth()->user()->hasRole('event_staff') && $event->created_by !== auth()->id()) {
            return redirect()
                ->route('guests.index', ['event_id' => $guest->event_id])
                ->with('error', 'You do not have permission to modify guests in this event.');
        }

        $validated = $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'string', 'max:100'],
            'bio' => ['nullable', 'string'],
        ]);

        $guest->update($validated);

        return redirect()
            ->route('guests.show', $guest)
            ->with('status', 'Guest updated successfully.');
    }

    public function destroy(Guest $guest): RedirectResponse
    {
        if (! $this->guestModuleReady()) {
            return redirect()
                ->route('guests.index')
                ->withErrors(['guests' => 'Guest module tables are not ready yet. Please run migrations first.']);
        }

        // Check permission: Event Staff can only delete guests in their own events
        $event = Event::find($guest->event_id);
        if (auth()->user()->hasRole('event_staff') && $event->created_by !== auth()->id()) {
            return back()->with('error', 'You do not have permission to modify guests in this event.');
        }

        $eventId = $guest->event_id;
        $guest->delete();

        return redirect()
            ->route('guests.index', ['event_id' => $eventId])
            ->with('status', 'Guest deleted successfully.');
    }

    public function approve(Request $request, Event $event, Guest $guest)
    {
        if (! $this->guestModuleReady()) {
            return back()->withErrors(['guests' => 'Guest module tables are not ready yet.']);
        }

        // Check event consistency
        abort_if($guest->event_id !== $event->id, 404);

        // Authorization: admin or event_staff who owns the event
        if (! auth()->check() || (! auth()->user()->hasRole('admin') && (! auth()->user()->hasRole('event_staff') || $event->created_by !== auth()->id()))) {
            return $request->expectsJson() || $request->is('api/*')
                ? response()->json(['message' => 'Unauthorized'], 403)
                : back()->with('error', 'You do not have permission to approve this guest.');
        }

        $guest->update([
            'status' => 'approved',
        ]);

        if (! $guest->digital_token) {
            $guest->update(['digital_token' => Str::uuid()->toString()]);
            $guest->refresh();
        }

        Mail::to($guest->email)->send(new GuestDigitalIdMail($guest->loadMissing('event')));

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Guest approved and digital ID email sent.']);
        }

        return back()->with('status', 'Guest approved and digital ID email sent.');
    }

    public function deny(Request $request, Event $event, Guest $guest)
    {
        if (! $this->guestModuleReady()) {
            return back()->withErrors(['guests' => 'Guest module tables are not ready yet.']);
        }

        // Check event consistency
        abort_if($guest->event_id !== $event->id, 404);

        // Authorization: admin or event_staff who owns the event
        if (! auth()->check() || (! auth()->user()->hasRole('admin') && (! auth()->user()->hasRole('event_staff') || $event->created_by !== auth()->id()))) {
            return $request->expectsJson() || $request->is('api/*')
                ? response()->json(['message' => 'Unauthorized'], 403)
                : back()->with('error', 'You do not have permission to deny this guest.');
        }

        $guest->delete();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Guest denied and removed.']);
        }

        return back()->with('status', 'Guest denied and removed.');
    }

    private function guestModuleReady(): bool
    {
        return Schema::hasTable('guests') && Schema::hasTable('guest_evaluations');
    }

    private function emptyPaginator(): LengthAwarePaginator
    {
        return new LengthAwarePaginator(collect(), 0, 10, 1);
    }
}
