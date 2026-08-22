@extends('layouts.app')

@section('title', 'Verify Digital ID - Eventure')

@push('styles')
<style>
    .verify-id-page {
        --ef-ice: #E8F4FD;
        --ef-sky: #BFDFFF;
        --ef-steel: #5BA4CF;
        --ef-ocean: #1B6CA8;
        --ef-midnight: #0A2342;
        --ef-coral: #FF6B35;
        font-family: 'Sora', sans-serif;
        color: var(--ef-midnight);
    }

    .verify-id-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 28px;
        flex-wrap: wrap;
    }

    .verify-id-eyebrow {
        margin: 0 0 4px;
        font-size: 11px;
        color: var(--ef-steel);
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-weight: 600;
    }

    .verify-id-title {
        margin: 0;
        font-size: 26px;
        font-weight: 600;
        color: var(--ef-midnight);
        line-height: 1.2;
    }

    .verify-id-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid var(--ef-sky);
        color: var(--ef-ocean);
        background: #fff;
        border-radius: 8px;
        padding: 9px 18px;
        font-size: 13px;
        text-decoration: none;
        transition: background 150ms ease;
    }

    .verify-id-back:hover {
        background: var(--ef-ice);
    }

    .verify-id-back svg {
        width: 16px;
        height: 16px;
    }

    .verify-id-card {
        background: #fff;
        border: 1px solid var(--ef-sky);
        border-radius: 16px;
        padding: 32px;
        max-width: 640px;
        margin: 0 auto;
        box-shadow: 0 2px 16px rgba(10, 35, 66, 0.07);
    }

    .verify-id-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .verify-id-hint {
        display: inline-flex;
        align-items: center;
        color: var(--ef-ocean);
        font-size: 13px;
    }

    .verify-id-hint svg {
        width: 24px;
        height: 24px;
        color: var(--ef-steel);
        margin-right: 10px;
        flex-shrink: 0;
    }

    .verify-id-badge {
        background: var(--ef-ice);
        color: var(--ef-ocean);
        border: 1px solid var(--ef-sky);
        border-radius: 999px;
        font-size: 11px;
        padding: 3px 10px;
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    .verify-id-divider {
        height: 1px;
        background: var(--ef-ice);
        margin-bottom: 24px;
    }

    .verify-id-field {
        margin-bottom: 10px;
    }

    .verify-id-label {
        display: block;
        margin-bottom: 6px;
        font-size: 12px;
        font-weight: 500;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--ef-steel);
    }

    .verify-id-sublabel {
        margin: 0 0 8px;
        font-size: 12px;
        color: var(--ef-ocean);
    }

    .verify-id-input {
        width: 100%;
        border: 1px solid var(--ef-sky);
        border-radius: 8px;
        padding: 11px 50px 11px 16px;
        color: var(--ef-midnight);
        background: #fff;
        box-sizing: border-box;
    }

    .verify-id-input-wrap {
        position: relative;
    }

    .verify-id-paste-btn {
        position: absolute;
        top: 50%;
        right: 8px;
        transform: translateY(-50%);
        width: 32px;
        height: 32px;
        border: 1px solid var(--ef-sky);
        border-radius: 8px;
        background: #fff;
        color: var(--ef-ocean);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 150ms ease;
    }

    .verify-id-paste-btn:hover {
        background: var(--ef-ice);
    }

    .verify-id-paste-btn svg {
        width: 16px;
        height: 16px;
    }

    .verify-id-paste-status {
        margin: 6px 0 0;
        font-size: 12px;
        color: var(--ef-steel);
    }

    .verify-id-paste-status:empty {
        display: none;
    }

    .verify-id-paste-status.error {
        color: var(--ef-coral);
    }

    .verify-id-input {
        font-size: 14px;
        font-family: 'Sora', monospace;
    }

    .verify-id-input:focus {
        border-color: var(--ef-steel);
        outline: none;
        box-shadow: 0 0 0 3px rgba(91, 164, 207, 0.15);
    }

    .verify-id-submit {
        width: 100%;
        border: none;
        border-radius: 10px;
        padding: 13px;
        margin-top: 8px;
        background: var(--ef-ocean);
        color: #fff;
        font-size: 15px;
        font-weight: 600;
        font-family: 'Sora', sans-serif;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background 200ms ease;
    }

    .verify-id-submit:hover {
        background: var(--ef-midnight);
    }

    .verify-id-submit svg {
        width: 18px;
        height: 18px;
    }

    .verify-id-result {
        margin-top: 20px;
        padding: 16px 20px;
        border-radius: 0 10px 10px 0;
    }

    .verify-id-result.success {
        border-left: 4px solid #10b981;
        background: #f0fdf4;
    }

    .verify-id-result.fail {
        border-left: 4px solid var(--ef-coral);
        background: #fff5f0;
    }

    .verify-id-result-title {
        margin: 0 0 8px;
        font-size: 14px;
        font-weight: 600;
    }

    .verify-id-result.success .verify-id-result-title {
        color: #065f46;
    }

    .verify-id-result.fail .verify-id-result-title {
        color: #cc4a1a;
    }

    .verify-id-name {
        margin: 0 0 4px;
        color: var(--ef-midnight);
        font-size: 15px;
        font-weight: 500;
    }

    .verify-id-event {
        margin: 0;
        color: var(--ef-ocean);
        font-size: 13px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .verify-id-fail-copy {
        margin: 0;
        color: #7c2d12;
        font-size: 13px;
    }

    .verify-id-attendance {
        margin-top: 10px;
    }

    .verify-id-photo {
        width: 100%;
        height: 480px;
        margin-top: 16px;
        border-radius: 10px;
        object-fit: contain;
        object-position: center;
        background: #ffffff;
        display: block;
    }

    .verify-id-photo-empty {
        margin-top: 16px;
        color: #065f46;
        font-size: 13px;
    }

    .badge-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        min-width: 34px;
    }

    .badge-attended-yes {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-attended-no {
        background: #fee2e2;
        color: #991b1b;
    }

    @media (max-width: 640px) {
        .verify-id-card {
            padding: 22px;
        }

        .verify-id-photo {
            height: 318px;
        }
    }

    .fab-qr-scanner {
        position: fixed;
        right: 20px;
        bottom: 20px;
        z-index: 1200;
        width: 56px;
        height: 56px;
        border: 0;
        border-radius: 999px;
        background: #1B6CA8;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 24px rgba(10, 35, 66, 0.24);
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        transform: translateZ(0);
        transition: background-color 160ms ease, box-shadow 160ms ease;
    }

    .fab-qr-scanner:hover,
    .fab-qr-scanner:focus-visible {
        background: #0A2342;
        box-shadow: 0 12px 28px rgba(10, 35, 66, 0.28);
    }

    .fab-qr-scanner:focus-visible {
        outline: 3px solid rgba(91, 164, 207, 0.35);
        outline-offset: 3px;
    }

    .fab-qr-scanner svg {
        width: 24px;
        height: 24px;
        pointer-events: none;
    }

    .qr-scanner-modal {
        position: fixed;
        inset: 0;
        background: rgba(10, 35, 66, 0.55);
        z-index: 1300;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 180ms ease, visibility 0s linear 180ms;
    }

    .qr-scanner-modal.is-visible {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transition: opacity 180ms ease;
    }

    .qr-scanner-panel {
        background: #ffffff;
        border-radius: 16px;
        width: 100%;
        max-width: 380px;
        padding: 28px 18px 18px;
        box-shadow: 0 8px 40px rgba(10, 35, 66, 0.18);
        position: relative;
        text-align: center;
    }

    .qr-scanner-title {
        margin: 0 0 18px;
        font-size: 1.2rem;
        color: var(--ef-ocean);
        font-family: 'Sora', sans-serif;
    }

    .qr-scanner-close {
        position: absolute;
        top: 12px;
        right: 16px;
        background: none;
        border: none;
        font-size: 24px;
        color: var(--ef-steel);
        cursor: pointer;
    }

    .qr-scan-status {
        margin-top: 16px;
        font-size: 14px;
        color: #065f46;
        min-height: 24px;
        font-family: 'Sora', sans-serif;
    }

    @media (max-width: 900px) {
        .fab-qr-scanner {
            right: 14px;
            bottom: 14px;
            width: 50px;
            height: 50px;
        }

        .qr-scanner-panel {
            max-width: 98vw;
            padding: 18px 10px 12px;
        }
    }
</style>
@endpush

@section('content')
    <div class="verify-id-page">
        <div class="verify-id-header">
            <div>
                <h1 class="verify-id-title">Verify Digital ID</h1>
            </div>
        </div>

        <div class="verify-id-card">
            <div class="verify-id-card-head">
                <div class="verify-id-hint">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 4 7v6c0 5 3.4 7.7 8 8.9 4.6-1.2 8-3.9 8-8.9V7l-8-4Z"></path><path d="M9 12.5l2 2 4-4"></path></svg>
                    <span>Enter token to verify a participant</span>
                </div>
                <span class="verify-id-badge">{{ auth()->user()->hasRole('event_staff') ? 'Event Staff Only' : 'Admin Only' }}</span>
            </div>
            <div class="verify-id-divider"></div>

            <form action="{{ route('admin.digital-id.verify.check') }}" method="POST">
                @csrf
                <div class="verify-id-field">
                    <label class="verify-id-label" for="token">Token</label>
                    <p class="verify-id-sublabel">Paste the participant digital ID token</p>
                    <div class="verify-id-input-wrap">
                        <input
                            class="verify-id-input"
                            id="token"
                            name="token"
                            type="text"
                            value="{{ old('token', $submittedToken ?? '') }}"
                            placeholder="e.g. b2c70199-0436-4945-88bb-443f7fab54a3"
                        >
                        <button class="verify-id-paste-btn" id="pasteTokenBtn" type="button" aria-label="Paste token" title="Paste token">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                        </button>
                    </div>
                    <p class="verify-id-paste-status" id="pasteTokenStatus" aria-live="polite"></p>
                </div>

                <button class="verify-id-submit" type="submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m21 21-4.3-4.3"></path><path d="m8.8 11 1.7 1.7 3.3-3.3"></path></svg>
                    <span>Verify Participant</span>
                </button>
            </form>

            @if (($verificationAttempted ?? false) === true)
                @if ($participant)
                    <div class="verify-id-result success">
                        <p class="verify-id-result-title">✓ Verified</p>
                        <p class="verify-id-name">{{ $participant->name }}</p>
                        <div style="margin-top:8px;font-size:13px;color:var(--ef-midnight);">
                            <div><strong>Type:</strong> {{ $participant instanceof \App\Models\Guest ? 'Guest' : 'Participant' }}</div>
                            <div><strong>Role:</strong> {{ ucfirst(str_replace('_',' ', $participant->participant_type ?? $participant->role ?? 'N/A')) }}</div>
                            @if(!empty($participant->institution))
                                <div><strong>Institution:</strong> {{ $participant->institution }}</div>
                            @endif
                            @if(!empty($participant->digital_id_verified_at))
                                <div><strong>Attended At:</strong> {{ $participant->digital_id_verified_at->format('F j, Y g:i A') }}</div>
                            @endif
                            @if(!empty($verificationCheckedAt))
                                <div><strong>Verified At:</strong> {{ $verificationCheckedAt->format('F j, Y g:i A') }}</div>
                            @endif
                        </div>
                        <p class="verify-id-event" style="margin-top:10px;">
                            @php
                                $event = $participant->event ?? null;
                                $segments = [];
                                if ($event) {
                                    $segments[] = $event->title;
                                    if ($event->start_date) {
                                        $segments[] = $event->start_date->format('F j, Y');
                                    }
                                    if (!empty($event->location)) {
                                        $segments[] = $event->location;
                                    }
                                }
                            @endphp
                            {{ implode(' • ', array_filter($segments)) }}
                        </p>
                        <div class="verify-id-attendance">
                            <span class="badge-pill {{ $participant->attended ? 'badge-attended-yes' : 'badge-attended-no' }}">
                                {{ $participant->attended ? 'Attended' : 'Not Attended' }}
                            </span>
                        </div>
                        @php
                            $participantPhotoPath = trim((string) ($participant->photo_path ?? ''));
                            $participantPhotoUrl = null;

                            if ($participantPhotoPath !== '') {
                                if (str_starts_with($participantPhotoPath, 'http://') || str_starts_with($participantPhotoPath, 'https://')) {
                                    $participantPhotoUrl = $participantPhotoPath;
                                } else {
                                    try {
                                        $participantPhotoUrl = \Illuminate\Support\Facades\Storage::disk('participant-photos')->url(basename($participantPhotoPath));
                                    } catch (\Throwable $exception) {
                                        $participantPhotoUrl = null;
                                    }
                                }
                            }
                        @endphp
                        @if ($participantPhotoUrl)
                            <img class="verify-id-photo" src="{{ $participantPhotoUrl }}" alt="Participant photo for {{ $participant->name }}">
                        @else
                            <div class="verify-id-photo-empty">No photo submitted</div>
                        @endif
                    </div>
                @else
                    <div class="verify-id-result fail">
                        <p class="verify-id-result-title">✗ Not Available</p>
                        <p class="verify-id-fail-copy">No record available.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <button id="openQrScannerFab" class="fab-qr-scanner" type="button" title="Scan QR for Verification" aria-label="Scan QR for Verification">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="3" y="3" width="7" height="7" rx="2"></rect>
            <rect x="14" y="3" width="7" height="7" rx="2"></rect>
            <rect x="14" y="14" width="7" height="7" rx="2"></rect>
            <rect x="3" y="14" width="7" height="7" rx="2"></rect>
        </svg>
    </button>

    <div id="qrScannerModal" class="qr-scanner-modal" aria-hidden="true">
        <div class="qr-scanner-panel" role="dialog" aria-modal="true" aria-labelledby="qrScannerTitle">
            <button id="closeQrScannerModal" class="qr-scanner-close" type="button" aria-label="Close QR Scanner">&times;</button>
            <h2 id="qrScannerTitle" class="qr-scanner-title">Scan Digital ID</h2>
            <div id="qr-reader" style="width:100%;max-width:340px;margin:auto;"></div>
            <div id="qr-scan-status" class="qr-scan-status" aria-live="polite"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pasteButton = document.getElementById('pasteTokenBtn');
        const tokenInput = document.getElementById('token');
        const status = document.getElementById('pasteTokenStatus');
        const qrFab = document.getElementById('openQrScannerFab');
        const qrModal = document.getElementById('qrScannerModal');
        const qrCloseButton = document.getElementById('closeQrScannerModal');
        const qrStatus = document.getElementById('qr-scan-status');
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
        
        let qrScanner = null;

        if (!pasteButton || !tokenInput || !status) {
            return;
        }

        // Paste button functionality for manual token entry
        pasteButton.addEventListener('click', async function () {
            status.classList.remove('error');
            status.textContent = '';

            if (!navigator.clipboard || typeof navigator.clipboard.readText !== 'function') {
                status.classList.add('error');
                status.textContent = 'Clipboard paste is not supported in this browser.';

                return;
            }

            try {
                const clipboardText = (await navigator.clipboard.readText()).trim();

                if (!clipboardText) {
                    status.classList.add('error');
                    status.textContent = 'Clipboard is empty.';

                    return;
                }

                tokenInput.value = clipboardText;
                tokenInput.dispatchEvent(new Event('input', { bubbles: true }));
                tokenInput.focus();
                status.textContent = 'Token pasted from clipboard.';
            } catch (error) {
                status.classList.add('error');
                status.textContent = 'Clipboard permission denied. Please paste manually.';
            }
        });

        // QR Scanner functionality
        const startQrScanner = function () {
            if (!qrModal || !window.Html5Qrcode || qrScanner) {
                return;
            }

            qrScanner = new Html5Qrcode('qr-reader');
            qrScanner.start(
                { facingMode: 'environment' },
                { fps: 10, qrbox: 220 },
                function (decodedText) {
                    if (!qrStatus) {
                        return;
                    }

                    qrStatus.innerText = 'Checking...';
                    qrStatus.style.color = '#065f46';

                    if (qrScanner) {
                        qrScanner.stop().then(function () {
                            qrScanner.clear();
                            qrScanner = null;
                        }).catch(function () {
                            qrScanner = null;
                        });
                    }

                    fetch("{{ route('admin.digital-id.scan') }}", {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ payload: decodedText })
                    })
                        .then(function (response) {
                            return response.json().then(function (data) {
                                if (!response.ok) {
                                    var message = (data && data.message) ? data.message : 'Invalid QR code.';
                                    throw new Error(message);
                                }

                                return data;
                            });
                        })
                        .then(function (data) {
                            qrStatus.innerText = (data && data.message) ? data.message : 'Verification successful.';
                            qrStatus.style.color = '#065f46';

                            // Update the page with participant details
                            if (data.participant) {
                                updateVerificationResult(data.participant);
                            }

                            window.setTimeout(function () {
                                closeQrModal();
                            }, 1200);
                        })
                        .catch(function (error) {
                            qrStatus.innerText = error.message || 'Scan failed. Try again.';
                            qrStatus.style.color = '#b91c1c';

                            window.setTimeout(function () {
                                startQrScanner();
                            }, 900);
                        });
                },
                function () {}
            ).catch(function () {
                if (qrStatus) {
                    qrStatus.innerText = 'Unable to access the camera in this preview.';
                    qrStatus.style.color = '#b91c1c';
                }
                qrScanner = null;
            });
        };

        const stopQrScanner = function () {
            if (!qrScanner) {
                return;
            }

            qrScanner.stop().then(function () {
                qrScanner.clear();
                qrScanner = null;
            }).catch(function () {
                qrScanner = null;
            });
        };

        const openQrModal = function () {
            if (!qrModal) {
                return;
            }

            qrModal.classList.add('is-visible');
            qrModal.setAttribute('aria-hidden', 'false');

            if (qrStatus) {
                qrStatus.innerText = '';
            }

            window.setTimeout(startQrScanner, 150);
        };

        const closeQrModal = function () {
            if (!qrModal) {
                return;
            }

            qrModal.classList.remove('is-visible');
            qrModal.setAttribute('aria-hidden', 'true');
            stopQrScanner();
        };

        const updateVerificationResult = function (participant) {
            // Build result HTML
            const typeLabel = participant.type ? (participant.type === 'guest' ? 'Guest' : 'Participant') : 'Participant';
            const roleText = participant.role ? participant.role.replace(/_/g, ' ') : 'N/A';
            const roleLabel = roleText
                .split(' ')
                .map(segment => segment.charAt(0).toUpperCase() + segment.slice(1).toLowerCase())
                .join(' ');
            let attendedAt = '';
            let verifiedAt = '';

            if (participant.digital_id_verified_at) {
                const parsed = new Date(participant.digital_id_verified_at);
                if (!Number.isNaN(parsed.getTime())) {
                    attendedAt = parsed.toLocaleString('en-US', {
                        month: 'long',
                        day: 'numeric',
                        year: 'numeric',
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true,
                    });
                }
            }

            if (participant.verified_at) {
                const parsedChecked = new Date(participant.verified_at);
                if (!Number.isNaN(parsedChecked.getTime())) {
                    verifiedAt = parsedChecked.toLocaleString('en-US', {
                        month: 'long',
                        day: 'numeric',
                        year: 'numeric',
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true,
                    });
                }
            }

            const eventSegments = [];
            if (participant.event_name) {
                eventSegments.push(participant.event_name);
            }
            if (participant.event_date) {
                eventSegments.push(participant.event_date);
            }
            if (participant.event_location) {
                eventSegments.push(participant.event_location);
            }
            const eventLine = eventSegments.join(' • ');
            const photoMarkup = participant.photo_url
                ? `<img class="verify-id-photo" src="${participant.photo_url}" alt="Participant photo for ${participant.name}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"><div class="verify-id-photo-empty" style="display:none;">No photo submitted</div>`
                : '<div class="verify-id-photo-empty">No photo submitted</div>';

            const resultHtml = `
                <div class="verify-id-result success">
                    <p class="verify-id-result-title">✓ Verified</p>
                    <p class="verify-id-name">${participant.name}</p>
                    <div style="margin-top:8px;font-size:13px;color:var(--ef-midnight);">
                        <div><strong>Type:</strong> ${typeLabel}</div>
                        <div><strong>Role:</strong> ${roleLabel}</div>
                        ${participant.institution ? `<div><strong>Institution:</strong> ${participant.institution}</div>` : ''}
                        ${attendedAt ? `<div><strong>Attended At:</strong> ${attendedAt}</div>` : ''}
                        ${verifiedAt ? `<div><strong>Verified At:</strong> ${verifiedAt}</div>` : ''}
                    </div>
                    <p class="verify-id-event" style="margin-top:10px;">${eventLine}</p>
                    <div class="verify-id-attendance">
                        <span class="badge-pill ${participant.attended ? 'badge-attended-yes' : 'badge-attended-no'}">
                            ${participant.attended ? 'Attended' : 'Not Attended'}
                        </span>
                    </div>
                    ${photoMarkup}
                </div>
            `;

            // Remove existing result if present
            const existingResult = document.querySelector('.verify-id-result');
            if (existingResult) {
                existingResult.remove();
            }

            // Add new result after the form
            const verifyForm = document.querySelector('form[action*="verify/check"]');
            if (verifyForm) {
                verifyForm.insertAdjacentHTML('afterend', resultHtml);
            }
        };

        // Event listeners for QR scanner
        if (qrFab) {
            qrFab.addEventListener('click', openQrModal);
        }

        if (qrCloseButton) {
            qrCloseButton.addEventListener('click', closeQrModal);
        }

        // Close modal on escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && qrModal && qrModal.classList.contains('is-visible')) {
                closeQrModal();
            }
        });

        // Close modal on background click
        if (qrModal) {
            qrModal.addEventListener('click', function (e) {
                if (e.target === qrModal) {
                    closeQrModal();
                }
            });
        }
    });
</script>
@endpush
