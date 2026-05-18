@extends('layouts.app')

@section('title', 'Submissions')

@section('content')
    <div class="card">
        <div class="header-row">
            <h1>Submissions for {{ $participant->name }}</h1>
            <div class="actions">
                <a class="btn" href="{{ route('events.participants.show', [$participant->event, $participant]) }}">Back</a>
                <a class="btn btn-primary" href="{{ route('participants.submissions.create', $participant) }}">Upload Submission</a>
            </div>
        </div>

        @if ($submissions->count() === 0)
            <p>No submissions found.</p>
        @else
            <table>
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($submissions as $submission)
                    <tr>
                        <td>{{ $submission->title }}</td>
                        <td>{{ $submission->status }}</td>
                        <td><a class="btn" href="{{ asset('storage/'.$submission->file_path) }}" target="_blank">Open File</a></td>
                        <td>
                            <div class="actions">
                                <a class="btn" href="{{ route('participants.submissions.show', [$participant, $submission]) }}">View</a>
                                <a class="btn" href="{{ route('participants.submissions.edit', [$participant, $submission]) }}">Edit</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="pagination">{{ $submissions->links() }}</div>
        @endif
    </div>
@endsection
