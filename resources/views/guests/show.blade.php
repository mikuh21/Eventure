@extends('layouts.app')

@section('title', 'Guest Details')

@section('content')
    <div class="card">
        <div class="header-row">
            <h1>{{ $guest->name }}</h1>
            <div class="actions">
                <a class="btn" href="{{ route('guests.index', ['event_id' => $guest->event_id]) }}">Back</a>
                <a class="btn" href="{{ route('guests.edit', $guest) }}">Edit</a>
                <a class="btn btn-primary" href="{{ route('guests.evaluations.index', $guest) }}">Evaluations</a>
            </div>
        </div>

        <p><strong>Email:</strong> {{ $guest->email }}</p>
        <p><strong>Role:</strong> {{ ucfirst($guest->role) }}</p>
        <p><strong>Event:</strong> {{ $guest->event->title }}</p>
        <p><strong>Bio:</strong> {{ $guest->bio ?: 'No notes provided.' }}</p>
        <p><strong>Total Evaluations:</strong> {{ $guest->evaluations_count }}</p>
        <p><strong>Average Rating:</strong> {{ $averageRating ?? 'N/A' }}</p>

        <form action="{{ route('guests.destroy', $guest) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this guest?')">Delete Guest</button>
        </form>
    </div>
@endsection
