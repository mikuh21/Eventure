@extends('layouts.app')

@section('title', 'Edit Guest Evaluation')

@section('content')
    <div class="card">
        <div class="header-row">
            <h1>Edit Evaluation</h1>
            <a class="btn" href="{{ route('guests.evaluations.show', [$guest, $evaluation]) }}">Back</a>
        </div>

        <form action="{{ route('guests.evaluations.update', [$guest, $evaluation]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="rating">Rating (1-5)</label>
                <input id="rating" name="rating" type="number" min="1" max="5" value="{{ old('rating', $evaluation->rating) }}" required>
            </div>

            <div class="field">
                <label for="feedback">Feedback</label>
                <textarea id="feedback" name="feedback" rows="4">{{ old('feedback', $evaluation->feedback) }}</textarea>
            </div>

            <button class="btn btn-primary" type="submit">Update Evaluation</button>
        </form>
    </div>
@endsection
