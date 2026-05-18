@extends('layouts.app')

@section('title', 'Guest Evaluations')

@section('content')
    <div class="card">
        <div class="header-row">
            <h1>Evaluations for {{ $guest->name }}</h1>
            <div class="actions">
                <a class="btn" href="{{ route('guests.show', $guest) }}">Back</a>
                <a class="btn btn-primary" href="{{ route('guests.evaluations.create', $guest) }}">Add Evaluation</a>
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
                                <a class="btn" href="{{ route('guests.evaluations.show', [$guest, $evaluation]) }}">View</a>
                                <a class="btn" href="{{ route('guests.evaluations.edit', [$guest, $evaluation]) }}">Edit</a>
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
