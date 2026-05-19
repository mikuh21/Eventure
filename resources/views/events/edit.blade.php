@extends('layouts.app')

@section('title', 'Edit Event')

@push('styles')
    <style>
        .edit-event-card {
            padding: 18px;
        }

        .edit-event-form {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px 16px;
            align-items: start;
        }

        .edit-event-form .field {
            margin: 0;
        }

        .edit-event-form label {
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .edit-event-form input,
        .edit-event-form select,
        .edit-event-form textarea,
        .edit-event-form button {
            font-family: 'Sora', sans-serif;
            font-size: 14px;
        }

        .edit-event-form input,
        .edit-event-form select,
        .edit-event-form textarea {
            width: 100%;
            min-height: 40px;
            padding: 8px 10px;
            border: 1px solid #cfe0ef;
            border-radius: 8px;
            background: #ffffff;
            color: var(--color-midnight);
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            box-sizing: border-box;
        }

        .edit-event-form select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235BA4CF' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 14px 14px;
        }

        .edit-event-form input[type="file"] {
            min-height: 40px;
            padding: 4px 8px;
            cursor: pointer;
        }

        .edit-event-form input[type="file"]::file-selector-button {
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

        .edit-event-form input[type="file"]::-webkit-file-upload-button {
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

        .edit-event-form input[type="file"]:hover::file-selector-button,
        .edit-event-form input[type="file"]:hover::-webkit-file-upload-button {
            background: #0f5e95;
            border-color: #0f5e95;
        }

        .edit-event-form textarea {
            min-height: 86px;
            resize: vertical;
        }

        .edit-event-form .field p {
            margin: 8px 0 0;
        }

        .edit-event-form .span-2 {
            grid-column: 1 / -1;
        }

        /* Ensure single-column fields (date/datetime) are block and full width */
        .edit-event-form .single-column label,
        .edit-event-form .single-column input,
        .edit-event-form .single-column select,
        .edit-event-form .single-column textarea {
            display: block !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .input-error {
            color: #c0392b;
            font-size: 13px;
            margin-top: 6px;
            display: none;
        }

        .edit-event-form .form-actions {
            display: flex;
            justify-content: flex-end;
        }

        @media (max-width: 980px) {
            .edit-event-form {
                grid-template-columns: 1fr;
            }

            .edit-event-form .span-2 {
                grid-column: auto;
            }
        }
        /* Mobile-only: prevent date inputs from overflowing container */
        @media (max-width: 980px) {
            .edit-event-form .single-column input[type="date"],
            .edit-event-form .single-column input[type="datetime-local"] {
                max-width: 100% !important;
                box-sizing: border-box !important;
            }

            .edit-event-form .field.single-column {
                overflow: hidden;
                padding: 0;
            }
        }
    </style>
@endpush

@section('content')
    <div class="card edit-event-card">
        <div class="header-row">
            <h1>Edit Event</h1>
            <a class="btn" href="{{ route('events.show', $event) }}">Back to Event</a>
        </div>

        <form class="edit-event-form" action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="type">Event Type</label>
                <select id="type" name="type">
                    <option value="">Select Event Type</option>
                    <option value="school_event" {{ old('type', $event->type) === 'school_event' ? 'selected' : '' }}>School Event</option>
                    <option value="conference" {{ old('type', $event->type) === 'conference' ? 'selected' : '' }}>Conference</option>
                </select>
            </div>

            <div class="field">
                <label for="attendance_type">Type of Attendance</label>
                <select id="attendance_type" name="attendance_type">
                    <option value="face_to_face" {{ old('attendance_type', $event->attendance_type ?? 'face_to_face') === 'face_to_face' ? 'selected' : '' }}>Face-to-face</option>
                    <option value="virtual" {{ old('attendance_type', $event->attendance_type ?? 'face_to_face') === 'virtual' ? 'selected' : '' }}>Virtual</option>
                    <option value="both" {{ old('attendance_type', $event->attendance_type ?? 'face_to_face') === 'both' ? 'selected' : '' }}>Both</option>
                </select>
            </div>

            <div class="field" id="event-title-field">
                <label for="event_title">School Event Title</label>
                <input id="event_title" name="event_title" type="text" value="{{ old('event_title', $event->event_title) }}">
            </div>

            <div class="field" id="conference-title-field">
                <label for="conference_title">Conference Title</label>
                <input id="conference_title" name="conference_title" type="text" value="{{ old('conference_title', $event->conference_title) }}">
            </div>

            <div class="field" id="conference-theme-field">
                <label for="theme">Theme</label>
                <input id="theme" name="theme" type="text" value="{{ old('theme', $event->theme) }}">
            </div>

            <div class="field" id="conference-keywords-field">
                <label for="keywords">Keywords (comma-separated)</label>
                <input id="keywords" name="keywords" type="text" value="{{ old('keywords', is_array($event->keywords) ? implode(', ', $event->keywords) : '') }}" placeholder="AI, Mechatronics, Robotics">
            </div>

            <div class="field" id="standard-description-field">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4">{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="field single-column span-2">
                <label for="start_date">Start Date</label>
                <input id="start_date" name="start_date" type="date" value="{{ old('start_date', $event->start_date?->format('Y-m-d')) }}" required>
                <div class="input-error" id="start_date_error">Please select a future date</div>
            </div>

            <div class="field single-column span-2">
                <label for="end_date">End Date</label>
                <input id="end_date" name="end_date" type="date" value="{{ old('end_date', $event->end_date?->format('Y-m-d')) }}" required>
                <div class="input-error" id="end_date_error">Please select a future date</div>
            </div>

            <div class="field single-column span-2">
                <label for="start_registration">Start Registration</label>
                <input id="start_registration" name="start_registration" type="datetime-local" value="{{ old('start_registration', $event->start_registration?->format('Y-m-d\\TH:i')) }}" required>
                <div class="input-error" id="start_registration_error">Please select a future date</div>
            </div>

            <div class="field single-column span-2">
                <label for="end_registration">End Registration</label>
                <input id="end_registration" name="end_registration" type="datetime-local" value="{{ old('end_registration', $event->end_registration?->format('Y-m-d\\TH:i')) }}" required>
                <div class="input-error" id="end_registration_error">Please select a future date</div>
            </div>

            <div class="field">
                <label for="location">Location</label>
                <input id="location" name="location" type="text" value="{{ old('location', $event->location) }}" required>
            </div>

            <div class="field">
                <label for="department">Department</label>
                <input id="department" name="department" type="text" placeholder="e.g. College of Computer Studies" value="{{ old('department', $event->department) }}">
            </div>

            <div class="field">
                <label for="program">Program</label>
                <input id="program" name="program" type="text" placeholder="e.g. BS Computer Science" value="{{ old('program', $event->program) }}">
            </div>

            <div class="field">
                <label for="poster">Event Poster</label>
                <input id="poster" name="poster" type="file" accept="image/*">
                @if ($event->poster_path)
                    <div class="mt-2">
                        <p class="text-sm mb-2">Current Poster:</p>
                        <img src="https://sesmcvjwmkphgkzawewn.supabase.co/storage/v1/object/public/event-posters/{{ $event->poster_path }}" class="w-32 h-32 object-contain border border-gray-300 rounded">
                    </div>
                @endif
            </div>

            <div class="field" id="template-file-field">
                <label for="template_file">Conference Template (DOC/PDF)</label>
                <input id="template_file" name="template_file" type="file" accept=".pdf,.doc,.docx">
                @if ($event->type === 'conference' && $event->template_file_path)
                    <div class="mt-2">
                        <p class="text-sm mb-2">Current Template:</p>
                        <a href="{{ route('events.download-template', $event) }}" class="text-blue-600 underline text-sm flex items-center gap-1">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                <polyline points="13 2 13 9 20 9"></polyline>
                            </svg>
                            Conference-Paper-Template.{{ pathinfo($event->template_file_path, PATHINFO_EXTENSION) }}
                        </a>
                    </div>
                @endif
            </div>

            <div class="form-actions span-2">
                <button class="btn btn-primary" type="submit">Update Event</button>
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

            // Initialize date pickers
            var initializeDatePickers = function() {
                var today = new Date();
                var tomorrow = new Date(today);
                tomorrow.setDate(tomorrow.getDate() + 1);
                var minDate = tomorrow.toISOString().split('T')[0];
                var minDateTime = tomorrow.toISOString().split('Z')[0].slice(0, 16);

                ['start_date', 'end_date'].forEach(function(id) {
                    var input = document.getElementById(id);
                    if (input) {
                        input.setAttribute('min', minDate);
                    }
                });

                ['start_registration', 'end_registration'].forEach(function(id) {
                    var input = document.getElementById(id);
                    if (input) {
                        input.setAttribute('min', minDateTime);
                    }
                });
            };
            initializeDatePickers();

            // iOS fallback: enforce future-only selection for standalone edit page
            var enforceFutureSelectionForStandaloneEdit = function() {
                var today = new Date();
                var todayStr = new Date(today.getFullYear(), today.getMonth(), today.getDate()).toISOString().split('T')[0];

                var tomorrow = new Date(today);
                tomorrow.setDate(tomorrow.getDate() + 1);
                var minDateLocal = new Date(tomorrow.getFullYear(), tomorrow.getMonth(), tomorrow.getDate(), 0, 0, 0);

                ['start_date', 'end_date'].forEach(function(id) {
                    var el = document.getElementById(id);
                    if (!el) return;
                    el.addEventListener('change', function() {
                        if (!el.value) return;
                        if (el.value <= todayStr) {
                            el.value = '';
                            var err = document.getElementById(id + '_error');
                            if (err) err.style.display = 'block';
                            alert('Please select a future date');
                        } else {
                            var err = document.getElementById(id + '_error');
                            if (err) err.style.display = 'none';
                        }
                    });
                });

                ['start_registration', 'end_registration'].forEach(function(id) {
                    var el = document.getElementById(id);
                    if (!el) return;
                    el.addEventListener('change', function() {
                        if (!el.value) return;
                        var selected = new Date(el.value);
                        if (selected <= minDateLocal) {
                            el.value = '';
                            var err = document.getElementById(id + '_error');
                            if (err) err.style.display = 'block';
                            alert('Please select a future date');
                        } else {
                            var err = document.getElementById(id + '_error');
                            if (err) err.style.display = 'none';
                        }
                    });
                });
            };
            enforceFutureSelectionForStandaloneEdit();

            // Load Flatpickr on mobile for standalone edit page
            var loadFlatpickrOnMobileForStandalone = function() {
                var isSmall = window.matchMedia('(max-width: 980px)').matches;
                var isTouch = ('ontouchstart' in window) || navigator.maxTouchPoints > 0;
                if (!isSmall || !isTouch) return;

                var loadCss = function(href) {
                    return new Promise(function(resolve) {
                        var link = document.createElement('link');
                        link.rel = 'stylesheet';
                        link.href = href;
                        link.onload = resolve;
                        document.head.appendChild(link);
                    });
                };

                var loadScript = function(src) {
                    return new Promise(function(resolve) {
                        var s = document.createElement('script');
                        s.src = src;
                        s.onload = resolve;
                        document.head.appendChild(s);
                    });
                };

                Promise.all([
                    loadCss('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css'),
                    loadScript('https://cdn.jsdelivr.net/npm/flatpickr')
                ]).then(function() {
                    var today = new Date();
                    var tomorrow = new Date(today);
                    tomorrow.setDate(tomorrow.getDate() + 1);

                    ['start_date','end_date'].forEach(function(id) {
                        var el = document.getElementById(id);
                        if (!el) return;
                        flatpickr(el, {
                            dateFormat: 'Y-m-d',
                            minDate: tomorrow,
                            disableMobile: true,
                        });
                    });

                    ['start_registration','end_registration'].forEach(function(id) {
                        var el = document.getElementById(id);
                        if (!el) return;
                        flatpickr(el, {
                            enableTime: true,
                            time_24hr: false,
                            dateFormat: "Y-m-d\\TH:i",
                            minDate: tomorrow,
                            disableMobile: true,
                            minuteIncrement: 1
                        });
                    });
                }).catch(function() {
                    // fallback handled earlier
                });
            };
            loadFlatpickrOnMobileForStandalone();
        })();
    </script>
@endsection
