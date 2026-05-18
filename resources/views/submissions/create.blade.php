@extends('layouts.app')

@section('title', 'Upload Submission')

@section('content')
    <div class="card">
        <div class="header-row">
            <h1>Upload Submission</h1>
            <a class="btn" href="{{ route('participants.submissions.index', $participant) }}">Back</a>
        </div>

        @if ($event->type === 'conference')
            <p><strong>Conference Submission:</strong> DOC/DOCX/PDF is allowed.</p>
            @if ($event->template_file_path)
                <p><a class="btn" href="{{ route('events.template.download', $event) }}">Download Template</a></p>
            @endif
        @else
            <p><strong>School Event Submission:</strong> PDF only.</p>
        @endif

        <form action="{{ route('participants.submissions.store', $participant) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="field">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" required>
            </div>

            <div class="field">
                <label for="file">Submission File</label>
                <input id="file" name="file" type="file" accept="{{ $event->type === 'conference' ? '.pdf,.doc,.docx' : 'application/pdf' }}" required>
            </div>

            <button class="btn btn-primary" type="submit">Upload</button>
        </form>
    </div>
@endsection
