@extends('layouts.app')

@section('title', 'Register Participant')

@push('styles')
    <style>
        :root {
            --color-ice-white: #E8F4FD;
            --color-sky: #BFDFFF;
            --color-steel-blue: #5BA4CF;
            --color-ocean: #1B6CA8;
            --color-midnight: #0A2342;
            --color-coral: #FF6B35;
        }

        .participant-register-card {
            padding: 18px;
            border: 1px solid var(--color-sky);
            background: #ffffff;
            box-shadow: 0 18px 45px rgba(10, 35, 66, 0.2);
        }

        .participant-register-modal-backdrop {
            background: linear-gradient(135deg, rgba(232, 244, 253, 0.78), rgba(191, 223, 255, 0.52));
            border: 1px solid rgba(191, 223, 255, 0.65);
            border-radius: 14px;
            padding: 24px;
            min-height: calc(100dvh - 88px);
            display: grid;
            align-items: center;
        }

        .participant-register-modal {
            width: min(900px, 100%);
            margin: 0 auto;
        }

        .participant-register-title {
            margin: 0;
            font-family: 'Sora', sans-serif;
            color: var(--color-midnight);
            font-size: 1.6rem;
            font-weight: 700;
        }

        .registration-overview {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin: 10px 0 14px;
        }

        .overview-item {
            background: #ffffff;
            border: 1px solid var(--color-sky);
            border-radius: 10px;
            padding: 10px 12px;
        }

        .overview-label {
            margin: 0 0 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--color-steel-blue);
            font-size: 11px;
            font-weight: 600;
            font-family: 'Sora', sans-serif;
        }

        .overview-value {
            margin: 0;
            color: var(--color-midnight);
            font-size: 14px;
            font-family: 'Sora', sans-serif;
        }

        .registration-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            padding: 2px 10px;
            line-height: 1.3;
            font-family: 'Sora', sans-serif;
        }

        .registration-open {
            background: #d1fae5;
            color: #065f46;
        }

        .registration-closed {
            background: #fee2e2;
            color: #991b1b;
        }

        .participant-register-form {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px 16px;
            align-items: start;
        }

        .participant-register-form .field {
            margin: 0;
        }

        .participant-register-form label {
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .participant-register-form input,
        .participant-register-form button {
            font-family: 'Sora', sans-serif;
            font-size: 14px;
        }

        .participant-register-form input {
            width: 100%;
            min-height: 40px;
            padding: 8px 10px;
            border: 1px solid #cfe0ef;
            border-radius: 8px;
            background: #ffffff;
            color: var(--color-midnight);
        }

        .participant-register-form .form-actions {
            grid-column: 1 / -1;
            display: flex;
            justify-content: flex-end;
        }

        @media (max-width: 980px) {
            .participant-register-modal-backdrop {
                min-height: auto;
                padding: 12px;
            }

            .registration-overview {
                grid-template-columns: 1fr;
            }

            .participant-register-form {
                grid-template-columns: 1fr;
            }
        }

        .participant-register-form select {
    font-family: 'Sora', sans-serif;
    font-size: 14px;
    width: 100%;
    min-height: 40px;
    padding: 8px 10px;
    border: 1px solid #cfe0ef;
    border-radius: 8px;
    background: #ffffff;
    color: var(--color-midnight);
}
    </style>
@endpush

@section('content')
    <div class="participant-register-modal-backdrop">
        <div class="participant-register-modal">
            <div class="card participant-register-card">
                <div class="header-row">
                    <h1 class="participant-register-title">Register Participant</h1>
                    <a class="btn" href="{{ route('participants.public.events') }}">Close</a>
                </div>

                <div class="registration-overview">
                    <div class="overview-item">
                        <p class="overview-label">Event</p>
                        <p class="overview-value">{{ $event->title }}</p>
                    </div>
                    <div class="overview-item">
                        <p class="overview-label">Registration Window</p>
                        <p class="overview-value">
                            {{ $event->start_registration ? \Carbon\Carbon::parse($event->start_registration)->format('M d, Y h:i A') : 'N/A' }}
                            to
                            {{ $event->end_registration ? \Carbon\Carbon::parse($event->end_registration)->format('M d, Y h:i A') : 'N/A' }}
                        </p>
                    </div>
                    <div class="overview-item">
                        <p class="overview-label">Registration Status</p>
                        <p class="overview-value">
                            <span class="registration-badge {{ $event->isRegistrationOpen() ? 'registration-open' : 'registration-closed' }}">
                                {{ $event->isRegistrationOpen() ? 'Open' : 'Closed' }}
                            </span>
                        </p>
                    </div>
                </div>

                <form class="participant-register-form" action="{{ route('events.participants.store', $event) }}" method="POST">
                    @csrf

                    <div class="field">
                        <label for="name">Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                    </div>

                    <div class="field">
                        <label for="participant_type">Participant Type</label>
                        <select id="participant_type" name="participant_type" required>
                            <option value="">Select type</option>
                            <option value="faculty" {{ old('participant_type') === 'faculty' ? 'selected' : '' }}>Faculty</option>
                            <option value="student" {{ old('participant_type') === 'student' ? 'selected' : '' }}>Student</option>
                            <option value="coach" {{ old('participant_type') === 'coach' ? 'selected' : '' }}>Coach</option>
                            <option value="organizer" {{ old('participant_type') === 'organizer' ? 'selected' : '' }}>Organizer</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="field">
                        <label for="institution">School/University</label>
                        <input id="institution" name="institution" type="text" value="{{ old('institution') }}" placeholder="e.g. NU Lipa" required>
                    </div>

                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit">Register Participant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
