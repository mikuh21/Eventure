@extends('layouts.app')

@section('title', 'Edit Participant')

@section('content')
    <div class="card">
        <div class="header-row">
            <h1>Edit Participant</h1>
            <a class="btn" href="{{ route('events.participants.show', [$event, $participant]) }}">Back</a>
        </div>

        <form action="{{ route('events.participants.update', [$event, $participant]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $participant->name) }}" required>
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $participant->email) }}" required>
            </div>

            <div class="field">
                <label for="participant_type">Participant Type</label>
                <select id="participant_type" name="participant_type" required>
                    <option value="">Select type</option>
                    <option value="faculty" {{ old('participant_type', $participant->participant_type) === 'faculty' ? 'selected' : '' }}>Faculty</option>
                    <option value="student" {{ old('participant_type', $participant->participant_type) === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="coach" {{ old('participant_type', $participant->participant_type) === 'coach' ? 'selected' : '' }}>Coach</option>
                    <option value="organizer" {{ old('participant_type', $participant->participant_type) === 'organizer' ? 'selected' : '' }}>Organizer</option>
                </select>
            </div>

            <div class="field">
                <label for="institution">School/University</label>
                <input id="institution" name="institution" type="text" value="{{ old('institution', $participant->institution) }}" required>
            </div>

            <div class="field">
                <label for="attended">
                    <input id="attended" name="attended" type="checkbox" value="1" {{ old('attended', $participant->attended) ? 'checked' : '' }}>
                    Mark as Attended
                </label>
            </div>

            <button class="btn btn-primary" type="submit">Update Participant</button>
        </form>
    </div>
@endsection
