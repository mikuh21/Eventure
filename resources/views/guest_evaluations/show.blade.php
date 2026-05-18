@extends('layouts.app')

@section('title', 'Guest Evaluation Details')

@section('content')
    <div class="card">
        <div class="header-row">
            <h1>Evaluation</h1>
            <div class="actions">
                <a class="btn" href="{{ route('guests.evaluations.index', $guest) }}">Back</a>
                <a class="btn" href="{{ route('guests.evaluations.edit', [$guest, $evaluation]) }}">Edit</a>
            </div>
        </div>

        <p><strong>Guest:</strong> {{ $guest->name }}</p>
        <p><strong>Rating:</strong> {{ $evaluation->rating }}</p>
        <p><strong>Feedback:</strong> {{ $evaluation->feedback ?: 'No feedback provided.' }}</p>

        <form action="{{ route('guests.evaluations.destroy', [$guest, $evaluation]) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this evaluation?')">Delete Evaluation</button>
        </form>
    </div>
@endsection
