@extends('layouts.app')

@section('title', 'Add Guest Evaluation')

@section('content')
    <div class="card">
        <div class="header-row">
            <h1>Add Evaluation</h1>
            <a class="btn" href="{{ route('guests.evaluations.index', $guest) }}">Back</a>
        </div>

        <form action="{{ route('guests.evaluations.store', $guest) }}" method="POST">
            @csrf

            <div class="field">
                <label for="rating">Rating (1-5)</label>
                <input id="rating" name="rating" type="number" min="1" max="5" value="{{ old('rating') }}" required>
            </div>

            <div class="field">
                <label for="feedback">Feedback</label>
                <textarea id="feedback" name="feedback" rows="4">{{ old('feedback') }}</textarea>
            </div>

            <button class="btn btn-primary" type="submit">Save Evaluation</button>
        </form>
    </div>
@endsection
