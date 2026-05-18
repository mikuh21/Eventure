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
    }

    .verify-id-fail-copy {
        margin: 0;
        color: #7c2d12;
        font-size: 13px;
    }

    .verify-id-attendance {
        margin-top: 10px;
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
                        <p class="verify-id-event">{{ $participant->event->title }}</p>
                        <div class="verify-id-attendance">
                            <span class="badge-pill {{ $participant->attended ? 'badge-attended-yes' : 'badge-attended-no' }}">
                                {{ $participant->attended ? 'Attended' : 'Not Attended' }}
                            </span>
                        </div>
                    </div>
                @else
                    <div class="verify-id-result fail">
                        <p class="verify-id-result-title">✗ Invalid Token</p>
                        <p class="verify-id-fail-copy">No participant found matching this token.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pasteButton = document.getElementById('pasteTokenBtn');
        const tokenInput = document.getElementById('token');
        const status = document.getElementById('pasteTokenStatus');

        if (!pasteButton || !tokenInput || !status) {
            return;
        }

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
    });
</script>
@endpush
