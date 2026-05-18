@extends('layouts.app')

@section('title', 'Evaluation Details')

@section('content')
    <div class="card">
        <div class="header-row">
            <h1>Evaluation</h1>
            <div class="actions">
                <a class="btn" href="{{ route('participants.evaluations.index', $participant) }}">Back</a>
                <a class="btn" href="{{ route('participants.evaluations.edit', [$participant, $evaluation]) }}">Edit</a>
            </div>
        </div>

        <p><strong>Rating:</strong> {{ $evaluation->rating }}</p>
        <p><strong>Feedback:</strong> {{ $evaluation->feedback ?: 'No feedback provided.' }}</p>

        <form action="{{ route('participants.evaluations.destroy', [$participant, $evaluation]) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this evaluation?')">Delete Evaluation</button>
        </form>
    </div>
@endsection
