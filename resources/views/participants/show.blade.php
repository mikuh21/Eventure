@extends('layouts.app')

@section('title', 'Participant Details')

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

        .participant-profile-page {
            background: var(--color-ice-white);
            border: 1px solid var(--color-sky);
            border-radius: 12px;
            padding: 20px;
        }

        .participant-profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .participant-profile-title {
            margin: 0;
            color: var(--color-midnight);
            font-size: 24px;
            font-weight: 700;
            font-family: 'Sora', sans-serif;
        }

        .back-to-participants {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--color-ocean);
            text-decoration: none;
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            font-weight: 600;
        }

        .profile-top-actions {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .profile-top-actions .btn-digital-id {
            order: 2;
        }

        .profile-top-actions .back-to-participants {
            order: 1;
        }

        .btn-digital-id {
            background: var(--color-midnight);
            border: 1px solid var(--color-midnight);
            color: #ffffff;
            border-radius: 8px;
            font-family: 'Sora', sans-serif;
            white-space: nowrap;
        }

        .profile-card {
            background: #ffffff;
            border: 1px solid var(--color-sky);
            border-radius: 12px;
            padding: 24px;
        }

        .profile-card + .profile-card {
            margin-top: 20px;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px 20px;
        }

        .profile-item-label {
            margin: 0 0 4px;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.06em;
            color: var(--color-steel-blue);
            font-weight: 500;
            font-family: 'Sora', sans-serif;
        }

        .profile-item-value {
            margin: 0;
            color: var(--color-midnight);
            font-size: 15px;
            font-weight: 400;
            font-family: 'Sora', sans-serif;
            line-height: 1.4;
        }

        .attendance-badge {
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

        .attendance-yes {
            background: #d1fae5;
            color: #065f46;
        }

        .attendance-no {
            background: #fee2e2;
            color: #991b1b;
        }

        .evaluation-title {
            margin: 0 0 16px;
            color: var(--color-midnight);
            font-size: 16px;
            font-weight: 600;
            font-family: 'Sora', sans-serif;
        }

        .evaluation-item {
            padding: 12px 0;
            border-bottom: 1px solid var(--color-ice-white);
        }

        .evaluation-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .evaluation-question {
            margin: 0 0 6px;
            color: var(--color-steel-blue);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
        }

        .evaluation-answer {
            margin: 0;
            color: var(--color-midnight);
            font-size: 14px;
            font-family: 'Sora', sans-serif;
            line-height: 1.45;
        }

        .evaluation-empty {
            text-align: center;
            color: var(--color-ocean);
            padding: 12px 6px;
        }

        .evaluation-empty svg {
            width: 40px;
            height: 40px;
            color: var(--color-sky);
            margin-bottom: 8px;
        }

        .evaluation-empty p {
            margin: 0;
            color: var(--color-ocean);
            font-size: 14px;
            font-family: 'Sora', sans-serif;
        }

        .digital-id-modal {
            position: fixed;
            inset: 0;
            background: rgba(10, 35, 66, 0.55);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 180ms ease, visibility 0s linear 180ms;
        }

        .digital-id-modal.is-visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition: opacity 180ms ease;
        }

        .digital-id-panel {
            background: #ffffff;
            border-radius: 16px;
            width: 100%;
            max-width: 460px;
            padding: 32px;
            position: relative;
            box-shadow: 0 8px 40px rgba(10, 35, 66, 0.18);
            transform: translateY(10px) scale(0.98);
            opacity: 0;
            transition: transform 220ms ease, opacity 220ms ease;
        }

        .digital-id-modal.is-visible .digital-id-panel {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .digital-id-close {
            position: absolute;
            top: 16px;
            right: 20px;
            background: none;
            border: none;
            font-size: 20px;
            color: #5BA4CF;
            cursor: pointer;
        }

        .digital-id-kicker {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #5BA4CF;
            font-weight: 500;
            margin: 0 0 6px;
        }

        .digital-id-name {
            font-size: 20px;
            font-weight: 600;
            color: #0A2342;
            margin: 0;
            font-family: 'Sora', sans-serif;
        }

        .digital-id-event {
            font-size: 13px;
            color: #1B6CA8;
            margin: 4px 0 0;
            font-family: 'Sora', sans-serif;
        }

        .digital-id-qr-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            position: relative;
        }

        .digital-id-qr {
            width: 200px;
            height: 200px;
            border: 2px solid #BFDFFF;
            border-radius: 12px;
            padding: 8px;
            background: #E8F4FD;
            object-fit: contain;
        }

        .digital-id-qr-skeleton {
            position: absolute;
            inset: 0;
            margin: auto;
            width: 200px;
            height: 200px;
            border: 2px solid #BFDFFF;
            border-radius: 12px;
            background: linear-gradient(100deg, #e8f4fd 30%, #d6e9fb 50%, #e8f4fd 70%);
            background-size: 220% 100%;
            animation: did-skeleton 1.1s ease-in-out infinite;
            display: none;
        }

        .digital-id-qr-wrap.is-loading .digital-id-qr-skeleton {
            display: block;
        }

        .digital-id-qr-wrap.is-loading .digital-id-qr {
            opacity: 0;
        }

        @keyframes did-skeleton {
            from {
                background-position: 100% 0;
            }
            to {
                background-position: -100% 0;
            }
        }

        .digital-id-token-row {
            background: #E8F4FD;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .digital-id-token-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #5BA4CF;
            margin: 0 0 3px;
        }

        .digital-id-token-value {
            font-size: 12px;
            color: #0A2342;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;
            word-break: break-all;
            margin: 0;
        }

        .digital-id-copy-btn {
            margin-left: 12px;
            border: 1px solid #BFDFFF;
            background: #ffffff;
            color: #1B6CA8;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 12px;
            cursor: pointer;
            white-space: nowrap;
            font-family: 'Sora', sans-serif;
        }

        .digital-id-download {
            display: block;
            text-align: center;
            background: #0A2342;
            color: #ffffff;
            border-radius: 8px;
            padding: 12px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            font-family: 'Sora', sans-serif;
        }

        .digital-id-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .digital-id-resend {
            border: 1px solid #BFDFFF;
            background: #ffffff;
            color: #1B6CA8;
            border-radius: 8px;
            padding: 12px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Sora', sans-serif;
            cursor: pointer;
        }

        .digital-id-resend[disabled] {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .digital-id-email-status {
            margin: 10px 0 0;
            min-height: 18px;
            font-size: 12px;
            font-family: 'Sora', sans-serif;
            color: #5BA4CF;
        }

        .digital-id-email-status.success {
            color: #047857;
        }

        .digital-id-email-status.error {
            color: #b91c1c;
        }

        .digital-id-email-meta {
            margin: 4px 0 0;
            min-height: 16px;
            font-size: 11px;
            font-family: 'Sora', sans-serif;
            color: #5BA4CF;
            opacity: 0.9;
        }

        @media (max-width: 900px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .participant-profile-page {
                padding: 16px;
            }

            .participant-profile-header {
                align-items: flex-start;
            }

            .profile-top-actions {
                flex-direction: column;
                align-items: flex-end;
            }

            .profile-top-actions .btn-digital-id {
                order: 1;
            }

            .profile-top-actions .back-to-participants {
                order: 2;
            }

            .participant-profile-title {
                font-size: 21px;
                line-height: 1.2;
            }

            .btn-digital-id {
                padding: 10px 12px;
                font-size: 13px;
            }

            .back-to-participants {
                font-size: 12px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="participant-profile-page">
        <div class="participant-profile-header">
            <h1 class="participant-profile-title">Participant Profile</h1>
            <div class="profile-top-actions">
                <button
                    type="button"
                    class="btn btn-digital-id"
                    id="openProfileDigitalIdModal"
                    data-participant-id="{{ $participant->id }}"
                    data-name="{{ $participant->name }}"
                    data-event="{{ $participant->event->title ?? '' }}"
                    data-token="{{ $participant->digital_id_token ?? '' }}"
                    data-qr="{{ route('participants.digital-id.show', $participant, false) }}"
                    data-resend-url="{{ route('events.participants.resend-digital-id', [$event, $participant]) }}"
                >
                    Digital ID
                </button>
                <a class="back-to-participants" href="{{ route('events.participants.index', $event) }}?event_id={{ $participant->event_id }}">
                    <span aria-hidden="true">&larr;</span>
                    <span>Back to Participants</span>
                </a>
            </div>
        </div>

        <div class="profile-card">
            <div class="profile-grid">
                <div>
                    <p class="profile-item-label">Full Name</p>
                    <p class="profile-item-value">{{ $participant->name }}</p>
                </div>
                <div>
                    <p class="profile-item-label">Email</p>
                    <p class="profile-item-value">{{ $participant->email }}</p>
                </div>
                <div>
                    <p class="profile-item-label">Participant Type</p>
                    <p class="profile-item-value">{{ ucfirst($participant->participant_type ?? '') ?: 'N/A' }}</p>
                </div>
                <div>
                    <p class="profile-item-label">Institution</p>
                    <p class="profile-item-value">{{ $participant->institution ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="profile-item-label">Registered At</p>
                    <p class="profile-item-value">{{ \Carbon\Carbon::parse($participant->created_at)->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="profile-item-label">Event</p>
                    <p class="profile-item-value">{{ $participant->event->title }}</p>
                </div>
                <div>
                    <p class="profile-item-label">Attendance Status</p>
                    <span class="attendance-badge {{ $participant->attended ? 'attendance-yes' : 'attendance-no' }}">
                        {{ $participant->attended ? 'Attended' : 'Not Attended' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="profile-card">
            <h2 class="evaluation-title">Submitted Evaluation</h2>

            @if (! $participant->event->isSurveyActive())
                <div class="evaluation-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M8 2h8l4 4v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"></path>
                        <path d="M14 2v6h6"></path>
                        <path d="M8 13h8"></path>
                        <path d="M8 17h5"></path>
                    </svg>
                    <p>The survey becomes available after the event ends.</p>
                </div>
            @elseif (! $participant->attended)
                <div class="evaluation-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M8 2h8l4 4v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"></path>
                        <path d="M14 2v6h6"></path>
                        <path d="M8 13h8"></path>
                        <path d="M8 17h5"></path>
                    </svg>
                    <p>Attendance has not been recorded yet for this participant.</p>
                </div>
            @elseif ($participant->evaluations->count() > 0)
                @foreach ($participant->evaluations as $evaluation)
                    <div class="evaluation-item">
                        <p class="evaluation-question">Rating</p>
                        <p class="evaluation-answer">{{ $evaluation->rating }}/5</p>

                        <p class="evaluation-question" style="margin-top: 10px;">Feedback</p>
                        <p class="evaluation-answer">{{ $evaluation->feedback ?: 'No feedback submitted.' }}</p>

                        <p class="evaluation-question" style="margin-top: 10px;">Submitted At</p>
                        <p class="evaluation-answer">{{ \Carbon\Carbon::parse($evaluation->created_at)->format('M d, Y h:i A') }}</p>
                    </div>
                @endforeach
            @else
                <div class="evaluation-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M8 2h8l4 4v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"></path>
                        <path d="M14 2v6h6"></path>
                        <path d="M8 13h8"></path>
                        <path d="M8 17h5"></path>
                    </svg>
                    <p>No evaluation submitted yet.</p>
                </div>
            @endif
        </div>
    </div>

    <div id="profileDigitalIdModal" class="digital-id-modal" aria-hidden="true">
        <div class="digital-id-panel" role="dialog" aria-modal="true" aria-labelledby="profile-did-name">
            <button id="closeProfileDigitalId" class="digital-id-close" type="button" aria-label="Close digital ID">&#10005;</button>

            <div style="margin-bottom:24px;">
                <p class="digital-id-kicker">Digital ID</p>
                <h2 id="profile-did-name" class="digital-id-name"></h2>
                <p id="profile-did-event" class="digital-id-event"></p>
            </div>

            <div class="digital-id-qr-wrap" id="profileDidQrWrap">
                <div class="digital-id-qr-skeleton" aria-hidden="true"></div>
                <img id="profile-did-qr" class="digital-id-qr" src="" alt="QR Code">
            </div>

            <div class="digital-id-token-row">
                <div>
                    <p class="digital-id-token-label">Token</p>
                    <p id="profile-did-token" class="digital-id-token-value"></p>
                </div>
                <button id="copyProfileTokenBtn" class="digital-id-copy-btn" type="button">Copy</button>
            </div>

            <div class="digital-id-actions">
                <a id="profile-did-download" href="#" target="_blank" class="digital-id-download">Download QR PNG</a>
                <button id="profile-did-resend-email" type="button" class="digital-id-resend">Resend Email</button>
            </div>
            <p id="profile-did-email-status" class="digital-id-email-status" role="status" aria-live="polite"></p>
            <p id="profile-did-email-meta" class="digital-id-email-meta" aria-live="polite"></p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            var button = document.getElementById('openProfileDigitalIdModal');
            var modal = document.getElementById('profileDigitalIdModal');
            var closeButton = document.getElementById('closeProfileDigitalId');
            var copyButton = document.getElementById('copyProfileTokenBtn');
            var resendEmailBtn = document.getElementById('profile-did-resend-email');
            var emailStatus = document.getElementById('profile-did-email-status');
            var emailMeta = document.getElementById('profile-did-email-meta');
            var qrWrap = document.getElementById('profileDidQrWrap');
            var csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            var csrfTokenInput = document.querySelector('input[name="_token"]');
            var csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : (csrfTokenInput ? csrfTokenInput.value : '');
            var currentResendUrl = '';

            var setEmailStatus = function (message, type) {
                if (!emailStatus) {
                    return;
                }

                emailStatus.innerText = message || '';
                emailStatus.classList.remove('success', 'error');

                if (type) {
                    emailStatus.classList.add(type);
                }
            };

            var setEmailMeta = function (message) {
                if (!emailMeta) {
                    return;
                }

                emailMeta.innerText = message || '';
            };

            if (!button || !modal) {
                return;
            }

            var closeModal = function () {
                modal.classList.remove('is-visible');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';

                var qr = document.getElementById('profile-did-qr');
                if (qr) {
                    qr.src = '';
                }

                if (qrWrap) {
                    qrWrap.classList.remove('is-loading');
                }

                currentResendUrl = '';
                setEmailStatus('');
                setEmailMeta('');

                if (resendEmailBtn) {
                    resendEmailBtn.disabled = false;
                    resendEmailBtn.innerText = 'Resend Email';
                }
            };

            button.addEventListener('click', function () {
                var participantId = button.dataset.participantId || '';
                var participantName = button.dataset.name || '';
                var eventName = button.dataset.event || '';
                var token = button.dataset.token || '';
                var baseUrl = button.dataset.qr || '';
                currentResendUrl = button.dataset.resendUrl || '';

                var didName = document.getElementById('profile-did-name');
                var didEvent = document.getElementById('profile-did-event');
                var didToken = document.getElementById('profile-did-token');
                var didQr = document.getElementById('profile-did-qr');
                var didDownload = document.getElementById('profile-did-download');

                if (didName) {
                    didName.innerText = participantName;
                }

                if (didEvent) {
                    didEvent.innerText = 'Event: ' + eventName;
                }

                if (didToken) {
                    didToken.innerText = token || 'Not available';
                }

                if (qrWrap) {
                    qrWrap.classList.add('is-loading');
                }

                if (didQr && baseUrl) {
                    didQr.onload = function () {
                        if (qrWrap) {
                            qrWrap.classList.remove('is-loading');
                        }
                    };

                    didQr.onerror = function () {
                        if (qrWrap) {
                            qrWrap.classList.remove('is-loading');
                        }
                    };

                    didQr.src = baseUrl + '?format=qr';
                }

                if (didDownload && baseUrl) {
                    didDownload.href = baseUrl + '?download=1';
                }

                setEmailStatus('');
                setEmailMeta('');

                if (resendEmailBtn) {
                    resendEmailBtn.disabled = !currentResendUrl;
                    resendEmailBtn.innerText = 'Resend Email';
                }

                modal.classList.add('is-visible');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            });

            if (closeButton) {
                closeButton.addEventListener('click', closeModal);
            }

            if (modal) {
                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        closeModal();
                    }
                });
            }

            if (copyButton) {
                copyButton.addEventListener('click', function () {
                    var tokenEl = document.getElementById('profile-did-token');
                    if (!tokenEl) {
                        return;
                    }

                    navigator.clipboard.writeText(tokenEl.innerText).then(function () {
                        copyButton.innerText = 'Copied!';
                        window.setTimeout(function () {
                            copyButton.innerText = 'Copy';
                        }, 1500);
                    });
                });
            }

            if (resendEmailBtn) {
                resendEmailBtn.addEventListener('click', function () {
                    if (!currentResendUrl || !csrfToken) {
                        setEmailStatus('Unable to resend email right now.', 'error');
                        return;
                    }

                    resendEmailBtn.disabled = true;
                    resendEmailBtn.innerText = 'Sending...';
                    setEmailStatus('Sending Digital ID email...');

                    fetch(currentResendUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(function (response) {
                            return response.json().then(function (data) {
                                if (!response.ok) {
                                    var message = (data && data.message) ? data.message : 'Failed to resend Digital ID email.';
                                    throw new Error(message);
                                }

                                return data;
                            });
                        })
                        .then(function (data) {
                            setEmailStatus((data && data.message) ? data.message : 'Digital ID email resent successfully.', 'success');

                            var now = new Date();
                            var formatted = now.toLocaleString([], {
                                month: 'short',
                                day: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            setEmailMeta('Last sent: ' + formatted);
                        })
                        .catch(function (error) {
                            setEmailStatus(error.message || 'Failed to resend Digital ID email.', 'error');
                        })
                        .finally(function () {
                            resendEmailBtn.disabled = false;
                            resendEmailBtn.innerText = 'Resend Email';
                        });
                });
            }

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeModal();
                }
            });
        })();
    </script>
@endpush
