@extends('layouts.app')

@section('title', 'Registration Confirmation')

@section('content')
    <div class="card">
        <h1>Registration Confirmed</h1>
        <p><strong>Name:</strong> {{ $participant->name }}</p>
        <p><strong>Email:</strong> {{ $participant->email }}</p>
        <p><strong>Participant Type:</strong> {{ $participant->participant_type ?? 'N/A' }}</p>
        <p><strong>Institution:</strong> {{ $participant->institution ?? 'N/A' }}</p>
        <p><strong>Event:</strong> {{ $participant->event->title }}</p>

        <div class="actions">
            <a class="btn btn-primary" href="{{ route('participant.mobile', $participant->digital_id_token) }}">Open Digital ID</a>
        </div>
    </div>
@endsection
