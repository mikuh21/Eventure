@extends('layouts.app')

@section('title', 'Edit Submission')

@section('content')
    <div class="card">
        <div class="header-row">
            <h1>Edit Submission</h1>
            <a class="btn" href="{{ route('participants.submissions.show', [$participant, $submission]) }}">Back</a>
        </div>

        <form action="{{ route('participants.submissions.update', [$participant, $submission]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title', $submission->title) }}" required>
            </div>

            <div class="field">
                <label for="file">Replace File (optional)</label>
                <input id="file" name="file" type="file" accept="{{ $participant->event->type === 'conference' ? '.pdf,.doc,.docx' : 'application/pdf' }}">
            </div>

            <button class="btn btn-primary" type="submit">Update Submission</button>
        </form>
    </div>
@endsection
