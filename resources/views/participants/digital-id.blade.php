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
                @php($hasSubmittedSurvey = $participant->hasSubmittedSurvey())
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

                    var frontEl = document.querySelector('.digital-id-card');
                    if (!frontEl) throw new Error('Digital ID card not found');

                    // wait for assets
                    const imgs = Array.from(frontEl.querySelectorAll('img'));
                    await Promise.all(imgs.map((img) => img.complete ? Promise.resolve() : new Promise((r) => { img.addEventListener('load', r, { once: true }); img.addEventListener('error', r, { once: true }); })));
                    if (document.fonts && typeof document.fonts.ready !== 'undefined') {
                        await document.fonts.ready;
                    }

                    // capture front
                    var dataFront = await (async function(element){
                        var host = document.createElement('div'); host.className='export-host';
                        var clone = element.cloneNode(true); clone.classList.add('export-face');
                        clone.style.position='fixed'; clone.style.left='-9999px'; clone.style.top='-9999px'; clone.style.visibility='visible'; clone.style.opacity='1'; clone.style.transform='none'; clone.style.webkitTransform='none'; clone.style.backfaceVisibility='visible'; clone.style.webkitBackfaceVisibility='visible'; clone.style.pointerEvents='none';
                        host.appendChild(clone); document.body.appendChild(host);
                        try {
                            const c = await html2canvas(clone, {useCORS:true,allowTaint:true,scale:1,logging:false,backgroundColor:null,width:clone.scrollWidth,height:clone.scrollHeight,windowWidth:clone.scrollWidth,windowHeight:clone.scrollHeight});
                            return c.toDataURL('image/png');
                        } finally { host.remove(); }
                    })(frontEl);

                    // small pause then capture back
                    await new Promise(r => setTimeout(r, 800));

                    var backSource = frontEl.cloneNode(true);
                    
                    var ppre = backSource.querySelector('.digital-id-payload-pre'); if (ppre){ ppre.style.background='#fff'; ppre.style.color='#000'; ppre.style.padding='16px'; ppre.style.fontSize='12px'; }

                    var dataBack = await (async function(element){
                        var host = document.createElement('div'); host.className='export-host';
                        var clone = element.cloneNode(true); clone.classList.add('export-face');
                        clone.style.position='fixed'; clone.style.left='-9999px'; clone.style.top='-9999px'; clone.style.visibility='visible'; clone.style.opacity='1'; clone.style.transform='none'; clone.style.webkitTransform='none'; clone.style.backfaceVisibility='visible'; clone.style.webkitBackfaceVisibility='visible'; clone.style.pointerEvents='none';
                        host.appendChild(clone); document.body.appendChild(host);
                        try { const c = await html2canvas(clone, {useCORS:true,allowTaint:true,scale:1,logging:false,backgroundColor:null,width:clone.scrollWidth,height:clone.scrollHeight,windowWidth:clone.scrollWidth,windowHeight:clone.scrollHeight}); return c.toDataURL('image/png'); } finally { host.remove(); }
                    })(backSource);

                    // build modal with the exact same digital ID card appearance
                    var modal = (function buildSaveModal(frontDataUrl, backDataUrl, frontClone, backClone){
                        var existing = document.getElementById('saveIdModal'); if (existing) existing.remove();
                        var overlay = document.createElement('div'); overlay.id='saveIdModal'; overlay.className='save-id-modal-overlay';
                        overlay.innerHTML = '\n                            <div class="save-id-modal-panel" role="dialog" aria-modal="true">\n                                <button class="save-id-modal-close" aria-label="Close">×</button>\n                                <h3 class="save-id-modal-title">Save Your Digital ID</h3>\n                                <div class="save-id-modal-banner"></div>\n                                <div class="save-id-cards">\n                                    <div class="save-id-card-face">\n                                        <div class="save-id-image-label">Front</div>\n                                        <div class="save-id-card-preview save-id-front-card"></div>\n                                        <div class="save-id-actions-front"></div>\n                                    </div>\n                                    <div class="save-id-card-face">\n                                        <div class="save-id-image-label">Back</div>\n                                        <div class="save-id-card-preview save-id-back-card"></div>\n                                        <div class="save-id-actions-back"></div>\n                                    </div>\n                                </div>\n                            </div>\n                        ';
                        document.body.appendChild(overlay);

                        function sanitizeClone(node) {
                            if (!node || node.nodeType !== Node.ELEMENT_NODE) return;
                            if (node.id) node.removeAttribute('id');
                            node.querySelectorAll('[id]').forEach(function(el){ el.removeAttribute('id'); });
                            node.querySelectorAll('button, a, .digital-id-actions, .digital-id-copy-status, #ios-save-tip').forEach(function(el){ el.remove(); });
                            node.style.width = '100%';
                            node.style.maxWidth = '100%';
                            node.style.boxSizing = 'border-box';
                        }

                        if (frontClone) {
                            sanitizeClone(frontClone);
                            var frontTarget = overlay.querySelector('.save-id-front-card');
                            if (frontTarget) frontTarget.appendChild(frontClone);
                        }

                        if (backClone) {
                            sanitizeClone(backClone);
                            
                            var ppre = backClone.querySelector('.digital-id-payload-pre');
                            if (ppre) { ppre.style.background = '#fff'; ppre.style.color = '#000'; ppre.style.padding = '16px'; ppre.style.fontSize = '12px'; }
                            var backTarget = overlay.querySelector('.save-id-back-card');
                            if (backTarget) backTarget.appendChild(backClone);
                        }

                        if (!document.getElementById('save-id-modal-styles')){
                            var style=document.createElement('style'); style.id='save-id-modal-styles'; style.innerText='\n                                .save-id-modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.6);display:flex;align-items:flex-end;justify-content:center;z-index:1200;padding:18px;box-sizing:border-box}\n                                .save-id-modal-panel{width:min(100%,96vw);max-width:760px;background:#fff;border-radius:18px;padding:20px;box-shadow:0 24px 80px rgba(0,0,0,0.25);transform:translateY(100%);transition:transform .28s ease;max-height:94vh;overflow-y:auto;box-sizing:border-box}\n                                .save-id-modal-overlay.show .save-id-modal-panel{transform:translateY(0)}\n                                .save-id-modal-close{position:absolute;right:14px;top:14px;background:none;border:none;font-size:24px;cursor:pointer;color:#111}\n                                .save-id-modal-title{margin:0 0 10px;font-size:20px;color:#111;font-weight:700}\n                                .save-id-modal-banner{margin:8px 0 16px;font-size:13px;color:#065f46;line-height:1.4}\n                                .save-id-cards{display:flex;flex-direction:column;gap:22px;width:100%}\n                                .save-id-card-face{display:flex;flex-direction:column;gap:12px;width:100%}\n                                .save-id-card-preview{width:100%;box-sizing:border-box;display:flex;justify-content:center}\n                                .save-id-card-preview .digital-id-card{width:100%;max-width:100%;margin:0;box-shadow:none;border:none;border-radius:12px}\n                                .save-id-image-label{font-weight:700;color:#111;margin-bottom:8px}\n                                .save-id-actions-front,.save-id-actions-back{display:flex;flex-wrap:wrap;justify-content:center;gap:12px;margin-top:12px;width:100%}\n                                .save-id-download-btn{background:#0a2342;color:#fff;border:none;padding:10px 14px;border-radius:8px;cursor:pointer;min-width:140px}\n                                .save-id-modal-panel .digital-id-header{border-bottom:none;margin-bottom:22px;padding-bottom:0}\n                                .save-id-modal-panel .digital-id-qr{margin:24px auto;width:100%;max-width:220px;padding:8px;display:flex;align-items:center;justify-content:center;box-sizing:border-box;overflow:visible}.save-id-modal-panel .digital-id-qr svg{width:100% !important;height:auto !important;max-width:100% !important;display:block !important;overflow:visible !important}\n                                .save-id-modal-panel .digital-id-payload{margin:24px 0}\n                            ';
                            document.head.appendChild(style);
                        }

                        requestAnimationFrame(()=>{ overlay.classList.add('show'); });
                        overlay.querySelector('.save-id-modal-close').addEventListener('click', ()=>overlay.remove());
                        return overlay;
                    })(dataFront,dataBack, frontEl.cloneNode(true), backSource);

                    var banner = modal.querySelector('.save-id-modal-banner');
                    var isIOSPlatform = /iP(hone|od|ad)/.test(navigator.userAgent) || (navigator.platform && /MacIntel/.test(navigator.platform) && navigator.maxTouchPoints > 1);
                    var isAndroidPlatform = /Android/i.test(navigator.userAgent);
                    if (isIOSPlatform) banner.textContent = 'Long press each image and tap Save to Photos to save your Digital ID';
                    if (isAndroidPlatform){
                        var frontActions = modal.querySelector('.save-id-actions-front'); var backActions = modal.querySelector('.save-id-actions-back');
                        var dfBtn=document.createElement('button'); dfBtn.className='save-id-download-btn'; dfBtn.textContent='Download Front'; dfBtn.addEventListener('click', ()=>triggerDownload(dataFront,'digital-id-front-'+participantId+'.png')); frontActions.appendChild(dfBtn);
                        var dbBtn=document.createElement('button'); dbBtn.className='save-id-download-btn'; dbBtn.textContent='Download Back'; dbBtn.addEventListener('click', ()=>triggerDownload(dataBack,'digital-id-back-'+participantId+'.png')); backActions.appendChild(dbBtn);
                    }

                } catch (err) {
                    console.error(err);
                    if (iosTip) iosTip.style.display = 'block';
                } finally {
                    saveBtn.disabled = false;
                    saveBtn.textContent = originalText || 'Save ID';
                }
            });
        })();
    </script>
@endsection
