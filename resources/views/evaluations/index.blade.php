@extends('layouts.app')

@section('title', 'Evaluations')

@section('content')
    <div class="card">
        <div class="header-row">
            <h1>Evaluations for {{ $participant->name }}</h1>
            <div class="actions">
                <a class="btn" href="{{ route('events.participants.show', [$participant->event, $participant]) }}">Back</a>
                <a class="btn btn-primary" href="{{ route('participants.evaluations.create', $participant) }}">Add Evaluation</a>
            </div>
        </div>

        @if ($evaluations->count() === 0)
            <p>No evaluations yet.</p>
        @else
            <table>
                <thead>
                <tr>
                    <th>Rating</th>
                    <th>Feedback</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($evaluations as $evaluation)
                    <tr>
                        <td>{{ $evaluation->rating }}</td>
                        <td>{{ $evaluation->feedback }}</td>
                        <td>
                            <div class="actions">
                                <a class="btn" href="{{ route('participants.evaluations.show', [$participant, $evaluation]) }}">View</a>
                                <a class="btn" href="{{ route('participants.evaluations.edit', [$participant, $evaluation]) }}">Edit</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="pagination">{{ $evaluations->links() }}</div>
        @endif
    </div>
@endsection
