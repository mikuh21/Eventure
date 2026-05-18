@extends('layouts.app')

@section('title', 'Event Submissions')

@section('content')
    <div class="card">
        <div class="header-row">
            <h1>All Submissions for {{ $event->title }}</h1>
            <a class="btn" href="{{ route('events.show', $event) }}">Back to Event</a>
        </div>

        @if ($submissions->count() === 0)
            <p>No submissions found for this event.</p>
        @else
            <table>
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Participant</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($submissions as $submission)
                    <tr>
                        <td>{{ $submission->title }}</td>
                        <td>{{ $submission->participant->name }}</td>
                        <td>{{ $submission->status }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="pagination">{{ $submissions->links() }}</div>
        @endif
    </div>
@endsection
