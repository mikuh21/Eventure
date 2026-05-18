<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

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

        $guest = Guest::create($validated);

        return redirect()
            ->route('guests.index', ['event_id' => $guest->event_id])
            ->with('status', 'Guest added successfully.');
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

    private function guestModuleReady(): bool
    {
        return Schema::hasTable('guests') && Schema::hasTable('guest_evaluations');
    }

    private function emptyPaginator(): LengthAwarePaginator
    {
        return new LengthAwarePaginator(collect(), 0, 10, 1);
    }
}
