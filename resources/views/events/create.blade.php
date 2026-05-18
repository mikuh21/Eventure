@extends('layouts.app')

@section('title', 'Create Event - Eventure')

@push('styles')
    <style>
        .create-event-card {
            padding: 18px;
        }

        .create-event-form {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px 16px;
            align-items: start;
        }

        .create-event-form input,
        .create-event-form select,
        .create-event-form textarea {
            font-family: 'Sora', sans-serif;
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #cfe0ef;
            border-radius: 8px;
            background: #ffffff;
            color: #0A2342;
            box-sizing: border-box;
            font-size: 14px;
        }

        .create-event-form select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235BA4CF' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 14px 14px;
        }

        .create-event-form button {
            font-family: 'Sora', sans-serif;
        }

        .create-event-form input:not([type="file"]),
        .create-event-form select {
            min-height: 40px;
        }

        .create-event-form input[type="file"] {
            min-height: 40px;
            padding: 4px 8px;
            cursor: pointer;
        }

        .create-event-form input[type="file"]::file-selector-button {
            font-family: 'Sora', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #ffffff;
            background: #1B6CA8;
            border: 1px solid #1B6CA8;
            border-radius: 6px;
            padding: 0 10px;
            height: 30px;
            margin-right: 10px;
            cursor: pointer;
            transition: background-color 160ms ease, border-color 160ms ease;
        }

        .create-event-form input[type="file"]::-webkit-file-upload-button {
            font-family: 'Sora', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #ffffff;
            background: #1B6CA8;
            border: 1px solid #1B6CA8;
            border-radius: 6px;
            padding: 0 10px;
            height: 30px;
            margin-right: 10px;
            cursor: pointer;
            transition: background-color 160ms ease, border-color 160ms ease;
        }

        .create-event-form input[type="file"]:hover::file-selector-button,
        .create-event-form input[type="file"]:hover::-webkit-file-upload-button {
            background: #0f5e95;
            border-color: #0f5e95;
        }

        .create-event-form textarea {
            min-height: 86px;
            resize: vertical;
        }

        .create-event-form .span-2 {
            grid-column: 1 / -1;
        }

        .create-event-form .form-actions {
            display: flex;
            justify-content: flex-end;
        }

        @media (max-width: 980px) {
            .create-event-form {
                grid-template-columns: 1fr;
            }

            .create-event-form .span-2 {
                grid-column: auto;
            }
        }

        /* Mobile-specific overrides to ensure date inputs are full-width and readable */
        @media (max-width: 640px) {
            .create-event-form input,
            .create-event-form select,
            .create-event-form textarea {
                width: 100%;
                color: #0A2342;
                font-size: 15px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="card create-event-card">
        <div class="header-row">
            <h1>Create Event</h1>
            <a class="btn" href="{{ route('events.index') }}">Back to Events</a>
        </div>

        <form class="create-event-form" action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="field">
                <label for="type">Event Type</label>
                <select id="type" name="type">
                    <option value="standard" {{ old('type', 'standard') === 'standard' ? 'selected' : '' }}>School Event</option>
                    <option value="conference" {{ old('type') === 'conference' ? 'selected' : '' }}>Conference</option>
                </select>
            </div>

            <div class="field">
                <label for="attendance_type">Type of Attendance</label>
                <select id="attendance_type" name="attendance_type">
                    <option value="face_to_face" {{ old('attendance_type', 'face_to_face') === 'face_to_face' ? 'selected' : '' }}>Face-to-face</option>
                    <option value="virtual" {{ old('attendance_type') === 'virtual' ? 'selected' : '' }}>Virtual</option>
                    <option value="both" {{ old('attendance_type') === 'both' ? 'selected' : '' }}>Both</option>
                </select>
            </div>

            <div class="field" id="event-title-field">
                <label for="event_title">School Event Title</label>
                <input id="event_title" name="event_title" type="text" value="{{ old('event_title') }}">
            </div>

            <div class="field" id="conference-title-field">
                <label for="conference_title">Conference Title</label>
                <input id="conference_title" name="conference_title" type="text" value="{{ old('conference_title') }}">
            </div>

            <div class="field" id="conference-theme-field">
                <label for="theme">Theme</label>
                <input id="theme" name="theme" type="text" value="{{ old('theme') }}">
            </div>

            <div class="field" id="conference-keywords-field">
                <label for="keywords">Keywords (comma-separated)</label>
                <input id="keywords" name="keywords" type="text" value="{{ old('keywords') }}" placeholder="AI, Mechatronics, Robotics">
            </div>

            <div class="field" id="standard-description-field">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="field">
                <label for="department">Department</label>
                <input id="department" name="department" type="text" placeholder="e.g. College of Computer Studies" value="{{ old('department') }}">
            </div>

            <div class="field">
                <label for="program">Program</label>
                <input id="program" name="program" type="text" placeholder="e.g. BS Computer Science" value="{{ old('program') }}">
            </div>

            <div class="field">
                <label for="start_date">Start Date</label>
                <input id="start_date" name="start_date" type="date" value="{{ old('start_date') }}" required>
            </div>

            <div class="field">
                <label for="end_date">End Date</label>
                <input id="end_date" name="end_date" type="date" value="{{ old('end_date') }}" required>
            </div>

            <div class="field">
                <label for="start_registration">Start Registration</label>
                <input id="start_registration" name="start_registration" type="datetime-local" value="{{ old('start_registration') }}" required>
            </div>

            <div class="field">
                <label for="end_registration">End Registration</label>
                <input id="end_registration" name="end_registration" type="datetime-local" value="{{ old('end_registration') }}" required>
            </div>

            <div class="field">
                <label for="location">Location</label>
                <input id="location" name="location" type="text" value="{{ old('location') }}" required>
            </div>

            <div class="field">
                <label for="poster">Event Poster</label>
                <input id="poster" name="poster" type="file" accept="image/*">
            </div>

            <div class="field" id="template-file-field">
                <label for="template_file">Conference Template (DOC/PDF)</label>
                <input id="template_file" name="template_file" type="file" accept=".pdf,.doc,.docx">
            </div>

            <div class="form-actions span-2">
                <button class="btn btn-primary" type="submit">Save Event</button>
            </div>
        </form>
    </div>

    <script>
        (function () {
            var typeInput = document.getElementById('type');
            var eventTitleField = document.getElementById('event-title-field');
            var conferenceTitleField = document.getElementById('conference-title-field');
            var conferenceThemeField = document.getElementById('conference-theme-field');
            var conferenceKeywordsField = document.getElementById('conference-keywords-field');
            var standardDescriptionField = document.getElementById('standard-description-field');
            var templateFileField = document.getElementById('template-file-field');

            var toggleByType = function () {
                var isConference = typeInput.value === 'conference';

                eventTitleField.style.display = isConference ? 'none' : 'block';
                standardDescriptionField.style.display = isConference ? 'none' : 'block';

                conferenceTitleField.style.display = isConference ? 'block' : 'none';
                conferenceThemeField.style.display = isConference ? 'block' : 'none';
                conferenceKeywordsField.style.display = isConference ? 'block' : 'none';
                templateFileField.style.display = isConference ? 'block' : 'none';
            };

            typeInput.addEventListener('change', toggleByType);
            toggleByType();

            // Initialize date pickers (apply mobile-only min restrictions)
            var initializeDatePickers = function() {
                var today = new Date();
                var tomorrow = new Date(today);
                tomorrow.setDate(tomorrow.getDate() + 1);
                var minDate = tomorrow.toISOString().split('T')[0];
                var minDateTime = tomorrow.toISOString().split('Z')[0].slice(0, 16);

                // Only enforce min on small viewports (mobile web)
                if (window.matchMedia && window.matchMedia('(max-width: 640px)').matches) {
                    ['start_date', 'end_date'].forEach(function(id) {
                        var input = document.getElementById(id);
                        if (input) {
                            var original = input.value || input.getAttribute('value') || '';
                            input.setAttribute('min', minDate);
                            if (original) input.value = original;
                        }
                    });

                    ['start_registration', 'end_registration'].forEach(function(id) {
                        var input = document.getElementById(id);
                        if (input) {
                            var original = input.value || input.getAttribute('value') || '';
                            input.setAttribute('min', minDateTime);
                            if (original) input.value = original;
                        }
                    });
                }
            };
            initializeDatePickers();
        })();
    </script>
@endsection
