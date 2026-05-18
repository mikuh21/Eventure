@extends('layouts.app')

@section('title', 'Digital ID')

@push('styles')
    <style>
        .digital-id-card {
            max-width: 500px;
            margin: 0 auto;
            padding: 32px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .digital-id-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .digital-id-title {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .digital-id-actions {
            display: flex;
            gap: 12px;
        }

        .digital-id-btn {
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .digital-id-btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .digital-id-btn-secondary:hover {
            background: #d1d5db;
        }

        .digital-id-btn-primary {
            background: #3b82f6;
            color: white;
        }

        .digital-id-btn-primary:hover:not(:disabled) {
            background: #2563eb;
        }

        .digital-id-btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .digital-id-section {
            margin-bottom: 28px;
        }

        .digital-id-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .digital-id-value {
            font-size: 15px;
            color: #1f2937;
            word-break: break-all;
        }

        .digital-id-token-row {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .digital-id-token-input {
            flex: 1;
            padding: 10px 12px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 13px;
            font-family: 'Courier New', monospace;
            color: #1f2937;
            word-break: break-all;
        }

        .digital-id-copy-btn {
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
            background: #e5e7eb;
            color: #374151;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .digital-id-copy-btn:hover {
            background: #d1d5db;
        }

        .digital-id-copy-status {
            font-size: 12px;
            color: #10b981;
            font-weight: 600;
            margin-top: 6px;
        }

        /* Export helpers for html2canvas capture */
        .export-host {
            position: fixed;
            left: -10000px;
            top: 0;
            width: 500px;
            height: 500px;
            pointer-events: none;
            z-index: -1;
        }

        .export-face {
            position: relative !important;
            transform: none !important;
            -webkit-backface-visibility: visible !important;
            backface-visibility: visible !important;
            width: 500px !important;
            height: auto !important;
            margin: 0 !important;
            background: white !important;
            color: #000 !important;
        }

        #ios-save-tip {
            display: none;
            margin-top: 8px;
            font-size: 13px;
            color: #065f46;
            text-align: center;
        }

        .digital-id-qr {
            margin: 28px 0;
            text-align: center;
            padding: 20px;
            background: #fafafa;
            border-radius: 8px;
        }

        .digital-id-qr svg {
            max-width: 100%;
            height: auto;
        }

        .digital-id-payload {
            margin: 28px 0;
        }

        .digital-id-payload-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #6b7280;
            margin-bottom: 12px;
        }

        .digital-id-payload-pre {
            padding: 12px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 12px;
            font-family: 'Courier New', monospace;
            color: #374151;
            overflow-x: auto;
        }

        .survey-status {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 8px;
            margin-bottom: 24px;
        }

        .survey-status-icon {
            width: 24px;
            height: 24px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
            flex-shrink: 0;
        }

        .survey-status-text {
            font-size: 14px;
            color: #065f46;
            font-weight: 500;
        }

        .survey-not-answered {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: #fef3c7;
            border: 1px solid #fde68a;
            border-radius: 8px;
            margin-bottom: 24px;
        }

        .survey-not-answered-icon {
            width: 24px;
            height: 24px;
            background: #f59e0b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
            flex-shrink: 0;
        }

        .survey-not-answered-text {
            font-size: 14px;
            color: #92400e;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .digital-id-card {
                padding: 20px;
            }

            .digital-id-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .digital-id-actions {
                width: 100%;
                flex-direction: column;
            }

            .digital-id-btn {
                width: 100%;
                text-align: center;
            }

            .digital-id-token-row {
                flex-direction: column;
            }

            .digital-id-copy-btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="digital-id-card">
        <div class="digital-id-header">
            <h1 class="digital-id-title">Digital ID</h1>
            <div class="digital-id-actions">
                <a class="digital-id-btn digital-id-btn-secondary" href="{{ route('events.participants.show', [$participant->event, $participant]) }}">Back</a>
                @php($hasSubmittedSurvey = $participant->evaluations()->exists())
                <button id="save-id-btn" class="digital-id-btn digital-id-btn-primary" type="button" {{ !$hasSubmittedSurvey ? 'disabled' : '' }} title="{{ !$hasSubmittedSurvey ? 'Complete the survey first to save' : 'Save ID' }}">Save ID</button>
            </div>
        </div>

        @if($hasSubmittedSurvey)
            <div class="survey-status">
                <div class="survey-status-icon">✓</div>
                <div class="survey-status-text">Survey submitted successfully</div>
            </div>
        @else
            <div class="survey-not-answered">
                <div class="survey-not-answered-icon">!</div>
                <div class="survey-not-answered-text">Complete the survey to enable certificate download</div>
            </div>
        @endif

        <div class="digital-id-section">
            <span class="digital-id-label">Participant</span>
            <div class="digital-id-value">{{ $participant->name }}</div>
        </div>

        <div class="digital-id-section">
            <span class="digital-id-label">Event</span>
            <div class="digital-id-value">{{ $participant->event->title }}</div>
        </div>

        <div class="digital-id-section">
            <span class="digital-id-label">Token</span>
            <div class="digital-id-token-row">
                <div class="digital-id-token-input" id="digital-id-token">{{ $participant->digital_id_token }}</div>
                <button type="button" class="digital-id-copy-btn" id="copy-token-btn">Copy</button>
            </div>
            <div class="digital-id-copy-status" id="copy-token-status"></div>
            <div id="ios-save-tip" style="display:none;margin-top:8px;text-align:center;color:#10b981;font-size:12px;">If the image did not save, long press the image and select Save to Photos</div>
        </div>

        <div class="digital-id-qr">
            {!! $qrSvg !!}
        </div>

        <div class="digital-id-payload">
            <span class="digital-id-payload-label">QR Payload</span>
            <pre class="digital-id-payload-pre">{{ $payload }}</pre>
        </div>
    </div>

    <script>
        (function () {
            var button = document.getElementById('copy-token-btn');
            var tokenEl = document.getElementById('digital-id-token');
            var statusEl = document.getElementById('copy-token-status');

            if (!button || !tokenEl || !statusEl) {
                return;
            }

            button.addEventListener('click', async function () {
                try {
                    await navigator.clipboard.writeText(tokenEl.textContent.trim());
                    statusEl.textContent = 'Copied!';
                    setTimeout(function () {
                        statusEl.textContent = '';
                    }, 2000);
                } catch (e) {
                    statusEl.textContent = 'Copy failed';
                }
            });
        })();
    </script>

    <script>
        (function () {
            var saveBtn = document.getElementById('save-id-btn');
            var iosTip = document.getElementById('ios-save-tip');
            var hasSubmitted = @json($hasSubmittedSurvey);
            var participantId = @json($participant->id);

            if (!saveBtn) return;

            function isIOS() {
                return /iP(hone|od|ad)/.test(navigator.userAgent) || (navigator.platform && /MacIntel/.test(navigator.platform) && navigator.maxTouchPoints > 1);
            }

            function isAndroid() {
                return /Android/i.test(navigator.userAgent);
            }

            async function ensureHtml2Canvas() {
                if (typeof html2canvas !== 'undefined') return;
                return new Promise(function (resolve, reject) {
                    var s = document.createElement('script');
                    s.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
                    s.onload = resolve;
                    s.onerror = reject;
                    document.head.appendChild(s);
                });
            }

            function triggerDownload(dataUrl, filename) {
                var a = document.createElement('a');
                a.href = dataUrl;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                a.remove();
            }

            saveBtn.addEventListener('click', async function () {
                if (!hasSubmitted) return;
                saveBtn.disabled = true;
                var originalText = saveBtn.textContent;
                saveBtn.textContent = 'Saving to photos...';

                try {
                    await ensureHtml2Canvas();

                    var frontEl = document.querySelector('.digital-id-card');
                    if (!frontEl) throw new Error('Digital ID card not found');

                    // Create a back face clone and adjust visuals for export
                    var backEl = frontEl.cloneNode(true);
                    var qrel = backEl.querySelector('.digital-id-qr');
                    if (qrel) qrel.style.display = 'none';
                    var payloadPre = backEl.querySelector('.digital-id-payload-pre');
                    if (payloadPre) {
                        payloadPre.style.background = '#ffffff';
                        payloadPre.style.color = '#000000';
                        payloadPre.style.padding = '16px';
                        payloadPre.style.fontSize = '12px';
                    }

                    // Helper: capture an element by cloning it into an off-screen host
                    async function captureElementAsDataUrl(element) {
                        var host = document.createElement('div');
                        host.className = 'export-host';

                        var clone = element.cloneNode(true);
                        clone.classList.add('export-face');

                        // Force renderable off-screen layout to avoid flip/transform issues
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
                            var canvas = await html2canvas(clone, {
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
                    }

                    // Wait for images and fonts to be ready so html2canvas renders correctly
                    const frontImages = Array.from(frontEl.querySelectorAll('img'));
                    await Promise.all(frontImages.map((img) => {
                        if (img.complete) return Promise.resolve();
                        return new Promise((res) => {
                            img.addEventListener('load', res, { once: true });
                            img.addEventListener('error', res, { once: true });
                        });
                    }));

                    if (document.fonts && typeof document.fonts.ready !== 'undefined') {
                        await document.fonts.ready;
                    }

                    // Capture front then back sequentially
                    var dataFront = await captureElementAsDataUrl(frontEl);
                    await new Promise(function (r) { setTimeout(r, 800); });

                    // Prepare a back-specific clone for capture (hide QR if present, style payload)
                    var backCaptureSource = frontEl.cloneNode(true);
                    var qrel2 = backCaptureSource.querySelector('.digital-id-qr');
                    if (qrel2) qrel2.style.display = 'none';
                    var payloadPre2 = backCaptureSource.querySelector('.digital-id-payload-pre');
                    if (payloadPre2) {
                        payloadPre2.style.background = '#ffffff';
                        payloadPre2.style.color = '#000000';
                        payloadPre2.style.padding = '16px';
                        payloadPre2.style.fontSize = '12px';
                    }

                    var dataBack = await (async function(){
                        var host = document.createElement('div');
                        host.className = 'export-host';
                        backCaptureSource.classList.add('export-face');
                        backCaptureSource.style.position = 'fixed';
                        backCaptureSource.style.left = '-10000px';
                        backCaptureSource.style.top = '-10000px';
                        backCaptureSource.style.visibility = 'visible';
                        backCaptureSource.style.opacity = '1';
                        backCaptureSource.style.transform = 'none';
                        backCaptureSource.style.webkitTransform = 'none';
                        backCaptureSource.style.backfaceVisibility = 'visible';
                        backCaptureSource.style.webkitBackfaceVisibility = 'visible';
                        backCaptureSource.style.pointerEvents = 'none';
                        host.appendChild(backCaptureSource);
                        document.body.appendChild(host);
                        try {
                            var canvas = await html2canvas(backCaptureSource, {
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
                    })();

                    // Trigger downloads for both front and back using anchor-download approach
                    triggerDownload(dataFront, 'digital-id-front-' + participantId + '.png');
                    setTimeout(function () {
                        triggerDownload(dataBack, 'digital-id-back-' + participantId + '.png');
                    }, 800);

                    // For iOS, show a helpful inline tip if the image did not save automatically
                    if (isIOS()) {
                        if (iosTip) {
                            iosTip.textContent = 'If the image did not save, long press the image and select Save to Photos';
                            iosTip.style.display = 'block';
                        }
                    }

                    // cleanup (individual hosts removed by capture helper)
                } catch (err) {
                    console.error(err);
                    alert('Saving the ID failed. Please try again.');
                } finally {
                    saveBtn.disabled = false;
                    saveBtn.textContent = originalText;
                }
            });
        })();
    </script>
@endsection
