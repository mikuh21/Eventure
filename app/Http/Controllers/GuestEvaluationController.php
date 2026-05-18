<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\GuestEvaluation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GuestEvaluationController extends Controller
{
    public function index(Guest $guest)
    {
        $evaluations = $guest->evaluations()->latest()->paginate(10);

        return view('guest_evaluations.index', [
            'guest' => $guest,
            'evaluations' => $evaluations,
        ]);
    }

    public function create(Guest $guest)
    {
        return view('guest_evaluations.create', [
            'guest' => $guest,
        ]);
    }

    public function store(Request $request, Guest $guest): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'feedback' => ['nullable', 'string'],
        ]);

        $guest->evaluations()->create($validated);

        return redirect()
            ->route('guests.evaluations.index', $guest)
            ->with('status', 'Guest evaluation saved successfully.');
    }

    public function show(Guest $guest, GuestEvaluation $evaluation)
    {
        $this->ensureEvaluationBelongsToGuest($guest, $evaluation);

        return view('guest_evaluations.show', [
            'guest' => $guest,
            'evaluation' => $evaluation,
        ]);
    }

    public function edit(Guest $guest, GuestEvaluation $evaluation)
    {
        $this->ensureEvaluationBelongsToGuest($guest, $evaluation);

        return view('guest_evaluations.edit', [
            'guest' => $guest,
            'evaluation' => $evaluation,
        ]);
    }

    public function update(Request $request, Guest $guest, GuestEvaluation $evaluation): RedirectResponse
    {
        $this->ensureEvaluationBelongsToGuest($guest, $evaluation);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'feedback' => ['nullable', 'string'],
        ]);

        $evaluation->update($validated);

        return redirect()
            ->route('guests.evaluations.show', [$guest, $evaluation])
            ->with('status', 'Guest evaluation updated successfully.');
    }

    public function destroy(Guest $guest, GuestEvaluation $evaluation): RedirectResponse
    {
        $this->ensureEvaluationBelongsToGuest($guest, $evaluation);

        $evaluation->delete();

        return redirect()
            ->route('guests.evaluations.index', $guest)
            ->with('status', 'Guest evaluation deleted successfully.');
    }

    private function ensureEvaluationBelongsToGuest(Guest $guest, GuestEvaluation $evaluation): void
    {
        abort_if($evaluation->guest_id !== $guest->id, 404);
    }
}
