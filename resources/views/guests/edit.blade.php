@extends('layouts.app')

@section('title', 'Edit Guest')

@section('content')
    <div class="card">
        <div class="header-row">
            <h1>Edit Guest</h1>
            <a class="btn" href="{{ route('guests.show', $guest) }}">Back to Guest</a>
        </div>

        <form action="{{ route('guests.update', $guest) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="event_id">Event</label>
                <select id="event_id" name="event_id" required style="appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%235BA4CF\' stroke-width=\'2.2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Cpath d=\'m6 9 6 6 6-6\'/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 12px center; background-size: 14px 14px;">
                    @foreach ($events as $event)
                        <option value="{{ $event->id }}" {{ (string) old('event_id', $guest->event_id) === (string) $event->id ? 'selected' : '' }}>
                            {{ $event->title }} ({{ $event->dateRangeLabel() }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="name">Guest Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $guest->name) }}" required>
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $guest->email) }}" required>
            </div>

            <div class="field">
                <label for="role">Role</label>
                <input id="role" name="role" type="text" value="{{ old('role', $guest->role) }}" required>
            </div>

            <div class="field">
                <label for="bio">Bio / Notes</label>
                <textarea id="bio" name="bio" rows="4">{{ old('bio', $guest->bio) }}</textarea>
            </div>

            <button class="btn btn-primary" type="submit">Update Guest</button>
        </form>
    </div>
@endsection
