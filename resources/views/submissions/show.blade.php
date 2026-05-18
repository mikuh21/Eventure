@extends('layouts.app')

@section('title', 'Submission Details')

@section('content')
    <div class="card">
        <div class="header-row">
            <h1>{{ $submission->title }}</h1>
            <div class="actions">
                <a class="btn" href="{{ route('participants.submissions.index', $participant) }}">Back</a>
                <a class="btn" href="{{ route('participants.submissions.edit', [$participant, $submission]) }}">Edit</a>
                <a class="btn" href="{{ asset('storage/'.$submission->file_path) }}" target="_blank">Open File</a>
            </div>
        </div>

        <p><strong>Status:</strong> {{ $submission->status }}</p>

        <form action="{{ route('participants.submissions.destroy', [$participant, $submission]) }}" method="POST" style="margin-top:8px;">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this submission?')">Delete Submission</button>
        </form>
    </div>
@endsection
