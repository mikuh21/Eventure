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

        /* Ensure single-column fields (date/datetime) are block and full width */
        .create-event-form .single-column label,
        .create-event-form .single-column input,
        .create-event-form .single-column select,
        .create-event-form .single-column textarea {
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
        /* Mobile-only: prevent date inputs from overflowing container */
        @media (max-width: 980px) {
            .create-event-form .single-column input[type="date"],
            .create-event-form .single-column input[type="datetime-local"] {
                max-width: 100% !important;
                box-sizing: border-box !important;
            }

            .create-event-form .field.single-column {
                overflow: hidden;
                padding: 0;
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
                    <option value="school_event" {{ old('type', 'school_event') === 'school_event' ? 'selected' : '' }}>School Event</option>
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

            <div class="field single-column span-2">
                <label for="start_date">Start Date</label>
                <input id="start_date" name="start_date" type="date" value="{{ old('start_date') }}" required>
                <div class="input-error" id="start_date_error">Please select a future date</div>
            </div>

            <div class="field single-column span-2">
                <label for="end_date">End Date</label>
                <input id="end_date" name="end_date" type="date" value="{{ old('end_date') }}" required>
                <div class="input-error" id="end_date_error">Please select a future date</div>
            </div>

            <div class="field single-column span-2">
                <label for="start_registration">Start Registration</label>
                <input id="start_registration" name="start_registration" type="datetime-local" value="{{ old('start_registration') }}" required>
                <div class="input-error" id="start_registration_error">Please select a future date</div>
            </div>

            <div class="field single-column span-2">
                <label for="end_registration">End Registration</label>
                <input id="end_registration" name="end_registration" type="datetime-local" value="{{ old('end_registration') }}" required>
                <div class="input-error" id="end_registration_error">Please select a future date</div>
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

            <div class="form-group" style="margin-bottom:16px;grid-column:span 2;">
                <label style="display:flex;align-items:flex-start;gap:8px;font-weight:600;cursor:pointer;line-height:1.4;">
                    <input type="hidden" name="auto_activate_evaluation" value="0">
                    <input type="checkbox" name="auto_activate_evaluation" value="1" {{ old('auto_activate_evaluation', '1') == '1' ? 'checked' : '' }} style="width:14px;height:14px;margin-top:3px;cursor:pointer;flex-shrink:0;">
                    <span>Auto-open evaluation form at 11PM on event end date</span>
                </label>
                <p style="margin:4px 0 0 22px;font-size:12px;color:#6b7280;line-height:1.5;">If enabled, the system will automatically open the evaluation form<br>and email attended participants at 11PM, if not opened manually.</p>
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

            // Initialize date pickers
            var initializeDatePickers = function() {
                var today = new Date();
                var todayStr = today.toISOString().split('T')[0];
                var todayDateTime = today.toISOString().split('Z')[0].slice(0, 16);
                
                var tomorrow = new Date(today);
                tomorrow.setDate(tomorrow.getDate() + 1);
                var minDate = tomorrow.toISOString().split('T')[0];
                var minDateTime = tomorrow.toISOString().split('Z')[0].slice(0, 16);

                // Start Date and End Date must be tomorrow or later
                ['start_date', 'end_date'].forEach(function(id) {
                    var input = document.getElementById(id);
                    if (input) {
                        input.setAttribute('min', minDate);
                    }
                });

                // Start Registration and End Registration can be today or later
                ['start_registration', 'end_registration'].forEach(function(id) {
                    var input = document.getElementById(id);
                    if (input) {
                        input.setAttribute('min', todayDateTime);
                    }
                });
            };
            initializeDatePickers();

            // iOS fallback: enforce date validation
            var enforceFutureSelection = function() {
                var today = new Date();
                var todayStr = new Date(today.getFullYear(), today.getMonth(), today.getDate()).toISOString().split('T')[0];

                var tomorrow = new Date(today);
                tomorrow.setDate(tomorrow.getDate() + 1);
                var tomorrowStr = new Date(tomorrow.getFullYear(), tomorrow.getMonth(), tomorrow.getDate()).toISOString().split('T')[0];
                var minDateLocal = new Date(tomorrow.getFullYear(), tomorrow.getMonth(), tomorrow.getDate(), 0, 0, 0);
                var todayDateLocal = new Date(today.getFullYear(), today.getMonth(), today.getDate(), 0, 0, 0);

                // Start Date and End Date must be tomorrow or later
                var dateIds = ['start_date', 'end_date'];
                dateIds.forEach(function(id) {
                    var el = document.getElementById(id);
                    if (!el) return;
                    el.addEventListener('change', function() {
                        if (!el.value) return;
                        // value format YYYY-MM-DD, must be > todayStr (tomorrow or later)
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

                // Start Registration and End Registration can be today or later
                var dtIds = ['start_registration', 'end_registration'];
                dtIds.forEach(function(id) {
                    var el = document.getElementById(id);
                    if (!el) return;
                    el.addEventListener('change', function() {
                        if (!el.value) return;
                        var selected = new Date(el.value);
                        // Allow today or any time after today (>= todayDateLocal)
                        if (selected < todayDateLocal) {
                            el.value = '';
                            var err = document.getElementById(id + '_error');
                            if (err) err.style.display = 'block';
                            alert('Please select today or a future date');
                        } else {
                            var err = document.getElementById(id + '_error');
                            if (err) err.style.display = 'none';
                        }
                    });
                });
            };
            enforceFutureSelection();

            // Load Flatpickr on mobile (iOS/Android small screens) to provide a visual minDate
            var loadFlatpickrOnMobile = function() {
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

                    // Start Date and End Date: minDate is tomorrow
                    ['start_date','end_date'].forEach(function(id) {
                        var el = document.getElementById(id);
                        if (!el) return;
                        flatpickr(el, {
                            dateFormat: 'Y-m-d',
                            minDate: tomorrow,
                            disableMobile: true,
                        });
                    });

                    // Start Registration and End Registration: minDate is today
                    ['start_registration','end_registration'].forEach(function(id) {
                        var el = document.getElementById(id);
                        if (!el) return;
                        flatpickr(el, {
                            enableTime: true,
                            time_24hr: false,
                            dateFormat: "Y-m-d\\TH:i",
                            minDate: today,
                            disableMobile: true,
                            minuteIncrement: 1,
                            noCalendar: false
                        });
                    });
                }).catch(function() {
                    // if flatpickr fails to load, rely on JS fallback already in place
                });
            };
            loadFlatpickrOnMobile();
        })();
    </script>
@endsection
