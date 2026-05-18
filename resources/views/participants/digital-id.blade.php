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
                saveBtn.textContent = 'Preparing your ID...';

                try {
                    await ensureHtml2Canvas();

                    var cardEl = document.querySelector('.digital-id-card');
                    if (!cardEl) throw new Error('Digital ID card not found');

                    // wait for SVG to render
                    if (document.fonts && typeof document.fonts.ready !== 'undefined') {
                        await document.fonts.ready;
                    }

                    // Build front face HTML: clone the card with just QR section
                    var frontCardHtml = (function(){
                        var clone = cardEl.cloneNode(true);
                        // keep only: header, survey status, participant/event/token info, and QR
                        // remove: back face stuff
                        var payload = clone.querySelector('.digital-id-payload');
                        if (payload) payload.remove();
                        var copyStatus = clone.querySelector('.digital-id-copy-status');
                        if (copyStatus) copyStatus.remove();
                        var iosTip = clone.querySelector('#ios-save-tip');
                        if (iosTip) iosTip.remove();
                        return clone;
                    })();

                    // Build back face HTML: just the payload section with white background
                    var backCardHtml = (function(){
                        var clone = cardEl.cloneNode(true);
                        // remove header, survey status, token row, QR section
                        var header = clone.querySelector('.digital-id-header');
                        if (header) header.remove();
                        var survey = clone.querySelector('.survey-status');
                        if (survey) survey.remove();
                        var surveyNot = clone.querySelector('.survey-not-answered');
                        if (surveyNot) surveyNot.remove();
                        var sections = clone.querySelectorAll('.digital-id-section');
                        sections.forEach(function(s){ s.remove(); });
                        var qr = clone.querySelector('.digital-id-qr');
                        if (qr) qr.remove();
                        var copyStatus = clone.querySelector('.digital-id-copy-status');
                        if (copyStatus) copyStatus.remove();
                        var iosTip = clone.querySelector('#ios-save-tip');
                        if (iosTip) iosTip.remove();
                        return clone;
                    })();

                    // build modal with HTML card elements (not images yet)
                    var existing = document.getElementById('saveIdModal'); if (existing) existing.remove();
                    var overlay = document.createElement('div'); overlay.id='saveIdModal'; overlay.className='save-id-modal-overlay';
                    var panel = document.createElement('div'); panel.className='save-id-modal-panel'; panel.setAttribute('role','dialog'); panel.setAttribute('aria-modal','true');
                    var closeBtn = document.createElement('button'); closeBtn.className='save-id-modal-close'; closeBtn.setAttribute('aria-label','Close'); closeBtn.textContent='×';
                    var title = document.createElement('h3'); title.className='save-id-modal-title'; title.textContent='Save Your Digital ID';
                    var banner = document.createElement('div'); banner.className='save-id-modal-banner';
                    var imagesContainer = document.createElement('div'); imagesContainer.className='save-id-images';

                    var frontBlock = document.createElement('div'); frontBlock.className='save-id-image-block';
                    var frontLabel = document.createElement('div'); frontLabel.className='save-id-image-label'; frontLabel.textContent='Front';
                    var frontCardContainer = document.createElement('div'); frontCardContainer.className='save-id-card-container'; frontCardContainer.style.maxWidth='92%'; frontCardContainer.style.borderRadius='8px'; frontCardContainer.style.overflow='hidden'; frontCardContainer.style.border='1px solid #e5e7eb'; frontCardContainer.appendChild(frontCardHtml);
                    var frontActions = document.createElement('div'); frontActions.className='save-id-actions-front'; frontActions.style.marginTop='8px';
                    frontBlock.appendChild(frontLabel); frontBlock.appendChild(frontCardContainer); frontBlock.appendChild(frontActions);

                    var backBlock = document.createElement('div'); backBlock.className='save-id-image-block';
                    var backLabel = document.createElement('div'); backLabel.className='save-id-image-label'; backLabel.textContent='Back';
                    var backCardContainer = document.createElement('div'); backCardContainer.className='save-id-card-container'; backCardContainer.style.maxWidth='92%'; backCardContainer.style.borderRadius='8px'; backCardContainer.style.overflow='hidden'; backCardContainer.style.border='1px solid #e5e7eb'; backCardContainer.appendChild(backCardHtml);
                    var backActions = document.createElement('div'); backActions.className='save-id-actions-back'; backActions.style.marginTop='8px';
                    backBlock.appendChild(backLabel); backBlock.appendChild(backCardContainer); backBlock.appendChild(backActions);

                    imagesContainer.appendChild(frontBlock); imagesContainer.appendChild(backBlock);
                    panel.appendChild(closeBtn); panel.appendChild(title); panel.appendChild(banner); panel.appendChild(imagesContainer);
                    overlay.appendChild(panel);
                    document.body.appendChild(overlay);

                    // inject modal styles if not present
                    if (!document.getElementById('save-id-modal-styles')){
                        var style=document.createElement('style'); style.id='save-id-modal-styles'; style.innerText='\n                            .save-id-modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.6);display:flex;align-items:flex-end;justify-content:center;z-index:1200}\n                            .save-id-modal-panel{width:100%;max-width:520px;background:#fff;border-radius:12px 12px 0 0;padding:16px 16px 28px;box-shadow:0 -8px 30px rgba(0,0,0,0.4);transform:translateY(100%);transition:transform .28s ease}\n                            .save-id-modal-overlay.show .save-id-modal-panel{transform:translateY(0)}\n                            .save-id-modal-close{position:absolute;right:12px;top:8px;background:none;border:none;font-size:22px;cursor:pointer;color:#000}\n                            .save-id-modal-title{margin:8px 0 6px;font-size:18px;color:#000}\n                            .save-id-modal-banner{margin:6px 0 12px;font-size:13px;color:#065f46}\n                            .save-id-images{display:flex;flex-direction:column;gap:14px}\n                            .save-id-image-block{display:flex;flex-direction:column;align-items:center}\n                            .save-id-image-label{font-weight:700;margin-bottom:6px;color:#000}\n                            .save-id-card-container{background:#fff}\n                            .save-id-card-container .digital-id-card{box-shadow:none;margin:0;max-width:100%;}\n                            .save-id-actions-front,.save-id-actions-back{margin-top:8px;width:100%;display:flex;justify-content:center}\n                            .save-id-download-btn{background:#0a2342;color:#fff;border:none;padding:8px 12px;border-radius:8px;cursor:pointer}\n                        ';
                        document.head.appendChild(style);
                    }

                    // show modal
                    requestAnimationFrame(function(){ overlay.classList.add('show'); });
                    closeBtn.addEventListener('click', function(){ overlay.remove(); });

                    // helper: convert card HTML element to image and replace it
                    var cardToImageInPlace = async function(cardElement, filename){
                        try {
                            var host = document.createElement('div'); host.style.position='fixed'; host.style.left='-9999px'; host.style.top='-9999px'; host.style.visibility='visible'; host.style.opacity='1';
                            var clone = cardElement.cloneNode(true);
                            clone.style.width='500px'; clone.style.margin='0'; clone.style.boxShadow='none';
                            host.appendChild(clone); document.body.appendChild(host);
                            try {
                                var canvas = await html2canvas(clone, {useCORS:true,allowTaint:true,scale:2,logging:false,backgroundColor:'#fff'});
                                host.remove();
                                return canvas.toDataURL('image/png');
                            } finally { if (host.parentNode) host.remove(); }
                        } catch (e) { console.error('Card to image conversion failed:', e); throw e; }
                    };

                    var isIOSPlatform = /iP(hone|od|ad)/.test(navigator.userAgent) || (navigator.platform && /MacIntel/.test(navigator.platform) && navigator.maxTouchPoints > 1);
                    var isAndroidPlatform = /Android/i.test(navigator.userAgent);

                    if (isIOSPlatform){
                        banner.textContent = 'Long press each card and tap Save to Photos to save your Digital ID';
                        // add long-press handlers to convert cards to images on demand
                        [frontCardContainer, backCardContainer].forEach(function(container, idx){
                            var cardElem = container.querySelector('.digital-id-card');
                            var longPressTimer = null;
                            var isLongPress = false;
                            container.addEventListener('touchstart', function(){
                                isLongPress = false;
                                longPressTimer = setTimeout(function(){
                                    isLongPress = true;
                                    cardToImageInPlace(cardElem, (idx === 0 ? 'front' : 'back')+'.png').then(function(dataUrl){
                                        // replace HTML card with image
                                        var img = document.createElement('img');
                                        img.src = dataUrl;
                                        img.style.maxWidth = '100%';
                                        img.style.height = 'auto';
                                        img.style.borderRadius = '8px';
                                        img.style.border = '1px solid #e5e7eb';
                                        container.innerHTML = '';
                                        container.appendChild(img);
                                    }).catch(function(e){ console.error(e); });
                                }, 500);
                            });
                            container.addEventListener('touchend', function(){
                                if (longPressTimer) clearTimeout(longPressTimer);
                            });
                            container.addEventListener('touchmove', function(){
                                if (longPressTimer) clearTimeout(longPressTimer);
                                isLongPress = false;
                            });
                        });
                    } else if (isAndroidPlatform) {
                        banner.textContent = 'Download your Digital ID using the buttons below';
                        var dfBtn = document.createElement('button'); dfBtn.className='save-id-download-btn'; dfBtn.textContent='Download Front'; 
                        dfBtn.addEventListener('click', function(){
                            cardToImageInPlace(frontCardHtml, 'digital-id-front-'+participantId+'.png').then(function(dataUrl){
                                triggerDownload(dataUrl, 'digital-id-front-'+participantId+'.png');
                            }).catch(function(e){ console.error(e); alert('Failed to capture front card'); });
                        });
                        frontActions.appendChild(dfBtn);

                        var dbBtn = document.createElement('button'); dbBtn.className='save-id-download-btn'; dbBtn.textContent='Download Back';
                        dbBtn.addEventListener('click', function(){
                            cardToImageInPlace(backCardHtml, 'digital-id-back-'+participantId+'.png').then(function(dataUrl){
                                triggerDownload(dataUrl, 'digital-id-back-'+participantId+'.png');
                            }).catch(function(e){ console.error(e); alert('Failed to capture back card'); });
                        });
                        backActions.appendChild(dbBtn);
                    }

                } catch (err) {
                    console.error(err);
                    alert('Failed to prepare your Digital ID. Please try again.');
                } finally {
                    saveBtn.disabled = false;
                    saveBtn.textContent = originalText || 'Save ID';
                }
            });
        })();
    </script>
@endsection
