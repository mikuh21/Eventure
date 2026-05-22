<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventure Digital ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0A2342;
            --panel: rgba(255, 255, 255, 0.05);
            --panel-border: rgba(191, 223, 255, 0.15);
            --text: #ffffff;
            --muted: #bfdfff;
            --muted-soft: rgba(191, 223, 255, 0.7);
            --muted-faint: rgba(191, 223, 255, 0.4);
            --muted-fainter: rgba(191, 223, 255, 0.25);
            --brand: #5BA4CF;
            --brand-strong: #1B6CA8;
            --success: #10b981;
            --card-gradient: linear-gradient(135deg, #1B6CA8 0%, #0A2342 60%, #0d1f3c 100%);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            --radius: 16px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Sora', sans-serif;
            background:
                radial-gradient(circle at top, rgba(91, 164, 207, 0.16), transparent 34%),
                linear-gradient(180deg, #0c2c53 0%, var(--bg) 28%, #071b33 100%);
            color: var(--text);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .page {
            width: 100%;
            max-width: 420px;
            min-height: 100vh;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }

        .page::before,
        .page::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .page::before {
            width: 240px;
            height: 240px;
            right: -120px;
            top: 84px;
            background: radial-gradient(circle, rgba(91, 164, 207, 0.2), transparent 70%);
        }

        .page::after {
            width: 180px;
            height: 180px;
            left: -90px;
            bottom: 220px;
            background: radial-gradient(circle, rgba(27, 108, 168, 0.22), transparent 70%);
        }

        .topbar {
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            position: relative;
            z-index: 1;
        }

        .wordmark {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .wordmark-event {
            color: var(--text);
        }

        .wordmark-flow {
            color: var(--brand);
        }

        .section-card {
            position: relative;
            z-index: 1;
        }

        .digital-id-section {
            padding: 24px 20px 0;
        }

        .section-label {
            margin: 0 0 12px;
            text-align: center;
            font-size: 10px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--brand);
        }

        .flip-card {
            width: 100%;
            max-width: 380px;
            height: 220px;
            margin: 0 auto;
            perspective: 1000px;
            cursor: pointer;
        }

        .flip-card-inner {
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            -webkit-transform-style: preserve-3d;
            transform-style: preserve-3d;
            position: relative;
            width: 100%;
            height: 100%;
        }

        .flip-card-inner.flipped {
            transform: rotateY(180deg);
        }

        .flip-card-front,
        .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            background: var(--card-gradient);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .flip-card-front {
            padding: 20px 24px;
            z-index: 2;
        }

        .flip-card-back {
            transform: rotateY(180deg);
            padding: 20px 24px;
            z-index: 1;
        }

        /* When flipped, ensure the back is on top for visibility on WebKit browsers */
        .flip-card-inner.flipped .flip-card-front {
            z-index: 1;
        }

        .flip-card-inner.flipped .flip-card-back {
            z-index: 2;
        }

        .card-circle-lg,
        .card-circle-sm {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .card-circle-lg {
            width: 140px;
            height: 140px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            top: -40px;
            right: -40px;
        }

        .card-circle-sm {
            width: 80px;
            height: 80px;
            border: 1px solid rgba(91, 164, 207, 0.12);
            bottom: -20px;
            left: -20px;
        }

        .card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            position: relative;
            z-index: 1;
        }

        .mini-brand {
            font-size: 12px;
            font-weight: 700;
            margin: 0 0 4px;
        }

        .event-name {
            margin: 0;
            font-size: 10px;
            color: var(--muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 190px;
        }

        .status-pill {
            padding: 3px 8px;
            border-radius: 999px;
            border: 1px solid var(--success);
            background: rgba(16, 185, 129, 0.2);
            color: var(--success);
            font-size: 9px;
            font-weight: 600;
            white-space: nowrap;
        }

        .participant-name {
            margin: 16px 0 4px;
            font-size: 22px;
            font-weight: 700;
            line-height: 1.15;
            position: relative;
            z-index: 1;
        }

        .participant-role {
            margin: 0;
            font-size: 11px;
            color: var(--muted);
            position: relative;
            z-index: 1;
        }

        .card-bottom {
            margin-top: 26px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            position: relative;
            z-index: 1;
        }

        .survey-form-progress {
            padding: 16px 20px 24px;
            background: transparent;
            border-bottom: none;
        }

        .survey-form-step-label {
            font-size: 13px;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 8px;
            font-family: 'Sora', sans-serif;
        }

        .survey-form-progress-bar {
            height: 6px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 999px;
            overflow: hidden;
            position: relative;
        }

        .survey-form-progress-bar::after {
            content: '';
            display: block;
            height: 100%;
            width: var(--progress, 0%);
            background: #38bdf8;
            border-radius: 999px;
            transition: width 0.3s ease;
        }

        .meta-label,
        .token-label,
        .back-validity-label {
            font-size: 9px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .meta-value,
        .back-validity-value {
            margin-top: 4px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        .qr-thumb,
        .qr-large {
            background: #ffffff;
            border-radius: 10px;
            padding: 6px;
            object-fit: contain;
            display: block;
        }

        .qr-thumb {
            width: 60px;
            height: 60px;
            padding: 3px;
            border-radius: 6px;
        }

        .back-strip {
            height: 36px;
            margin: -20px -24px 16px;
            padding: 0 16px;
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.07);
            font-size: 10px;
        }

        .qr-large {
            width: 110px;
            height: 110px;
            margin: 0 auto 14px;
        }

        .token-label {
            text-align: center;
            letter-spacing: 0.1em;
            margin-bottom: 4px;
        }

        .token-value {
            margin: 0;
            padding: 0 12px;
            text-align: center;
            color: var(--text);
            font-family: monospace;
            font-size: 10px;
            word-break: break-all;
        }

        .participant-email {
            margin: 16px 0 0;
            text-align: center;
            font-size: 10px;
            color: var(--muted);
        }

        .back-validity {
            margin-top: 8px;
            text-align: center;
        }

        .flip-hint {
            margin: 10px 0 0;
            text-align: center;
            font-size: 11px;
            color: rgba(191, 223, 255, 0.6);
        }

        .actions {
            margin: 20px 20px 0;
            display: flex;
            gap: 10px;
            flex-wrap: nowrap;
            position: relative;
            z-index: 1;
        }

        .actions > * {
            flex: 1 1 calc(50% - 5px);
        }

        .action-link,
        .action-button {
            min-height: 48px;
            border-radius: 10px;
            padding: 12px;
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
        }

        .action-link {
            background: var(--brand-strong);
            color: var(--text);
            border: 1px solid transparent;
        }

        .action-button {
            background: transparent;
            color: var(--brand);
            border: 1px solid var(--brand);
        }

        .action-link[disabled],
        .action-button[disabled] {
            opacity: 0.72;
            cursor: not-allowed;
        }

        .export-host {
            position: fixed;
            left: -10000px;
            top: 0;
            width: 380px;
            height: 220px;
            pointer-events: none;
            z-index: -1;
        }

        .export-face {
            position: relative !important;
            transform: none !important;
            backface-visibility: visible !important;
            -webkit-backface-visibility: visible !important;
            width: 380px !important;
            height: 220px !important;
            margin: 0 !important;
        }

        .action-link:active,
        .action-button:active,
        .survey-button:active {
            transform: translateY(1px);
        }

        .divider {
            margin: 28px 20px 0;
            height: 1px;
            background: rgba(191, 223, 255, 0.12);
            position: relative;
            z-index: 1;
        }

        .survey-section {
            padding: 24px 20px;
            position: relative;
            z-index: 1;
        }

        .survey-label {
            margin: 0 0 8px;
            font-size: 10px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--brand);
        }

        .survey-card {
            background: var(--panel);
            border: 1px solid var(--panel-border);
            border-radius: 14px;
            padding: 20px;
            backdrop-filter: blur(12px);
        }

        .survey-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 8px;
            font-size: 15px;
            font-weight: 600;
        }

        .survey-subtext {
            margin: 0 0 16px;
            font-size: 12px;
            color: var(--muted-soft);
        }

        .survey-button {
            display: block;
            width: 100%;
            text-align: center;
            border-radius: 10px;
            padding: 13px;
            background: var(--brand);
            color: var(--text);
            font-size: 14px;
            font-weight: 600;
            font-family: 'Sora', sans-serif;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .survey-button:hover,
        .survey-button:focus-visible {
            background: var(--brand-strong);
        }

        .survey-form-overlay {
            position: fixed;
            inset: 0;
            background: rgba(10, 35, 66, 0.88);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 50;
            opacity: 0;
            transition: opacity 300ms ease;
            pointer-events: none;
        }

        .survey-form-overlay.is-visible {
            opacity: 1;
            pointer-events: auto;
        }

        .survey-form-shell {
            position: relative;
            width: min(100%, 420px);
            max-height: min(100%, 840px);
            overflow: auto;
            background: rgba(6, 18, 35, 0.98);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            box-shadow: 0 28px 60px rgba(0, 0, 0, 0.4);
            padding: 24px;
        }

        .survey-confirmation-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(10, 35, 66, 0.92);
            border-radius: 24px;
            padding: 20px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 200ms ease;
        }

        .survey-confirmation-overlay.is-visible {
            opacity: 1;
            pointer-events: auto;
        }

        .survey-confirmation-shell {
            width: min(100%, 380px);
            max-width: 380px;
            background: rgba(3, 11, 25, 0.98);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 22px;
            padding: 22px;
            box-shadow: 0 24px 50px rgba(0, 0, 0, 0.35);
            text-align: center;
        }

        .survey-confirmation-title {
            margin: 0 0 10px;
            font-size: 18px;
            font-weight: 700;
            color: var(--text);
        }

        .survey-confirmation-copy {
            margin: 0 0 22px;
            font-size: 14px;
            color: var(--muted);
            line-height: 1.6;
        }

        .survey-confirmation-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .survey-form-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }

        .survey-form-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: var(--text);
        }

        if (downloadPngButton) {
            const iosSaveTip = document.getElementById('ios-save-tip');

            function isIOS() {
                return /iP(hone|od|ad)/.test(navigator.userAgent) || (navigator.platform && /MacIntel/.test(navigator.platform) && navigator.maxTouchPoints > 1);
            }

            downloadPngButton.addEventListener('click', async () => {
                try {
                    setDownloadState(true, 'Saving to photos...');

                    await waitForCardAssets();

                    const inner = document.querySelector('#flipCard .flip-card-inner');
                    const wasFlipped = inner ? inner.classList.contains('flipped') : false;

                    // Ensure front is visible for capture
                    if (inner) {
                        inner.classList.remove('flipped');
                        await new Promise((r) => requestAnimationFrame(r));
                    }

                    const frontDataUrl = await renderFaceDataUrl('.flip-card-front');

                    // Ensure back is visible for capture
                    if (inner) {
                        inner.classList.add('flipped');
                        await new Promise((r) => requestAnimationFrame(r));
                    }

                    const backDataUrl = await renderFaceDataUrl('.flip-card-back');

                    // Restore original flip state
                    if (inner) {
                        if (!wasFlipped) inner.classList.remove('flipped');
                        else inner.classList.add('flipped');
                        await new Promise((r) => requestAnimationFrame(r));
                    }

                    // Trigger downloads sequentially
                    downloadDataUrl(frontDataUrl, 'digital-id-front.png');
                    setTimeout(() => {
                        downloadDataUrl(backDataUrl, 'digital-id-back.png');
                    }, 800);

                    // Show inline iOS tip if needed
                    if (isIOS() && iosSaveTip) {
                        iosSaveTip.textContent = 'If the image did not save, long press the image and select Save to Photos';
                        iosSaveTip.style.display = 'block';
                    }
                } catch (error) {
                    console.error(error);
                    setButtonLabel(downloadPngButton, 'Save failed');
                    window.setTimeout(() => {
                        setButtonLabel(downloadPngButton, downloadPngButton.dataset.defaultLabel || 'Save ID');
                    }, 1800);
                } finally {
                    setDownloadState(false);
                }
            });
        }
            display: none;
        }

        .survey-step.active {
            display: block;
        }

        .survey-step-title {
            margin: 0 0 14px;
            font-size: 16px;
            color: var(--text);
        }

        .survey-question {
            margin-bottom: 18px;
        }

        .survey-question-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
        }

        .survey-question-help {
            margin: 0 0 10px;
            font-size: 12px;
            color: var(--muted-soft);
        }

        .survey-question-help.text-center {
            text-align: center;
        }

        .survey-form-input,
        .survey-form-textarea {
            width: 100%;
            min-height: 46px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.04);
            color: var(--text);
            padding: 12px 14px;
            font: inherit;
            box-sizing: border-box;
        }

        .survey-form-input[readonly] {
            opacity: 0.84;
            color: rgba(191, 223, 255, 0.85);
            background: rgba(255, 255, 255, 0.03);
            cursor: default;
        }

        .survey-form-input[type=date],
        .survey-form-input[type=time] {
            width: 100%;
            min-height: 46px;
            box-sizing: border-box;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.04);
            padding: 12px 14px;
            color: var(--text);
            font: inherit;
            appearance: none;
            -webkit-appearance: none;
        }

        .survey-form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .survey-form-likert,
        .survey-form-radio-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 12px;
        }

        .survey-form-vertical {
            grid-template-columns: 1fr !important;
        }

        .survey-form-likert-option,
        .survey-form-radio-option {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 52px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.04);
            color: var(--text);
            font-size: 14px;
            cursor: pointer;
            position: relative;
        }

        .survey-form-radio-option {
            padding: 0 4px;
        }

        .survey-form-radio-option span {
            padding: 10px 12px;
        }

        .survey-form-likert-option span,
        .survey-form-radio-option span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            border-radius: inherit;
            padding: 10px 0;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .survey-form-likert-option input,
        .survey-form-radio-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .survey-form-likert-option:hover,
        .survey-form-radio-option:hover {
            border-color: rgba(91, 164, 207, 0.5);
        }

        .survey-form-likert-option input:checked + span,
        .survey-form-radio-option input:checked + span {
            color: var(--text);
            font-weight: 700;
            background: rgba(91, 164, 207, 0.95);
        }

        .survey-form-likert-option input:checked,
        .survey-form-radio-option input:checked {
            border-color: transparent;
        }

        .survey-form-action-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 8px;
            flex-wrap: wrap;
        }

        .survey-form-button {
            flex: 1 1 140px;
            min-height: 50px;
            border-radius: 14px;
            border: none;
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .survey-form-button.primary {
            background: var(--brand);
            color: var(--text);
        }

        .survey-form-button.secondary {
            background: rgba(255, 255, 255, 0.06);
            color: var(--text);
        }

        .survey-form-button[disabled] {
            opacity: 0.55;
            cursor: not-allowed;
        }

        .survey-form-error {
            margin: 18px 0 0;
            font-size: 13px;
            color: #f97316;
            line-height: 1.4;
        }

        .required-star {
            color: #f97316;
            margin-left: 4px;
        }

        @media (max-width: 520px) {
            .survey-form-likert,
            .survey-form-radio-group {
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            }
        }

        .survey-submitted,
        .survey-pending {
            border-radius: 10px;
            padding: 14px;
            text-align: center;
        }

        .survey-submitted {
            background: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .survey-pending {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(191, 223, 255, 0.1);
        }

        .survey-state-title {
            margin: 8px 0 4px;
            font-size: 14px;
            font-weight: 600;
        }

        .survey-state-title.success {
            color: var(--success);
        }

        .survey-state-copy {
            margin: 0;
            font-size: 12px;
            color: var(--muted-soft);
        }

        .footer {
            padding: 32px 20px 40px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .footer .wordmark {
            font-size: 16px;
        }

        .tagline {
            margin: 4px 0 0;
            font-size: 11px;
            color: var(--muted-faint);
        }

        .copyright {
            margin: 12px 0 0;
            font-size: 10px;
            color: var(--muted-fainter);
        }

        .icon {
            width: 16px;
            height: 16px;
            flex: 0 0 auto;
        }

        .icon-lg {
            width: 20px;
            height: 20px;
            flex: 0 0 auto;
        }

        @media (min-width: 421px) {
            body {
                padding: 20px 0;
            }

            .page {
                min-height: calc(100vh - 40px);
                border-radius: 28px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.28);
            }
        }
    </style>
</head>
<body>
    <main class="page" data-participant-name="{{ addslashes($participant->name) }}" data-participant-id="{{ $participant->id }}" data-token="{{ $digitalId->token }}">
        <nav class="topbar" aria-label="Participant navigation">
            <div class="wordmark">
                <span class="wordmark-event">Even</span><span class="wordmark-flow">ture</span>
            </div>
        </nav>

        <section class="digital-id-section section-card">
            <p class="section-label">Your Digital ID</p>

            <div class="flip-card" id="flipCard" role="button" tabindex="0" aria-label="Flip digital ID card">
                <div class="flip-card-inner">
                    <article class="flip-card-front">
                        <span class="card-circle-lg"></span>
                        <span class="card-circle-sm"></span>

                        <div class="card-top">
                            <div>
                                <p class="mini-brand">Eventure</p>
                                <p class="event-name">{{ $participant->event->title }}</p>
                            </div>

                            <div class="status-pill">● CONFIRMED</div>
                        </div>

                        <h1 class="participant-name">{{ $participant->name }}</h1>
                        <p class="participant-role">{{ ucfirst($participant->participant_type ?? '') }} • {{ $participant->event->title }}</p>

                        <div class="card-bottom">
                            <div>
                                <div class="meta-label">Valid Until</div>
                                <div class="meta-value">{{ $validThru }}</div>
                            </div>

                            <img class="qr-thumb" src="{{ $qrUrl }}" alt="Participant QR code">
                        </div>
                    </article>

                    <article class="flip-card-back">
                        <div class="back-strip">Eventure Digital ID</div>

                        <img class="qr-large" src="{{ $qrUrl }}" alt="Participant QR code enlarged">

                        <div class="token-label">Token</div>
                        <p class="token-value">{{ $digitalId->token }}</p>

                        <p class="participant-email">{{ $participant->email }}</p>

                        <div class="back-validity">
                            <div class="back-validity-label">Valid Until</div>
                            <div class="back-validity-value">{{ $validThru }}</div>
                        </div>
                    </article>
                </div>
            </div>

            <p class="flip-hint">Tap card to flip</p>
        </section>

        <section class="actions" aria-label="Digital ID actions">
            <button class="action-link" id="downloadPngButton" type="button" data-default-label="Save ID">
                <svg class="icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 3v11m0 0 4-4m-4 4-4-4M5 17v1a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Save ID</span>
            </button>

            <button class="action-button" id="copyTokenButton" type="button" data-default-label="Copy Token">
                Copy Token
            </button>
        </section>

        <div id="ios-save-tip" style="display:none;width:100%;margin-top:8px;text-align:center;color:#10b981;font-size:12px;">If the image did not save, long press the image and select Save to Photos</div>

        <div class="divider"></div>

        <section class="survey-section">
            <p class="survey-label">Feedback Survey</p>

            <div class="survey-card">
                <div class="survey-title">
                    <svg class="icon-lg" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M9 4.75h6M9 8.75h6M8 3h8a2 2 0 0 1 2 2v14l-3-2-3 2-3-2-3 2V5a2 2 0 0 1 2-2Z" stroke="#5BA4CF" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Share Your Experience</span>
                </div>

                <p class="survey-subtext">Takes approximately 5–10 minutes</p>

                @if ($evaluation)
                    <div class="survey-submitted">
                        <svg class="icon-lg" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="m5 12 4.5 4.5L19 7" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p class="survey-state-title success">Thank you! Your feedback has been submitted successfully.</p>
                        <p class="survey-state-copy">Your responses have been recorded. You can now download your certificate below.</p>
                    </div>
                @elseif ($surveyAvailable && $questions->isNotEmpty())
                    <button class="survey-button" id="openSurveyButton" type="button">Take Feedback Survey</button>
                @elseif ($surveyAvailable)
                    <div class="survey-pending">
                        <p class="survey-state-title">Survey is available soon</p>
                        <p class="survey-state-copy">Feedback questions are still being prepared for this event.</p>
                    </div>
                @else
                    <div class="survey-pending">
                        <p class="survey-state-title">Survey Not Yet Available</p>
                        <p class="survey-state-copy">The feedback survey will open after the event ends.</p>
                    </div>
                @endif
            </div>
        </section>

        <div class="survey-form-overlay" id="surveyFormOverlay" hidden aria-hidden="true">
            <div class="survey-form-shell" role="dialog" aria-modal="true" aria-labelledby="surveyFormTitle">
                <div class="survey-form-header">
                    <div>
                        <h2 id="surveyFormTitle">Event Feedback Survey</h2>
                        <p class="survey-subtext">
                            <span>{{ $participant->event->title }}</span> • <span>{{ $participant->event->location ?? 'Location' }}</span>
                        </p>
                        <p class="survey-subtext" style="margin-top: 4px; font-size: 0.9em; color: var(--muted);">
                            {{ optional($participant->event)->dateRangeLabel() ?? 'Date' }}
                        </p>
                    </div>
                    <button type="button" class="survey-close-button" id="closeSurveyForm" aria-label="Close survey form">×</button>
                </div>

                <form action="{{ $surveyAction }}" method="POST" id="mobileSurveyForm" novalidate>
                    @csrf
                    @php
                        $event = $participant->event;
                        $startDate = \Carbon\Carbon::parse($event->start_date)->setTimezone('Asia/Manila');
                        $endDate = \Carbon\Carbon::parse($event->end_date)->setTimezone('Asia/Manila');
                        if ($startDate->isSameDay($endDate)) {
                            $eventDateDisplay = $startDate->format('F j, Y');
                        } elseif ($startDate->isSameMonth($endDate)) {
                            $eventDateDisplay = $startDate->format('F j') . '-' . $endDate->format('j, Y');
                        } else {
                            $eventDateDisplay = $startDate->format('F j, Y') . ' - ' . $endDate->format('F j, Y');
                        }

                        $sessionFeedbackRatingLabels = [
                            1 => '1 (Poor)',
                            2 => '2 (Needs Improvement)',
                            3 => '3 (Satisfactory)',
                            4 => '4 (Good)',
                            5 => '5 (Excellent)',
                        ];
                    @endphp
                    <div class="survey-form-progress">
                        <div class="survey-form-step-label" id="surveyStepLabel">Step 1 of {{ $sections->count() }}</div>
                        <div class="survey-form-progress-bar" id="surveyFormProgressBar"></div>
                    </div>

                    @foreach ($sections as $section => $sectionQuestions)
                        <div class="survey-step{{ $loop->first ? ' active' : '' }}" data-step="{{ $loop->index }}">
                            <h3 class="survey-step-title">{{ $section }}</h3>

                            @foreach ($sectionQuestions as $question)
                                @if ($section === 'Session Feedback' && ! $question->is_matrix && in_array($question->renderingType(), ['likert', 'rating']))
                                    @continue
                                @endif
                                @if ($question->is_matrix && $section === 'Session Feedback' && is_array($question->matrix_items))
                                    @foreach ($question->matrix_items as $itemIndex => $item)
                                        <div class="survey-question" data-question-id="{{ $question->id }}_{{ $itemIndex }}" data-required="{{ $question->is_required ? 'true' : 'false' }}">
                                            <label class="survey-question-label" for="question_{{ $question->id }}_{{ $itemIndex }}">
                                                {{ $item }}
                                                @if ($question->is_required)
                                                    <span class="required-star">*</span>
                                                @endif
                                            </label>

                                            <div class="survey-form-likert survey-form-vertical">
                                                @foreach ([1, 2, 3, 4, 5] as $i)
                                                    <label class="survey-form-likert-option">
                                                        <input type="radio" id="question_{{ $question->id }}_{{ $itemIndex }}_{{ $i }}" name="answers[{{ $question->id }}][{{ $itemIndex }}]" value="{{ $i }}" {{ $question->is_required ? 'required' : '' }}>
                                                        <span>{{ $sessionFeedbackRatingLabels[$i] }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="survey-question" data-question-id="{{ $question->id }}" data-required="{{ $question->is_required ? 'true' : 'false' }}">
                                        <label class="survey-question-label" for="question_{{ $question->id }}">
                                            {{ $question->question }}
                                            @if ($question->is_required)
                                                <span class="required-star">*</span>
                                            @endif
                                        </label>

                                        @if ($question->help_text && $section !== 'Session Feedback')
                                            <p class="survey-question-help{{ in_array($question->renderingType(), ['likert', 'rating']) ? ' text-center' : '' }}">{{ $question->help_text }}</p>
                                        @endif

                                        @if (in_array($question->renderingType(), ['likert', 'rating']))
                                            @php
                                                $optionClass = $section === 'Session Feedback' ? ' survey-form-vertical' : '';
                                            @endphp
                                            <div class="survey-form-likert{{ $optionClass }}">
                                                @foreach ([1, 2, 3, 4, 5] as $i)
                                                    <label class="survey-form-likert-option">
                                                        <input type="radio" id="question_{{ $question->id }}_{{ $i }}" name="answers[{{ $question->id }}]" value="{{ $i }}" {{ $question->is_required ? 'required' : '' }}>
                                                        <span>{{ $section === 'Session Feedback' ? $sessionFeedbackRatingLabels[$i] : $i }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @elseif ($question->renderingType() === 'textarea')
                                            <textarea
                                                id="question_{{ $question->id }}"
                                                name="answers[{{ $question->id }}]"
                                                class="survey-form-textarea"
                                                placeholder="{{ $question->placeholder }}"
                                                {{ $question->is_required ? 'required' : '' }}
                                                @if ($question->isProgramQuestion()) aria-label="{{ $question->question }}" @endif
                                            ></textarea>
                                        @elseif ($question->renderingType() === 'radio' && is_array($question->matrix_items))
                                            <div class="survey-form-radio-group">
                                                @foreach ($question->matrix_items as $item)
                                                    <label class="survey-form-radio-option">
                                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $item }}" {{ $question->is_required ? 'required' : '' }}>
                                                        <span>{{ $item }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @else
                                            @php
                                                $isEventDetailsDateField = $section === 'Event Details' && $question->renderingType() === 'date';
                                                $isEventDetailsVenueField = $section === 'Event Details' && trim(strtolower($question->question)) === 'venue';
                                            @endphp
                                            <input
                                                id="question_{{ $question->id }}"
                                                name="answers[{{ $question->id }}]"
                                                type="{{ $isEventDetailsDateField || $isEventDetailsVenueField ? 'text' : (in_array($question->renderingType(), ['date', 'time']) ? $question->renderingType() : 'text') }}"
                                                class="survey-form-input"
                                                placeholder="{{ $question->placeholder }}"
                                                value="{{ $isEventDetailsDateField ? $eventDateDisplay : ($isEventDetailsVenueField ? $event->location : '') }}"
                                                {{ $isEventDetailsDateField || $isEventDetailsVenueField ? 'readonly' : '' }}
                                                {{ $question->is_required ? 'required' : '' }}
                                                @if ($question->isProgramQuestion()) aria-label="{{ $question->question }}" @endif
                                            >
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach

                    @if ($questions->isEmpty())
                        <div class="survey-pending" style="margin-bottom: 18px;">No survey questions are available at this time.</div>
                    @endif

                    <div class="survey-form-action-row">
                        <button type="button" class="survey-form-button secondary" id="surveyPrevButton" disabled>Previous</button>
                        <button type="button" class="survey-form-button primary" id="surveyNextButton">Next</button>
                        <button type="submit" class="survey-form-button primary" id="surveySubmitButton" hidden>Submit Feedback</button>
                    </div>
                    <p class="survey-form-error" id="surveyFormError" hidden></p>
                </form>

                <div class="survey-confirmation-overlay" id="surveyCloseConfirmation" hidden aria-hidden="true">
                    <div class="survey-confirmation-shell" role="dialog" aria-modal="true" aria-labelledby="surveyCloseConfirmationTitle">
                        <h3 class="survey-confirmation-title" id="surveyCloseConfirmationTitle">Close Survey?</h3>
                        <p class="survey-confirmation-copy">Are you sure you want to leave this survey? Your progress will not be saved.</p>
                        <div class="survey-confirmation-actions">
                            <button type="button" class="survey-button secondary" id="cancelCloseSurvey">Continue Survey</button>
                            <button type="button" class="survey-button primary" id="confirmCloseSurvey">Yes, Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="survey-section">
            <p class="survey-label">Certificate</p>

            <div class="survey-card">
                <div class="survey-title">
                    <svg class="icon-lg" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 5h16v14H4V5Zm4 4H6v6h2v-6Zm4 0H10v6h2v-6Zm4 0h-2v6h2v-6Z" stroke="#5BA4CF" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Download Your Certificate</span>
                </div>

                <p class="survey-subtext">{{ $participant->event->getCertificateType() }}</p>

                @if ($certificateAvailable)
                    <a class="survey-button" href="{{ route('participants.certificate.show', ['token' => $participant->digital_id_token, 'type' => $certificateType]) }}">
                        Download {{ $participant->event->getCertificateType() }}
                    </a>
                @else
                    <div class="survey-pending">
                        <p class="survey-state-title">Certificate Not Yet Available</p>
                        <p class="survey-state-copy">Certificates will be downloadable once feedback survey is submitted.</p>
                    </div>
                @endif
            </div>
        </section>

        <footer class="footer">
            <div class="wordmark">
                <span class="wordmark-event">Even</span><span class="wordmark-flow">ture</span>
            </div>
            <p class="tagline">Empowering Events. Connecting People.</p>
            <p class="copyright">© 2026 Eventure. All rights reserved.</p>
        </footer>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script>
        const card = document.querySelector('#flipCard .flip-card-inner');

        if (card) {
            const toggleCard = () => {
                card.classList.toggle('flipped');
            };

            document.getElementById('flipCard').addEventListener('click', toggleCard);
            document.getElementById('flipCard').addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    toggleCard();
                }
            });
        }

        const copyTokenButton = document.getElementById('copyTokenButton');
        const downloadPngButton = document.getElementById('downloadPngButton');

        const setButtonLabel = (button, label) => {
            if (!button) {
                return;
            }

            const labelEl = button.querySelector('span');

            if (labelEl) {
                labelEl.innerText = label;
                return;
            }

            button.innerText = label;
        };

        const setDownloadState = (isDownloading, label) => {
            [downloadPngButton].forEach((button) => {
                if (!button) {
                    return;
                }

                button.disabled = isDownloading;
            });

            if (!isDownloading) {
                setButtonLabel(downloadPngButton, downloadPngButton?.dataset.defaultLabel || 'Save ID');

                return;
            }

            setButtonLabel(downloadPngButton, label);
        };

        const waitForCardAssets = async () => {
            const cardImages = Array.from(document.querySelectorAll('#flipCard img'));

            await Promise.all(cardImages.map((img) => {
                if (img.complete) {
                    return Promise.resolve();
                }

                return new Promise((resolve) => {
                    img.addEventListener('load', resolve, { once: true });
                    img.addEventListener('error', resolve, { once: true });
                });
            }));

            if (document.fonts && typeof document.fonts.ready !== 'undefined') {
                await document.fonts.ready;
            }
        };

        const toSlug = (value) => {
            return String(value || '')
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '')
                .slice(0, 40);
        };

        const downloadDataUrl = (dataUrl, filename) => {
            const link = document.createElement('a');
            link.href = dataUrl;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            link.remove();
        };

        const renderFaceDataUrl = async (selector) => {
            const source = document.querySelector(selector);

            if (!source) {
                throw new Error('Digital ID face not found.');
            }

            const host = document.createElement('div');
            host.className = 'export-host';

            const clone = source.cloneNode(true);
            clone.classList.add('export-face');

            // Ensure clone is rendered off-screen and not affected by flip transforms
            clone.style.position = 'fixed';
            clone.style.left = '-10000px';
            clone.style.top = '-10000px';
            clone.style.visibility = 'visible';
            clone.style.opacity = '1';
            clone.style.transform = 'none';
            clone.style.webkitTransform = 'none';
            clone.style.backfaceVisibility = 'visible';
            clone.style.webkitBackfaceVisibility = 'visible';
            clone.style.pointerEvents = 'none';

            host.appendChild(clone);
            document.body.appendChild(host);

            try {
                const canvas = await html2canvas(clone, {
                    useCORS: true,
                    allowTaint: true,
                    scale: 2,
                    logging: false,
                    backgroundColor: null,
                });

                return canvas.toDataURL('image/png');
            } finally {
                host.remove();
            }
        };

        const downloadDigitalIdFaces = async () => {
            const page = document.querySelector('.page');
            const participantName = page?.dataset.participantName || '';
            const participantId = page?.dataset.participantId || '';
            const base = toSlug(participantName) || ('participant-' + participantId);
            const ext = 'png';

            await waitForCardAssets();

            const frontDataUrl = await renderFaceDataUrl('.flip-card-front');
            const backDataUrl = await renderFaceDataUrl('.flip-card-back');

            downloadDataUrl(frontDataUrl, 'digital-id-' + base + '-front.' + ext);

            window.setTimeout(() => {
                downloadDataUrl(backDataUrl, 'digital-id-' + base + '-back.' + ext);
            }, 120);
        };

        if (downloadPngButton) {
            downloadPngButton.addEventListener('click', async () => {
                try {
                    setDownloadState(true, 'Preparing your ID...');
                    await waitForCardAssets();
                    const frontDataUrl = await renderFaceDataUrl('.flip-card-front');
                    await new Promise(r => setTimeout(r, 800));
                    const backDataUrl = await renderFaceDataUrl('.flip-card-back');
                    
                    // build and show modal
                    var existing = document.getElementById('saveIdModal'); if (existing) existing.remove();
                    var overlay = document.createElement('div'); overlay.id='saveIdModal'; overlay.className='save-id-modal-overlay';
                    overlay.innerHTML = '\n                        <div class="save-id-modal-panel" role="dialog" aria-modal="true">\n                            <button class="save-id-modal-close" aria-label="Close">×</button>\n                            <h3 class="save-id-modal-title">Save Your Digital ID</h3>\n                            <div class="save-id-modal-banner"></div>\n                            <div class="save-id-images">\n                                <div class="save-id-image-block">\n                                    <div class="save-id-image-label">Front</div>\n                                    <img class="save-id-image" src="'+frontDataUrl+'" alt="Front" />\n                                    <div class="save-id-actions-front"></div>\n                                </div>\n                                <div class="save-id-image-block">\n                                    <div class="save-id-image-label">Back</div>\n                                    <img class="save-id-image" src="'+backDataUrl+'" alt="Back" />\n                                    <div class="save-id-actions-back"></div>\n                                </div>\n                            </div>\n                        </div>\n                    ';
                    document.body.appendChild(overlay);

                    if (!document.getElementById('save-id-modal-styles')){
                        var style=document.createElement('style'); style.id='save-id-modal-styles'; style.innerText='\n                            .save-id-modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.6);display:flex;align-items:flex-end;justify-content:center;z-index:1200}\n                            .save-id-modal-panel{width:100%;max-width:520px;background:#fff;border-radius:12px 12px 0 0;padding:16px 16px 28px;box-shadow:0 -8px 30px rgba(0,0,0,0.4);transform:translateY(100%);transition:transform .28s ease}\n                            .save-id-modal-overlay.show .save-id-modal-panel{transform:translateY(0)}\n                            .save-id-modal-close{position:absolute;right:12px;top:8px;background:none;border:none;font-size:22px;cursor:pointer;color:#000}\n                            .save-id-modal-title{margin:8px 0 6px;font-size:18px;color:#000}\n                            .save-id-modal-banner{margin:6px 0 12px;font-size:13px;color:#065f46}\n                            .save-id-images{display:flex;flex-direction:column;gap:14px}\n                            .save-id-image-block{display:flex;flex-direction:column;align-items:center}\n                            .save-id-image-label{font-weight:700;margin-bottom:6px;color:#000}\n                            .save-id-image{max-width:92%;height:auto;border-radius:8px;border:1px solid #e5e7eb}\n                            .save-id-actions-front,.save-id-actions-back{margin-top:8px}\n                            .save-id-download-btn{background:#0a2342;color:#fff;border:none;padding:8px 12px;border-radius:8px;cursor:pointer}\n                        ';
                        document.head.appendChild(style);
                    }

                    requestAnimationFrame(()=>{ overlay.classList.add('show'); });
                    overlay.querySelector('.save-id-modal-close').addEventListener('click', ()=>overlay.remove());

                    var banner = overlay.querySelector('.save-id-modal-banner');
                    var isIOSPlatform = /iP(hone|od|ad)/.test(navigator.userAgent) || (navigator.platform && /MacIntel/.test(navigator.platform) && navigator.maxTouchPoints > 1);
                    var isAndroidPlatform = /Android/i.test(navigator.userAgent);
                    if (isIOSPlatform) banner.textContent = 'Long press each image and tap Save to Photos to save your Digital ID';
                    if (isAndroidPlatform){
                        var frontActions = overlay.querySelector('.save-id-actions-front'); var backActions = overlay.querySelector('.save-id-actions-back');
                        var dfBtn=document.createElement('button'); dfBtn.className='save-id-download-btn'; dfBtn.textContent='Download Front'; dfBtn.addEventListener('click', ()=>downloadDataUrl(frontDataUrl, 'digital-id-front.png')); frontActions.appendChild(dfBtn);
                        var dbBtn=document.createElement('button'); dbBtn.className='save-id-download-btn'; dbBtn.textContent='Download Back'; dbBtn.addEventListener('click', ()=>downloadDataUrl(backDataUrl, 'digital-id-back.png')); backActions.appendChild(dbBtn);
                    }

                } catch (error) {
                    console.error(error);
                    setButtonLabel(downloadPngButton, 'Save failed');
                    window.setTimeout(() => {
                        setButtonLabel(downloadPngButton, downloadPngButton.dataset.defaultLabel || 'Save ID');
                    }, 1800);
                } finally {
                    setDownloadState(false);
                }
            });
        }

        if (copyTokenButton) {
            copyTokenButton.addEventListener('click', function () {
                const page = document.querySelector('.page');
                const token = page?.dataset.token || '';
                navigator.clipboard.writeText(token)
                    .then(() => {
                        copyTokenButton.innerText = '✓ Copied!';
                        window.setTimeout(() => {
                            copyTokenButton.innerText = copyTokenButton.dataset.defaultLabel;
                        }, 2000);
                    })
                    .catch(() => {
                        copyTokenButton.innerText = 'Copy failed';
                        window.setTimeout(() => {
                            copyTokenButton.innerText = copyTokenButton.dataset.defaultLabel;
                        }, 2000);
                    });
            });
        }

        const openSurveyButton = document.getElementById('openSurveyButton');
        const surveyFormOverlay = document.getElementById('surveyFormOverlay');
        const closeSurveyForm = document.getElementById('closeSurveyForm');
        const surveyCloseConfirmation = document.getElementById('surveyCloseConfirmation');
        const cancelCloseSurveyButton = document.getElementById('cancelCloseSurvey');
        const confirmCloseSurveyButton = document.getElementById('confirmCloseSurvey');
        const surveySteps = Array.from(document.querySelectorAll('.survey-step'));
        const surveyPrevButton = document.getElementById('surveyPrevButton');
        const surveyNextButton = document.getElementById('surveyNextButton');
        const surveySubmitButton = document.getElementById('surveySubmitButton');
        const surveyFormError = document.getElementById('surveyFormError');
        const surveyStepLabel = document.getElementById('surveyStepLabel');
        const surveyFormProgressBar = document.getElementById('surveyFormProgressBar');
        const mobileSurveyForm = document.getElementById('mobileSurveyForm');

        let activeSurveyStep = 0;

        const setSurveyStep = (index) => {
            const totalSteps = Math.max(1, surveySteps.length);
            activeSurveyStep = Math.max(0, Math.min(index, totalSteps - 1));

            surveySteps.forEach((step, stepIndex) => {
                const isActive = stepIndex === activeSurveyStep;
                step.classList.toggle('active', isActive);
                step.hidden = !isActive;
            });

            if (surveyPrevButton) {
                surveyPrevButton.disabled = activeSurveyStep === 0;
            }

            if (surveyNextButton) {
                surveyNextButton.hidden = activeSurveyStep === totalSteps - 1;
            }

            if (surveySubmitButton) {
                surveySubmitButton.hidden = activeSurveyStep !== totalSteps - 1;
            }

            if (surveyStepLabel) {
                surveyStepLabel.textContent = `Step ${activeSurveyStep + 1} of ${totalSteps}`;
            }

            if (surveyFormProgressBar) {
                surveyFormProgressBar.style.setProperty('--progress', `${((activeSurveyStep + 1) / totalSteps) * 100}%`);
            }

            if (surveyFormError) {
                surveyFormError.hidden = true;
                surveyFormError.textContent = '';
            }
        };

        const closeSurvey = () => {
            if (!surveyFormOverlay) return;

            closeConfirmation();
            surveyFormOverlay.classList.remove('is-visible');
            setTimeout(() => {
                surveyFormOverlay.style.display = 'none';
                surveyFormOverlay.setAttribute('aria-hidden', 'true');
            }, 200);
        };

        const openSurvey = () => {
            if (!surveyFormOverlay) return;

            surveyFormOverlay.style.display = 'flex';
            surveyFormOverlay.setAttribute('aria-hidden', 'false');
            setSurveyStep(0);
            
            // Trigger fade in with a small delay to ensure display change is applied
            setTimeout(() => {
                surveyFormOverlay.classList.add('is-visible');
            }, 10);
        };

        const openConfirmation = () => {
            if (!surveyCloseConfirmation) return;

            surveyCloseConfirmation.hidden = false;
            setTimeout(() => {
                surveyCloseConfirmation.classList.add('is-visible');
            }, 10);
        };

        const closeConfirmation = () => {
            if (!surveyCloseConfirmation) return;

            surveyCloseConfirmation.classList.remove('is-visible');
            setTimeout(() => {
                surveyCloseConfirmation.hidden = true;
            }, 200);
        };

        const hasInputValue = (input) => {
            if (input.type === 'radio') {
                const radios = document.querySelectorAll(`input[name="${input.name}"]`);
                return Array.from(radios).some((radio) => radio.checked);
            }

            return String(input.value || '').trim().length > 0;
        };

        const validateCurrentStep = () => {
            if (!surveySteps[activeSurveyStep]) {
                return true;
            }

            const requiredFields = Array.from(surveySteps[activeSurveyStep].querySelectorAll('[required]'));
            const missing = requiredFields.filter((field) => !hasInputValue(field));

            if (missing.length > 0) {
                if (surveyFormError) {
                    surveyFormError.hidden = false;
                    surveyFormError.textContent = 'Please complete all required items before continuing.';
                }
                return false;
            }

            if (surveyFormError) {
                surveyFormError.hidden = true;
                surveyFormError.textContent = '';
            }

            return true;
        };

        if (openSurveyButton) {
            openSurveyButton.addEventListener('click', openSurvey);
        }

        const closeSurveyButtons = Array.from(document.querySelectorAll('.survey-close-button'));

        closeSurveyButtons.forEach((button) => {
            const closeSurveyHandler = (event) => {
                event.preventDefault();
                event.stopPropagation();
                openConfirmation();
            };

            button.addEventListener('pointerdown', closeSurveyHandler);
            button.addEventListener('click', closeSurveyHandler);
        });

        if (cancelCloseSurveyButton) {
            cancelCloseSurveyButton.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();
                closeConfirmation();
            });
        }

        if (confirmCloseSurveyButton) {
            confirmCloseSurveyButton.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();
                closeSurvey();
            });
        }

        if (surveyFormOverlay) {
            surveyFormOverlay.addEventListener('click', (event) => {
                if (event.target === surveyFormOverlay) {
                    if (surveyCloseConfirmation && !surveyCloseConfirmation.hidden) {
                        closeConfirmation();
                        return;
                    }
                    closeSurvey();
                }
            });

            surveyFormOverlay.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    if (surveyCloseConfirmation && !surveyCloseConfirmation.hidden) {
                        closeConfirmation();
                        return;
                    }
                    closeSurvey();
                }
            });
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && surveyFormOverlay && !surveyFormOverlay.hidden) {
                if (surveyCloseConfirmation && !surveyCloseConfirmation.hidden) {
                    closeConfirmation();
                    return;
                }
                closeSurvey();
            }
        });

        if (surveyPrevButton) {
            surveyPrevButton.addEventListener('click', () => {
                setSurveyStep(activeSurveyStep - 1);
            });
        }

        if (surveyNextButton) {
            surveyNextButton.addEventListener('click', () => {
                if (!validateCurrentStep()) {
                    return;
                }
                setSurveyStep(activeSurveyStep + 1);
            });
        }

        if (mobileSurveyForm) {
            mobileSurveyForm.addEventListener('submit', (event) => {
                if (!validateCurrentStep()) {
                    event.preventDefault();
                }
            });
        }
    </script>
</body>
</html>