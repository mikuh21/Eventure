@extends('layouts.app')

@section('title', 'Event Details - Eventure')

@push('styles')
    <style>
        .event-view-page {
            display: grid;
            gap: 16px;
        }

        .event-view-card {
            border: 1px solid #bfdfff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(10, 35, 66, 0.06);
        }

        .event-view-header h1,
        .event-view-header h2 {
            margin: 0;
            color: #0A2342;
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            line-height: 1.2;
        }

        .event-view-header h1 {
            font-size: 21px;
        }

        .event-view-header h2 {
            font-size: 20px;
        }

        .event-view-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
        }

        .event-view-meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px 14px;
            margin-top: 8px;
        }

        .event-view-item {
            margin: 0;
            padding: 10px;
            border: 1px solid #dceaf8;
            border-radius: 10px;
            background: #f9fcff;
        }

        .event-view-label {
            display: inline-flex;
            align-items: center;
            border-radius: 8px;
            border: 1px solid #bfdfff;
            background: #e8f4fd;
            color: #1B6CA8;
            font-family: 'Sora', sans-serif;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.2;
            padding: 4px 8px;
        }

        .event-view-value {
            display: block;
            margin-top: 7px;
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            color: #0A2342;
            line-height: 1.4;
            word-break: break-word;
        }

        .event-view-item.span-2 {
            grid-column: 1 / -1;
        }

        .event-status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            padding: 2px 10px;
            line-height: 1.3;
        }

        .event-status-open {
            background: #d1fae5;
            color: #065f46;
        }

        .event-status-closed {
            background: #fee2e2;
            color: #991b1b;
        }

        .event-view-utility {
            margin-top: 12px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .modal-floating-label {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1.4;
            white-space: nowrap;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.12);
            box-shadow: 0 14px 34px rgba(55, 65, 81, 0.08);
            color: #0A2342;
            border-radius: 999px;
            padding: 10px 16px;
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            font-weight: 600;
            transition: opacity 220ms ease, transform 220ms ease;
            z-index: 30;
        }

        .modal-toast {
            position: fixed;
            left: 50%;
            bottom: 24px;
            transform: translateX(-50%);
            max-width: min(92vw, 420px);
            width: auto;
            min-height: 44px;
            margin: 0 auto;
            pointer-events: none;
            opacity: 1;
        }

        .modal-floating-success {
            color: #065f46;
            background: #ecfdf5;
            border-color: #a7f3d0;
        }

        .modal-floating-error {
            color: #b91c1c;
            background: #fce7e7;
            border-color: #fca5a5;
        }

        .modal-floating-warning {
            color: #92400e;
            background: #fef3c7;
            border-color: #fde68a;
        }

        .modal-floating-info {
            color: #0c4a6e;
            background: #e0f2fe;
            border-color: #7dd3fc;
        }

        .modal-toast.is-hiding {
            opacity: 0;
            transform: translateX(-50%) translateY(-10px);
        }

        .event-assets-grid {
            margin-top: 12px;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .event-asset-card {
            border: 1px solid #bfdfff;
            border-radius: 10px;
            background: #f7fbff;
            padding: 10px;
        }

        .event-asset-title {
            margin: 0 0 8px;
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: #1B6CA8;
        }

        .poster-preview {
            display: block;
            width: 100%;
            height: 256px;
            border: 1px solid #d7e8f8;
            border-radius: 8px;
            overflow: hidden;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .poster-preview img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .asset-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 200px;
            border: 1px dashed #9fc3e3;
            border-radius: 8px;
            background: #ffffff;
            color: #5a84a6;
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            text-align: center;
            padding: 12px;
        }

        .template-file-name {
            margin: 0;
            color: #0A2342;
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            line-height: 1.4;
            word-break: break-word;
        }

        .event-asset-actions {
            margin-top: 10px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .participants-note {
            margin: 0;
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            color: #1B6CA8;
        }

        .participants-summary {
            margin-top: 10px;
            border: 1px solid #dceaf8;
            border-radius: 10px;
            background: #f9fcff;
            padding: 12px;
            display: grid;
            gap: 6px;
        }

        .participants-summary-label {
            font-family: 'Sora', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #1B6CA8;
            margin: 0;
        }

        .participants-summary-value {
            font-family: 'Sora', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: #0A2342;
            margin: 0;
        }

        .scroll-top-btn {
            position: fixed;
            right: 24px;
            bottom: 24px;
            width: 48px;
            height: 48px;
            cursor: pointer;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(27,108,168,0.95);
            color: #ffffff;
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 12px 24px rgba(0,0,0,0.22);
            transform: translateY(10px) scale(0.96);
            opacity: 0;
            pointer-events: none;
            transition: opacity 250ms ease, transform 250ms ease, filter 250ms ease;
            z-index: 60;
        }

        .scroll-top-btn.is-visible {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        .scroll-top-btn:hover {
            filter: brightness(1.08);
            transform: translateY(-2px) scale(1.02);
        }

        .scroll-top-btn:focus-visible {
            outline: 2px solid rgba(255,255,255,0.9);
            outline-offset: 3px;
        }

        @media (max-width: 980px) {
            .event-view-meta {
                grid-template-columns: 1fr;
            }

            .event-assets-grid {
                grid-template-columns: 1fr;
            }

            .event-view-item.span-2 {
                grid-column: auto;
            }
        }

        /* Poster Modal Styles */
        .poster-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(10, 35, 66, 0.55);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 180ms ease, visibility 0s linear 180ms;
        }

        .poster-modal-overlay.active,
        .poster-modal-overlay.is-visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition: opacity 180ms ease;
        }

        .poster-modal-content {
            background: #ffffff;
            border-radius: 16px;
            width: 100%;
            max-width: 42rem;
            max-height: 100vh;
            padding: 24px;
            box-shadow: 0 8px 40px rgba(10, 35, 66, 0.22);
            transform: translateY(10px) scale(0.98);
            opacity: 0;
            transition: transform 220ms ease, opacity 220ms ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: visible;
        }

        .poster-modal-overlay.is-visible .poster-modal-content {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .poster-modal-header {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            position: absolute;
            top: 16px;
            right: 16px;
            z-index: 10000;
        }

        .poster-modal-close {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 1px solid var(--color-sky, #dceaf8);
            background: #ffffff;
            color: var(--color-ocean, #1B6CA8);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-family: 'Sora', sans-serif;
            font-size: 24px;
            line-height: 1;
            transition: background-color 160ms ease, color 160ms ease, border-color 160ms ease;
            padding: 0;
        }

        .poster-modal-close:hover {
            background: #f0f4f8;
            border-color: var(--color-steel-blue, #5ba4cf);
            color: var(--color-midnight, #0A2342);
        }

        .poster-modal-image {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
            border-radius: 12px;
            width: 100%;
        }
    </style>
@endpush

@section('content')
    <div class="event-view-page">
        <div id="toastContainer"></div>
    <div class="card event-view-card">
        <div class="header-row event-view-header">
            <h1>{{ $event->title }}</h1>
            <div class="actions event-view-actions">
                <a class="btn" href="{{ route('events.index') }}">Back</a>
                @if ($event->isRegistrationOpen())
                    <a class="btn btn-primary" href="{{ route('events.participants.index', $event) }}?event_id={{ $event->id }}&open_register_participant=1">Register Participant</a>
                @endif
            </div>
        </div>

        <div class="event-view-meta">
            <div class="event-view-item">
                <span class="event-view-label">Date</span>
                <span class="event-view-value">{{ $event->dateRangeLabel() }}</span>
            </div>
            <div class="event-view-item">
                <span class="event-view-label">Type</span>
                <span class="event-view-value">{{ $event->type === 'conference' ? 'Conference' : 'School Event' }}</span>
            </div>
            <div class="event-view-item">
                <span class="event-view-label">Attendance Type</span>
                <span class="event-view-value">{{ $event->attendanceTypeLabel() }}</span>
            </div>
            <div class="event-view-item">
                <span class="event-view-label">Registration Opens</span>
                <span class="event-view-value">{{ $event->start_registration ? \Carbon\Carbon::parse($event->start_registration)->format('M d, Y h:i A') : 'N/A' }}</span>
            </div>
            <div class="event-view-item">
                <span class="event-view-label">Registration Closes</span>
                <span class="event-view-value">{{ $event->end_registration ? \Carbon\Carbon::parse($event->end_registration)->format('M d, Y h:i A') : 'N/A' }}</span>
            </div>
            <div class="event-view-item">
                <span class="event-view-label">Registration Status</span>
                <span class="event-view-value">
                    <span class="event-status-pill {{ $event->registration_open ? 'event-status-open' : 'event-status-closed' }}">{{ $event->registration_open ? 'Open' : 'Closed' }}</span>
                </span>
            </div>
            <div class="event-view-item">
                <span class="event-view-label">Location</span>
                <span class="event-view-value">{{ $event->location }}</span>
            </div>
            @if ($event->department)
                <div class="event-view-item">
                    <span class="event-view-label">Department</span>
                    <span class="event-view-value">{{ $event->department }}</span>
                </div>
            @endif
            @if ($event->program)
                <div class="event-view-item">
                    <span class="event-view-label">Program</span>
                    <span class="event-view-value">{{ $event->program }}</span>
                </div>
            @endif
        @if ($event->type === 'conference')
            <div class="event-view-item">
                <span class="event-view-label">Conference Title</span>
                <span class="event-view-value">{{ $event->conference_title }}</span>
            </div>
            <div class="event-view-item">
                <span class="event-view-label">Theme</span>
                <span class="event-view-value">{{ $event->theme ?: 'N/A' }}</span>
            </div>
            <div class="event-view-item span-2">
                <span class="event-view-label">Keywords</span>
                <span class="event-view-value">{{ is_array($event->keywords) ? implode(', ', $event->keywords) : 'N/A' }}</span>
            </div>
        @else
            <div class="event-view-item">
                <span class="event-view-label">Event Title</span>
                <span class="event-view-value">{{ $event->event_title }}</span>
            </div>
            <div class="event-view-item span-2">
                <span class="event-view-label">Description</span>
                <span class="event-view-value">{{ $event->description ?: 'No description provided.' }}</span>
            </div>
        @endif
            <div class="event-view-item">
                <span class="event-view-label">Total Participants</span>
                <span class="event-view-value">{{ $event->participants_count }}</span>
            </div>
            <div class="event-view-item">
                <span class="event-view-label">Average Rating</span>
                <span class="event-view-value">{{ $event->average_rating ?? 'N/A' }}</span>
            </div>
        </div>

        <div class="event-assets-grid">
            <div class="event-asset-card">
                <h3 class="event-asset-title">Event Poster</h3>
                @if ($event->poster_url)
                    <div class="poster-preview" style="cursor: pointer;" id="posterThumbnail" data-poster-url="{{ $event->poster_url }}">
                        <img src="{{ $event->poster_url }}" alt="Event Poster">
                    </div>
                    <div class="event-asset-actions">
                        <button type="button" class="btn" id="viewPosterBtn" data-poster-url="{{ $event->poster_url }}" style="font-family: 'Sora', sans-serif;">View Full Poster</button>
                    </div>
                @else
                    <div class="asset-placeholder">No poster uploaded yet.</div>
                @endif
            </div>

            @if ($event->type === 'conference')
                <div class="event-asset-card">
                    <h3 class="event-asset-title">Conference Template File</h3>
                    @if ($event->template_file_path)
                        <div class="template-preview" style="margin-bottom: 1rem;">
                            <iframe 
                                src="https://docs.google.com/viewer?url=https://sesmcvjwmkphgkzawewn.supabase.co/storage/v1/object/public/event-templates/{{ $event->template_file_path }}&embedded=true"
                                width="100%" 
                                height="400px"
                                frameborder="0"
                                style="border-radius: 8px;">
                            </iframe>
                        </div>
                        <div class="event-asset-actions">
                            <a class="btn" href="{{ route('events.download-template', $event) }}" style="font-family: 'Sora', sans-serif;">Download Conference Template</a>
                        </div>
                    @else
                        <div class="asset-placeholder">No conference template uploaded yet.</div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if (!auth()->user()->hasRole('event_staff') || $event->created_by === auth()->id())
        <div class="card event-view-card">
            <div class="header-row event-view-header">
                <h2>Participants</h2>
                <a class="btn" href="{{ route('events.participants.index', $event) }}?event_id={{ $event->id }}">Open Full List</a>
            </div>

            <div class="participants-summary">
                <p class="participants-summary-label">Total Participants</p>
                <p class="participants-summary-value">{{ number_format($event->participants_count ?? 0) }}</p>
                <p class="participants-note">Participants are managed in the full list page.</p>
            </div>
        </div>
    @endif

    <!-- Poster Modal -->
    <div id="posterModal" class="poster-modal-overlay" aria-hidden="true" role="dialog" aria-modal="true">
        <div class="poster-modal-content">
            <div class="poster-modal-header">
                <button type="button" class="poster-modal-close" aria-label="Close modal">×</button>
            </div>
            <img id="modalPosterImage" class="poster-modal-image" alt="Event Poster" src="">
        </div>
    </div>

    <button id="scrollTopBtn" type="button" class="scroll-top-btn" aria-label="Scroll to top" title="Scroll to top">
        <svg viewBox="0 0 24 24" fill="none" width="20" height="20" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 19V5" />
            <path d="M5 12l7-7 7 7" />
        </svg>
    </button>
    </div>
@endsection

@push('scripts')
    <script>
        // Poster Modal Handler
        (function() {
            const posterModal = document.getElementById('posterModal');
            const viewPosterBtn = document.getElementById('viewPosterBtn');
            const posterThumbnail = document.getElementById('posterThumbnail');
            const modalCloseBtn = document.querySelector('.poster-modal-close');
            const modalPosterImage = document.getElementById('modalPosterImage');

            if (!posterModal) return;

            const openModal = function(posterUrl) {
                if (!posterUrl) return;
                modalPosterImage.src = posterUrl;
                posterModal.classList.add('active', 'is-visible');
                posterModal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            };

            const closeModal = function() {
                posterModal.classList.remove('is-visible', 'active');
                posterModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            };

            // Open modal on button click
            if (viewPosterBtn) {
                viewPosterBtn.addEventListener('click', function() {
                    const posterUrl = this.getAttribute('data-poster-url');
                    openModal(posterUrl);
                });
            }

            // Open modal on thumbnail click
            if (posterThumbnail) {
                posterThumbnail.addEventListener('click', function() {
                    const posterUrl = this.getAttribute('data-poster-url');
                    openModal(posterUrl);
                });
            }

            // Close modal on button click
            if (modalCloseBtn) {
                modalCloseBtn.addEventListener('click', closeModal);
            }

            // Close modal on outside click
            posterModal.addEventListener('click', function(e) {
                if (e.target === posterModal) {
                    closeModal();
                }
            });

            // Close modal on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && posterModal.classList.contains('is-visible')) {
                    closeModal();
                }
            });
        })();

        function showToast(message, type = 'info', duration = 4000) {
            var toastContainer = document.getElementById('toastContainer');
            if (!toastContainer) return;

            var toast = document.createElement('div');
            toast.className = 'modal-floating-label modal-floating-' + type + ' modal-toast';
            toast.textContent = message;
            toastContainer.appendChild(toast);

            window.setTimeout(function () {
                toast.classList.add('is-hiding');
            }, duration - 400);

            window.setTimeout(function () {
                if (toast && toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, duration);
        }

        document.addEventListener('DOMContentLoaded', function () {
            @if (session('status'))
                showToast({{ json_encode(session('status')) }}, 'success', 4000);
            @endif

            var scrollTopBtn = document.getElementById('scrollTopBtn');
            var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (!scrollTopBtn) {
                return;
            }

            var toggleScrollTop = function () {
                if (window.scrollY > 320) {
                    scrollTopBtn.classList.add('is-visible');
                } else {
                    scrollTopBtn.classList.remove('is-visible');
                }
            };

            toggleScrollTop();
            window.addEventListener('scroll', toggleScrollTop, { passive: true });

            scrollTopBtn.addEventListener('click', function () {
                window.scrollTo({
                    top: 0,
                    behavior: prefersReducedMotion ? 'auto' : 'smooth'
                });
            });
        });
    </script>
@endpush
