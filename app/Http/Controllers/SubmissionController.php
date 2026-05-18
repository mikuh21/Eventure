<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubmissionRequest;
use App\Http\Requests\UpdateSubmissionRequest;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function index(Request $request, Participant $participant)
    {
        $wantsJson = $request->expectsJson() || $request->is('api/*');

        $submissions = $participant->submissions()->latest()->paginate(10);

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

        return view('submissions.index', [
            'participant' => $participant,
            'submissions' => $submissions,
        ]);
    }

    public function create(Request $request, Participant $participant)
    {
        $event = $participant->event;

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Send title and file to create a submission.',
                'participant_id' => $participant->id,
                'event_type' => $event->type,
                'allowed_extensions' => $event->type === 'conference' ? ['pdf', 'doc', 'docx'] : ['pdf'],
                'template_download_url' => $event->template_file_path ? route('events.template.download', $event) : null,
            ]);
        }

        return view('submissions.create', [
            'participant' => $participant,
            'event' => $event,
        ]);
    }

    public function store(StoreSubmissionRequest $request, Participant $participant)
    {
        $wantsJson = $request->expectsJson() || $request->is('api/*');

        $filePath = $request->file('file')->store('submissions', 'public');

        $submission = $participant->submissions()->create([
            'title' => $request->validated('title'),
            'file_path' => $filePath,
            'status' => 'pending',
        ]);

        if ($wantsJson) {
            return response()->json([
                'message' => 'Submission created successfully.',
                'data' => $submission,
            ], 201);
        }

        return redirect()
            ->route('participants.submissions.show', [$participant, $submission])
            ->with('status', 'Submission uploaded successfully.');
    }

    public function show(Request $request, Participant $participant, Submission $submission)
    {
        $this->ensureSubmissionBelongsToParticipant($participant, $submission);

        $wantsJson = $request->expectsJson() || $request->is('api/*');

        if ($wantsJson) {
            return response()->json($submission);
        }

        return view('submissions.show', [
            'participant' => $participant,
            'submission' => $submission,
        ]);
    }

    public function edit(Request $request, Participant $participant, Submission $submission)
    {
        $this->ensureSubmissionBelongsToParticipant($participant, $submission);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json($submission);
        }

        return view('submissions.edit', [
            'participant' => $participant,
            'submission' => $submission,
        ]);
    }

    public function update(UpdateSubmissionRequest $request, Participant $participant, Submission $submission)
    {
        $this->ensureSubmissionBelongsToParticipant($participant, $submission);

        $wantsJson = $request->expectsJson() || $request->is('api/*');

        $data = [
            'title' => $request->validated('title'),
        ];

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($submission->file_path);
            $data['file_path'] = $request->file('file')->store('submissions', 'public');
            $data['status'] = 'pending';
        }

        $submission->update($data);

        if ($wantsJson) {
            return response()->json([
                'message' => 'Submission updated successfully.',
                'data' => $submission->fresh(),
            ]);
        }

        return redirect()
            ->route('participants.submissions.show', [$participant, $submission])
            ->with('status', 'Submission updated successfully.');
    }

    public function destroy(Request $request, Participant $participant, Submission $submission)
    {
        $this->ensureSubmissionBelongsToParticipant($participant, $submission);

        $wantsJson = $request->expectsJson() || $request->is('api/*');

        Storage::disk('public')->delete($submission->file_path);
        $submission->delete();

        if ($wantsJson) {
            return response()->json(null, 204);
        }

        return redirect()
            ->route('participants.submissions.index', $participant)
            ->with('status', 'Submission deleted successfully.');
    }

    public function byEvent(Request $request, Event $event)
    {
        $submissions = $event->submissions()
            ->with('participant:id,name,event_id')
            ->latest()
            ->paginate(10);

        if ($request->expectsJson() || $request->is('api/*')) {
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

    private function ensureSubmissionBelongsToParticipant(Participant $participant, Submission $submission): void
    {
        abort_if($submission->participant_id !== $participant->id, 404);
    }
}
