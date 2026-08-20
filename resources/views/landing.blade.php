<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Eventure — Empowering Events. Connecting People.</title>
    <link rel="icon" type="image/png" sizes="any" href="{{ asset('eventuretabicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('eventuretabicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sora: ['Sora', 'sans-serif'],
                    },
                    colors: {
                        page: '#e8f4fd',
                        em4: '#1b6ca8',
                        em3: '#58a4cf',
                        em6: '#ff6b35',
                        live: '#22c55e',
                    },
                },
            },
        }
    </script>
    <style>
        :root {
            --scrollbar-thumb: rgba(107, 114, 128, 0.45);
            --scrollbar-thumb-hover: rgba(107, 114, 128, 0.68);
            --scrollbar-track: transparent;
        }

        html { scroll-behavior: smooth; }
        *, *::before, *::after { font-family: 'Sora', sans-serif; }

        * {
            scrollbar-width: thin;
            scrollbar-color: var(--scrollbar-thumb) var(--scrollbar-track);
        }

        *::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        *::-webkit-scrollbar-track {
            background: var(--scrollbar-track);
        }

        *::-webkit-scrollbar-thumb {
            background-color: var(--scrollbar-thumb);
            border-radius: 999px;
            border: 2px solid transparent;
            background-clip: content-box;
        }

        *::-webkit-scrollbar-thumb:hover {
            background-color: var(--scrollbar-thumb-hover);
        }

        @keyframes ticker {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-ticker { animation: ticker 35s linear infinite; }

        @keyframes pulse-green {
            0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(34,197,94,0.5); }
            50%      { opacity: .6; box-shadow: 0 0 8px 2px rgba(34,197,94,0.3); }
        }
        .pulse-live { animation: pulse-green 1.5s ease-in-out infinite; }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            letter-spacing: -0.03em;
            line-height: 1;
        }

        .brand img {
            height: 3rem;
            width: auto;
        }

        .brand-text {
            display: inline-flex;
            letter-spacing: -0.03em;
        }

        .card {
            background: #ffffff;
            border: 1px solid rgba(27,108,168,0.18);
        }
        .card-live {
            background: rgba(34,197,94,0.09);
            border: 1px solid rgba(34,197,94,0.24);
        }
        .hero-glow {
            background: radial-gradient(ellipse at 60% 0%, rgba(88,164,207,0.2) 0%, transparent 62%);
        }

        body {
            background: #e8f4fd;
            color: #0a2342;
        }

        section .text-white,
        footer .text-white {
            color: #0a2342 !important;
        }

        section [style*="color:rgba(255,255,255,0.6)"],
        footer [style*="color:rgba(255,255,255,0.6)"] {
            color: #5b7a96 !important;
        }

        section [style*="color:rgba(255,255,255,0.5)"],
        footer [style*="color:rgba(255,255,255,0.5)"] {
            color: #6f8da7 !important;
        }

        section [style*="color:rgba(255,255,255,0.35)"],
        footer [style*="color:rgba(255,255,255,0.35)"] {
            color: #7c97af !important;
        }

        footer {
            background: #f4faff;
        }

        @keyframes fade-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-title { animation: fade-up 700ms ease-out both; }
        .hero-sub { animation: fade-up 700ms ease-out 120ms both; }
        .hero-actions { animation: fade-up 700ms ease-out 220ms both; }

        .reveal {
            opacity: 0;
            transform: translateY(22px);
            transition: opacity 560ms ease, transform 560ms ease;
            will-change: opacity, transform;
        }
        .reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-delay-1 { transition-delay: 100ms; }
        .reveal-delay-2 { transition-delay: 200ms; }
        .reveal-delay-3 { transition-delay: 300ms; }

        .lift-hover {
            transition: transform 280ms ease, box-shadow 280ms ease, filter 280ms ease;
        }
        .lift-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 35px rgba(0,0,0,0.2);
        }

        .scroll-top-btn {
            position: fixed;
            right: 24px;
            bottom: 24px;
            width: 48px;
            height: 48px;
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
            z-index: 999;
        }

        .template-modal-overlay {
            opacity: 0;
            transition: opacity 300ms ease;
        }
        .template-modal-overlay.show {
            opacity: 1;
        }

        .template-modal-content {
            transform: translateY(20px);
            opacity: 0;
            transition: opacity 300ms ease, transform 300ms ease;
        }
        .template-modal-overlay.show .template-modal-content {
            transform: translateY(0);
            opacity: 1;
        }

        @media (max-width: 640px) {
            .template-modal-content {
                max-width: calc(100vw - 32px) !important;
                max-height: calc(100vh - 32px) !important;
            }
        }

        .paste-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            color: #6b7280;
            cursor: pointer;
            transition: color 200ms ease;
            flex-shrink: 0;
        }
        .paste-btn:hover {
            color: #1b6ca8;
        }
        .paste-btn.success {
            color: #16a34a;
        }
        .scroll-top-btn.is-visible {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        .landing-registration-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(10, 35, 66, 0.55);
            z-index: 9998;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-y: auto;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 180ms ease, visibility 0s linear 180ms;
        }

        .landing-registration-modal-overlay.is-visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition: opacity 180ms ease;
        }

        .landing-registration-modal {
            width: min(900px, 100%);
            max-width: 900px;
            max-height: calc(100vh - 40px);
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 30px 70px rgba(10, 35, 66, 0.18);
            overflow: hidden;
            border: 1px solid rgba(27,108,168,0.18);
            transform: translateY(10px) scale(0.98);
            opacity: 0;
            transition: transform 220ms ease, opacity 220ms ease;
        }

        .landing-registration-modal-overlay.is-visible .landing-registration-modal {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .landing-registration-body {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 0;
            min-height: auto;
            max-height: calc(100vh - 160px);
            overflow-y: auto;
        }

        .landing-registration-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 24px;
            border-bottom: 1px solid rgba(229, 236, 244, 1);
        }

        .landing-registration-title {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 800;
            color: #0a2342;
        }

        .landing-registration-close {
            width: 42px;
            height: 42px;
            border: 1px solid rgba(27,108,168,0.18);
            border-radius: 12px;
            background: #ffffff;
            color: #1b6ca8;
            font-size: 1.3rem;
            cursor: pointer;
            transition: background 180ms ease, border-color 180ms ease, color 180ms ease;
        }

        .landing-registration-close:hover {
            background: #f1f5f9;
            border-color: rgba(27,108,168,0.4);
            color: #0a2342;
        }

        .landing-registration-body {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 0;
            min-height: auto;
            max-height: calc(100vh - 160px);
            overflow-y: auto;
        }

        .landing-registration-panel {
            padding: 24px;
        }

        .landing-registration-info {
            background: #f8fbff;
            border-left: 1px solid rgba(27,108,168,0.12);
            padding: 24px;
            display: grid;
            gap: 18px;
        }

        .landing-registration-field {
            display: grid;
            gap: 6px;
            margin-bottom: 12px;
        }

        .landing-registration-field label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #0a2342;
        }

        .landing-registration-field input,
        .landing-registration-field select,
        .landing-registration-field textarea {
            width: 100%;
            min-height: 36px;
            padding: 6px 12px;
            border: 1px solid rgba(207, 224, 239, 1);
            border-radius: 14px;
            font-size: 0.75rem;
            color: #0a2342;
            background: #ffffff;
            transition: border-color 180ms ease, box-shadow 180ms ease;
        }

        .landing-registration-field select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235BA4CF' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 14px 14px;
        }

        .landing-registration-field input[type="file"] {
            min-height: 40px;
            padding: 4px 8px;
            cursor: pointer;
        }

        .landing-registration-field input[type="file"]::file-selector-button,
        .landing-registration-field input[type="file"]::-webkit-file-upload-button {
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

        .landing-registration-field input[type="file"]:hover::file-selector-button,
        .landing-registration-field input[type="file"]:hover::-webkit-file-upload-button {
            background: #0f5e95;
            border-color: #0f5e95;
        }

        .landing-registration-field textarea {
            min-height: 56px;
            resize: vertical;
        }

        .landing-registration-field input:focus,
        .landing-registration-field select:focus,
        .landing-registration-field textarea:focus {
            outline: none;
            border-color: rgba(27,108,168,0.5);
            box-shadow: 0 0 0 4px rgba(27,108,168,0.08);
        }

        .landing-registration-section-title {
            margin: 0 0 10px;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            color: #5b7a96;
        }

        .landing-registration-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 0.85rem;
            font-weight: 700;
            background: rgba(27,108,168,0.12);
            color: #1b6ca8;
        }

        .landing-registration-submit {
            width: 100%;
            border: none;
            border-radius: 16px;
            padding: 14px 18px;
            background: #1b6ca8;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.98rem;
            cursor: pointer;
            transition: filter 180ms ease;
        }

        .landing-registration-submit:hover {
            filter: brightness(1.05);
        }

        .landing-registration-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: 1px solid rgba(27,108,168,0.14);
            border-radius: 999px;
            padding: 10px 14px;
            cursor: pointer;
            transition: background 180ms ease, border-color 180ms ease;
        }

        .landing-registration-chip.active {
            background: rgba(27,108,168,0.12);
            border-color: rgba(27,108,168,0.3);
        }

        .landing-registration-footer {
            margin-top: 20px;
            font-size: 0.92rem;
            color: #65748b;
            line-height: 1.6;
        }

        .modal-floating-label {
            position: relative;
            z-index: 10051;
            min-width: 280px;
            max-width: 420px;
            border-radius: 10px;
            padding: 10px 14px;
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            line-height: 1.4;
            box-shadow: 0 10px 24px rgba(10, 35, 66, 0.2);
            color: inherit;
            opacity: 0;
            transform: translateY(-12px);
            transition: transform 250ms ease, opacity 250ms ease;
        }

        .modal-floating-label.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .modal-floating-label.is-hiding {
            opacity: 0;
            transform: translateY(-12px);
        }

        .modal-floating-success {
            background: #ecfdf5;
            border: 1px solid #34d399;
            color: #065f46;
        }

        .modal-floating-error {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .modal-floating-info {
            background: #eff6ff;
            border: 1px solid #93c5fd;
            color: #1d4ed8;
        }

        /* Privacy Notice Modal Styles */
        .privacy-notice-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(10, 35, 66, 0.55);
            z-index: 9997;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-y: auto;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 180ms ease, visibility 0s linear 180ms;
        }

        .privacy-notice-modal-overlay.is-visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition: opacity 180ms ease;
        }

        .privacy-notice-modal {
            width: min(560px, 100%);
            max-width: 560px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 30px 70px rgba(10, 35, 66, 0.18);
            overflow: hidden;
            border: 1px solid rgba(27,108,168,0.18);
            transform: translateY(10px) scale(0.98);
            opacity: 0;
            transition: transform 220ms ease, opacity 220ms ease;
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 40px);
        }

        .privacy-notice-modal-overlay.is-visible .privacy-notice-modal {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .privacy-notice-modal-header {
            padding: 28px;
            border-bottom: 1px solid rgba(27,108,168,0.18);
            flex-shrink: 0;
        }

        .privacy-notice-modal-title {
            margin: 0;
            font-family: 'Sora', sans-serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: #0a2342;
        }

        .privacy-notice-modal-content {
            flex: 1;
            overflow-y: auto;
            padding: 28px;
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            color: #4b5563;
            line-height: 1.7;
            max-height: 400px;
        }

        .privacy-notice-section {
            margin-top: 16px;
        }

        .privacy-notice-section:first-child {
            margin-top: 0;
        }

        .privacy-notice-section-heading {
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: #0a2342;
            margin: 0 0 8px 0;
        }

        .privacy-notice-section-text {
            margin: 0 0 8px 0;
        }

        .privacy-notice-bullet-list {
            margin: 8px 0 0 20px;
            padding: 0;
            list-style: disc;
        }

        .privacy-notice-bullet-list li {
            margin-bottom: 8px;
        }

        .privacy-notice-modal-footer {
            padding: 28px;
            border-top: 1px solid rgba(27,108,168,0.18);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .privacy-notice-checkbox-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .privacy-notice-checkbox {
            width: 18px;
            height: 18px;
            min-width: 18px;
            cursor: pointer;
            accent-color: #1b6ca8;
        }

        .privacy-notice-checkbox-label {
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            color: #0a2342;
            cursor: pointer;
            margin: 0;
        }

        .privacy-notice-buttons {
            display: flex;
            flex-direction: row;
            justify-content: flex-end;
            gap: 10px;
        }

        .privacy-notice-btn {
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 180ms ease;
        }

        .privacy-notice-btn-cancel {
            background: #f1f5f9;
            color: #0a2342;
            border: 1px solid rgba(27,108,168,0.18);
        }

        .privacy-notice-btn-cancel:hover {
            background: #e2e8f0;
            border-color: rgba(27,108,168,0.3);
        }

        .privacy-notice-btn-accept {
            background: #1b6ca8;
            color: #ffffff;
            border: 1px solid #1b6ca8;
        }

        .privacy-notice-btn-accept:hover:not(:disabled) {
            background: #0f5e95;
            border-color: #0f5e95;
            filter: brightness(1.05);
        }

        .privacy-notice-btn-accept:disabled {
            background: #cbd5e1;
            border-color: #cbd5e1;
            color: #ffffff;
            cursor: not-allowed;
            opacity: 0.5;
        }

        @keyframes toast-in {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            #landingToastContainer {
                top: 16px !important;
                right: 50% !important;
                transform: translateX(50%) !important;
                left: auto !important;
                width: calc(100% - 32px) !important;
                max-width: 420px !important;
            }
        }

        @media (max-width: 960px) {
            .landing-registration-body {
                grid-template-columns: 1fr;
            }

            .landing-registration-info {
                border-left: none;
                border-top: 1px solid rgba(27,108,168,0.12);
            }
        }

        @media (max-width: 640px) {
            .landing-registration-header,
            .landing-registration-panel,
            .landing-registration-info {
                padding: 18px;
            }

            .landing-registration-title {
                font-size: 1.35rem;
            }

            .landing-registration-field {
                margin-bottom: 14px;
            }
        }
        .scroll-top-btn:hover {
            filter: brightness(1.08);
            transform: translateY(-2px) scale(1.02);
        }
        .scroll-top-btn:focus-visible {
            outline: 2px solid rgba(255,255,255,0.9);
            outline-offset: 3px;
        }

        .sign-in-link {
            color: rgba(255,255,255,0.65);
            transition: color 260ms ease, text-shadow 260ms ease;
        }
        .sign-in-link:hover {
            color: #bfdfff;
            text-shadow: 0 0 12px rgba(191,223,255,0.35);
        }

        .icon-orange {
            color: #ff6b35;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            vertical-align: -2px;
        }

        .icon-orange svg {
            width: 1em;
            height: 1em;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .icon-badge {
            width: 36px;
            height: 36px;
            border-radius: 9999px;
            background: rgba(34,197,94,0.2);
            color: #22c55e;
        }

        .icon-badge svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .icon-feature {
            color: #ff6b35;
        }

        .icon-feature svg {
            width: 24px;
            height: 24px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .animate-ticker, .pulse-live, .hero-title, .hero-sub, .hero-actions {
                animation: none !important;
            }
            .reveal, .reveal.is-visible {
                opacity: 1;
                transform: none;
                transition: none;
            }
            .lift-hover, .lift-hover:hover {
                transform: none;
                box-shadow: none;
            }
            .scroll-top-btn, .scroll-top-btn:hover {
                transition: none;
                transform: none;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-page text-[#0a2342] font-sora">

    {{-- ========== NAVBAR ========== --}}
    <nav class="fixed top-0 inset-x-0 z-50" style="background:rgba(10,35,66,0.9);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-bottom:1px solid rgba(27,108,168,0.35)">
        <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-3.5">
            <a href="{{ url('/') }}" class="brand text-2xl font-bold">
                <img src="{{ asset('eventurelogo.png') }}" alt="Eventure logo">
                <span class="brand-text"><span class="text-white">Event</span><span class="text-em4">ure</span></span>
            </a>

            <div class="hidden md:flex items-center gap-8 text-sm font-normal" style="color:rgba(255,255,255,0.6)">
                <a href="#ongoing" class="hover:text-em4 transition">Ongoing</a>
                <a href="#upcoming" class="hover:text-em4 transition">Upcoming</a>
                <a href="#features" class="hover:text-em4 transition">Features</a>
                <a href="#about" class="hover:text-em4 transition">About</a>
                <a href="#need-help" class="hover:text-em4 transition">Need Help?</a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-sm sign-in-link">Sign In</a>
            </div>
        </div>
    </nav>

    {{-- ========== ANNOUNCEMENT BANNER ========== --}}
    @isset($announcements)
        @if($announcements->isNotEmpty())
            <div class="fixed top-[80px] inset-x-0 z-40 overflow-hidden" style="background:rgba(27,108,168,0.14)">
                <div class="flex whitespace-nowrap animate-ticker">
                    @foreach($announcements as $a)
                        <span class="inline-flex items-center gap-2 px-8 py-2 text-sm font-semibold uppercase tracking-wider" style="color:rgba(255,255,255,0.6)">
                            @if(!$a->hasEnded() && !$a->start_date->isFuture())
                                <span class="w-2 h-2 rounded-full bg-live pulse-live"></span>
                            @elseif($a->hasEnded())
                                <span class="w-2 h-2 rounded-full bg-gray-500"></span>
                            @else
                                <span class="w-2 h-2 rounded-full bg-em4"></span>
                            @endif
                            {{ $a->title }}
                            <span style="color:rgba(255,255,255,0.25)">•</span>
                            <span class="text-xs {{ $a->hasEnded() ? 'text-gray-400' : (!$a->start_date->isFuture() ? 'text-live' : 'text-em3') }}">
                                {{ $a->hasEnded() ? 'Completed' : ($a->start_date->isFuture() ? 'Upcoming' : 'Ongoing') }}
                            </span>
                        </span>
                    @endforeach
                    @foreach($announcements as $a)
                        <span class="inline-flex items-center gap-2 px-8 py-2 text-sm font-semibold uppercase tracking-wider" style="color:rgba(255,255,255,0.6)">
                            @if(!$a->hasEnded() && !$a->start_date->isFuture())
                                <span class="w-2 h-2 rounded-full bg-live pulse-live"></span>
                            @elseif($a->hasEnded())
                                <span class="w-2 h-2 rounded-full bg-gray-500"></span>
                            @else
                                <span class="w-2 h-2 rounded-full bg-em4"></span>
                            @endif
                            {{ $a->title }}
                            <span style="color:rgba(255,255,255,0.25)">•</span>
                            <span class="text-xs {{ $a->hasEnded() ? 'text-gray-400' : (!$a->start_date->isFuture() ? 'text-live' : 'text-em3') }}">
                                {{ $a->hasEnded() ? 'Completed' : ($a->start_date->isFuture() ? 'Upcoming' : 'Ongoing') }}
                            </span>
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
    @endisset

    {{-- ========== HERO ========== --}}
    <section class="relative pt-44 pb-24 px-6 hero-glow" data-reveal>
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="hero-title font-extrabold leading-tight text-white" style="font-size:clamp(44px,6vw,72px);letter-spacing:-2px;font-weight:800">
                Empowering Events.<br>
                <span class="text-em4">Connecting People.</span>
            </h1>
            <p class="hero-sub mt-6 text-lg max-w-2xl mx-auto font-normal" style="color:rgba(255,255,255,0.6)">
                The platform for creating, managing, and analyzing events — from school events to large-scale conferences.
            </p>

            <div class="hero-actions mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <!-- Browse Events login removed for participants -->
                <a href="#upcoming" class="lift-hover px-7 py-3.5 rounded-[10px] text-sm font-semibold text-white hover:bg-white/10 transition-all" style="border:1px solid rgba(255,255,255,0.25)">
                    View Upcoming →
                </a>
            </div>

            {{-- Stats --}}
            @isset($stats)
                <div class="mt-16 flex justify-center">
                    @foreach($stats as $stat)
                        <div class="card lift-hover reveal rounded-2xl p-6 text-center" data-reveal>
                            <div class="text-em4 font-extrabold" style="font-size:36px;font-weight:800">{{ $stat['value'] }}</div>
                            <div class="mt-1 uppercase tracking-wider" style="font-size:11px;color:rgba(255,255,255,0.5)">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            @endisset
        </div>
    </section>

    {{-- ========== ONGOING EVENTS ========== --}}
    <section id="ongoing" class="py-20 px-6" data-reveal>
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center gap-3 mb-10">
                <span class="w-3 h-3 rounded-full bg-live pulse-live"></span>
                <h2 class="font-bold text-white" style="font-size:22px;font-weight:700">Events Today</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @if(isset($ongoingEvents) && $ongoingEvents->isNotEmpty())
                    @foreach($ongoingEvents as $event)
                        <div class="card-live lift-hover reveal rounded-2xl overflow-hidden hover:brightness-110 transition group" data-reveal>
                            @if($event->poster_path)
                                <div class="h-48 overflow-hidden">
                                    <img src="https://sesmcvjwmkphgkzawewn.supabase.co/storage/v1/object/public/event-posters/{{ $event->poster_path }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                </div>
                            @endif
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider text-live" style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3)">
                                        <span class="w-2 h-2 rounded-full bg-live pulse-live"></span>
                                        Today
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-2">{{ $event->title }}</h3>
                                <div class="space-y-1 text-sm" style="color:rgba(255,255,255,0.6)">
                                    <p><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 21s7-5.2 7-11a7 7 0 1 0-14 0c0 5.8 7 11 7 11z"></path><circle cx="12" cy="10" r="2.5"></circle></svg></span> {{ $event->location ?? 'TBA' }}</p>
                                    <p><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M16 3v4M8 3v4M3 10h18"></path></svg></span> {{ $event->dateRangeLabel() }}</p>
                                    <p><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></span> {{ $event->participants_count ?? $event->participants->count() }} attendees</p>
                                    @if($event->type === 'conference' && $event->theme)
                                        <p><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M9 18h6"/><path d="M10 13h4"/><path d="M5.2 9a6.8 6.8 0 1 1 13.6 0c0 2.3-1.2 4.3-3.1 5.5l-.5.3v2.4a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1v-2.4l-.5-.3A6.8 6.8 0 0 1 5.2 9z"/></svg></span> {{ $event->theme }}</p>
                                    @endif
                                    @if($event->type === 'conference' && $event->keywords)
                                        <p><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg></span> {{ is_array($event->keywords) ? implode(', ', $event->keywords) : $event->keywords }}</p>
                                    @endif
                                    @if($event->program_file_path)
                                        <p><button type="button" class="program-download-btn inline-flex items-center gap-1 underline hover:opacity-80 transition" data-event-id="{{ $event->id }}"><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline><path d="M9 15l3 3 5-5"></path></svg></span> {{ $event->program_file_name ?? basename($event->program_file_path) }}</button></p>
                                    @endif
                                    @if($event->type === 'conference' && $event->template_file_path)
                                        <p><button type="button" class="template-download-btn inline-flex items-center gap-1 underline hover:opacity-80 transition" data-event-id="{{ $event->id }}"><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline><path d="M9 15l3 3 5-5"></path></svg></span> {{ $event->template_file_name ?? basename($event->template_file_path) }}</button></p>
                                    @endif
                                    @if($event->template_url)
                                        <p><button type="button" class="resource-link-btn inline-flex items-center gap-1 underline hover:opacity-80 transition" data-event-id="{{ $event->id }}"><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg></span> Resources Link</button></p>
                                    @endif
                                    @if(in_array($event->attendance_type, ['virtual', 'both']) && $event->meet_link)
                                        <p><button type="button" class="meet-link-btn inline-flex items-center gap-1 underline hover:opacity-80 transition" data-event-id="{{ $event->id }}"><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 7l-7 5 7 5V7z"></path><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg></span> Meet Link</button></p>
                                    @endif
                                @if($now->lt(\Carbon\Carbon::parse($event->start_registration)->setTimezone('Asia/Manila')))
                                    <button disabled class="mt-4 inline-block px-5 py-2.5 rounded-[10px] text-sm font-semibold cursor-not-allowed" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.35)">
                                        Registration Not Yet Open
                                    </button>
                                @elseif($now->gt(\Carbon\Carbon::parse($event->end_registration)->setTimezone('Asia/Manila')))
                                    <button disabled class="mt-4 inline-block px-5 py-2.5 rounded-[10px] text-sm font-semibold cursor-not-allowed" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.35)">
                                        Registration Closed
                                    </button>
                                @else
                                    <button type="button" class="mt-4 inline-flex w-full justify-center px-5 py-2.5 bg-live text-white rounded-[10px] text-sm font-semibold hover:brightness-110 transition open-registration-modal" data-event-id="{{ $event->id }}" data-event-title="{{ $event->title }}" data-event-date-range="{{ $event->dateRangeLabel() }}" data-event-location="{{ $event->location ?? 'TBA' }}" data-event-status="Open" data-event-start="{{ $event->start_registration }}" data-event-end="{{ $event->end_registration }}" data-event-type="{{ $event->type }}" data-event-register-url="{{ url('/events/' . $event->id . '/participants') }}">
                                        Register Now
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="md:col-span-2 lg:col-span-3 card-live reveal rounded-2xl p-8" data-reveal>
                        <div class="flex items-start gap-3">
                            <span class="inline-flex items-center justify-center icon-badge" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M6 3h12"></path><path d="M6 21h12"></path><path d="M8 3v5a4 4 0 0 0 2 3.46L12 12l2 1.54A4 4 0 0 1 16 17v4"></path><path d="M16 3v5a4 4 0 0 1-2 3.46L12 12l-2 1.54A4 4 0 0 0 8 17v4"></path></svg></span>
                            <div>
                                <h3 class="text-lg font-bold text-white">No ongoing event right now</h3>
                                <p class="mt-1 text-sm" style="color:rgba(255,255,255,0.6)">
                                    Check back soon or explore upcoming events to register.
                                </p>
                                <a href="#upcoming" class="mt-4 inline-block px-5 py-2.5 rounded-[10px] text-sm font-semibold text-live hover:brightness-110 transition" style="background:rgba(34,197,94,0.12);border:1px solid rgba(34,197,94,0.35)">
                                    See Upcoming Events
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ========== UPCOMING EVENTS ========== --}}
    @if(isset($upcomingEvents) && $upcomingEvents->isNotEmpty())
        <section id="upcoming" class="py-20 px-6" data-reveal>
            <div class="max-w-7xl mx-auto">
                <h2 class="font-bold text-white mb-2" style="font-size:22px;font-weight:700">Upcoming Events</h2>
                <p class="mb-10" style="color:rgba(255,255,255,0.6)">Check out our upcoming events</p>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($upcomingEvents as $event)
                        <div class="card lift-hover reveal rounded-2xl overflow-hidden hover:brightness-110 transition group flex flex-col" data-reveal>
                            @if($event->poster_path)
                                <div class="h-48 overflow-hidden relative">
                                    <img src="https://sesmcvjwmkphgkzawewn.supabase.co/storage/v1/object/public/event-posters/{{ $event->poster_path }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @if($event->isRegistrationOpen())
                                        <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider text-em4" style="background:rgba(0,0,0,0.5)">
                                            Open
                                        </span>
                                    @endif
                                </div>
                            @else
                                <div class="h-48 bg-gradient-to-br from-teal-800 to-emerald-700 relative">
                                    @if($event->isRegistrationOpen())
                                        <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider text-em4" style="background:rgba(0,0,0,0.5)">
                                            Open
                                        </span>
                                    @endif
                                </div>
                            @endif
                            <div class="p-6 flex flex-col flex-1">
                                <h3 class="text-xl font-bold text-white mb-2">{{ $event->title }}</h3>
                                <div class="space-y-1 text-sm mb-4" style="color:rgba(255,255,255,0.6)">
                                    <p><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M16 3v4M8 3v4M3 10h18"></path></svg></span> {{ $event->dateRangeLabel() }}</p>
                                    <p><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 21s7-5.2 7-11a7 7 0 1 0-14 0c0 5.8 7 11 7 11z"></path><circle cx="12" cy="10" r="2.5"></circle></svg></span> {{ $event->location ?? 'TBA' }}</p>
                                    @if($event->type === 'conference' && $event->theme)
                                        <p><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M9 18h6"/><path d="M10 13h4"/><path d="M5.2 9a6.8 6.8 0 1 1 13.6 0c0 2.3-1.2 4.3-3.1 5.5l-.5.3v2.4a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1v-2.4l-.5-.3A6.8 6.8 0 0 1 5.2 9z"/></svg></span> {{ $event->theme }}</p>
                                    @endif
                                    @if($event->type === 'conference' && $event->keywords)
                                        <p><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg></span> {{ is_array($event->keywords) ? implode(', ', $event->keywords) : $event->keywords }}</p>
                                    @endif
                                    @if($event->program_file_path)
                                        <p><button type="button" class="program-download-btn inline-flex items-center gap-1 underline hover:opacity-80 transition" data-event-id="{{ $event->id }}"><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline><path d="M9 15l3 3 5-5"></path></svg></span> {{ $event->program_file_name ?? basename($event->program_file_path) }}</button></p>
                                    @endif
                                    @if($event->type === 'conference' && $event->template_file_path)
                                        <p><button type="button" class="template-download-btn inline-flex items-center gap-1 underline hover:opacity-80 transition" data-event-id="{{ $event->id }}"><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline><path d="M9 15l3 3 5-5"></path></svg></span> {{ $event->template_file_name ?? basename($event->template_file_path) }}</button></p>
                                    @endif
                                    @if($event->template_url)
                                        <p><button type="button" class="resource-link-btn inline-flex items-center gap-1 underline hover:opacity-80 transition" data-event-id="{{ $event->id }}"><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg></span> Resources Link</button></p>
                                    @endif
                                    @if(in_array($event->attendance_type, ['virtual', 'both']) && $event->meet_link)
                                        <p><button type="button" class="meet-link-btn inline-flex items-center gap-1 underline hover:opacity-80 transition" data-event-id="{{ $event->id }}"><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M23 7l-7 5 7 5V7z"></path><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg></span> Meet Link</button></p>
                                    @endif
                                </div>
                                <div class="mt-auto">
                                    @php $now = \Carbon\Carbon::now('Asia/Manila'); @endphp
                                    @if($now->lt(\Carbon\Carbon::parse($event->start_registration)->setTimezone('Asia/Manila')))
                                        <button disabled class="block w-full text-center px-5 py-2.5 rounded-[10px] text-sm font-semibold cursor-not-allowed" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.35)">
                                            Registration Not Yet Open
                                        </button>
                                    @elseif($now->gt(\Carbon\Carbon::parse($event->end_registration)->setTimezone('Asia/Manila')))
                                        <button disabled class="block w-full text-center px-5 py-2.5 rounded-[10px] text-sm font-semibold cursor-not-allowed" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.35)">
                                            Registration Closed
                                        </button>
                                    @else
                                        <button type="button" class="block w-full text-center px-5 py-2.5 rounded-[10px] text-sm font-semibold text-em4 hover:bg-em4/25 transition open-registration-modal" style="background:rgba(27,108,168,0.15);border:1px solid rgba(27,108,168,0.35)" data-event-id="{{ $event->id }}" data-event-title="{{ $event->title }}" data-event-date-range="{{ $event->dateRangeLabel() }}" data-event-location="{{ $event->location ?? 'TBA' }}" data-event-status="Open" data-event-start="{{ $event->start_registration }}" data-event-end="{{ $event->end_registration }}" data-event-type="{{ $event->type }}" data-event-register-url="{{ url('/events/' . $event->id . '/participants') }}">
                                            Register Now
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ========== RECENT / COMPLETED EVENTS ========== --}}
    @if(isset($recentEvents) && $recentEvents->isNotEmpty())
        <section id="recent" class="py-20 px-6" data-reveal>
            <div class="max-w-7xl mx-auto">
                <h2 class="font-bold text-white mb-2" style="font-size:22px;font-weight:700">Recently Completed</h2>
                <p class="mb-10" style="color:rgba(255,255,255,0.6)">A look back at our past events</p>

                <div class="space-y-4">
                    @foreach($recentEvents as $event)
                        <div class="card lift-hover reveal rounded-2xl p-6 flex flex-col md:flex-row md:items-center gap-6 hover:brightness-110 transition" data-reveal>
                            @if($event->poster_path)
                                <div class="w-full md:w-40 h-28 rounded-xl overflow-hidden flex-shrink-0">
                                    <img src="https://sesmcvjwmkphgkzawewn.supabase.co/storage/v1/object/public/event-posters/{{ $event->poster_path }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider text-em4" style="background:rgba(27,108,168,0.12);border:1px solid rgba(27,108,168,0.25)">
                                        <span class="icon-orange mr-1" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"></path></svg></span> Completed
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-white truncate">{{ $event->title }}</h3>
                                <div class="flex flex-wrap gap-4 mt-1 text-sm" style="color:rgba(255,255,255,0.6)">
                                    <span><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M16 3v4M8 3v4M3 10h18"></path></svg></span> {{ $event->dateRangeLabel() }}</span>
                                    <span><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 21s7-5.2 7-11a7 7 0 1 0-14 0c0 5.8 7 11 7 11z"></path><circle cx="12" cy="10" r="2.5"></circle></svg></span> {{ $event->location ?? 'TBA' }}</span>
                                    @if($event->type === 'conference' && $event->theme)
                                        <span><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M9 18h6"/><path d="M10 13h4"/><path d="M5.2 9a6.8 6.8 0 1 1 13.6 0c0 2.3-1.2 4.3-3.1 5.5l-.5.3v2.4a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1v-2.4l-.5-.3A6.8 6.8 0 0 1 5.2 9z"/></svg></span> {{ $event->theme }}</span>
                                    @endif
                                    @if($event->type === 'conference' && $event->keywords)
                                        <span><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg></span> {{ is_array($event->keywords) ? implode(', ', $event->keywords) : $event->keywords }}</span>
                                    @endif
                                    <span><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></span> {{ $event->participants_count ?? $event->participants->count() }} attended</span>
                                    @if($event->averageRating())
                                        <span><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="m12 3.8 2.57 5.2 5.74.83-4.16 4.05.98 5.72L12 16.9l-5.13 2.7.98-5.72L3.69 9.83l5.74-.83L12 3.8z" fill="currentColor" stroke="none"></path></svg></span> {{ $event->averageRating() }}/5</span>
                                    @endif
                                    @if($event->program_file_path)
                                        <button type="button" class="program-download-btn inline-flex items-center gap-1 underline hover:opacity-80 transition" data-event-id="{{ $event->id }}"><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline><path d="M9 15l3 3 5-5"></path></svg></span> {{ $event->program_file_name ?? basename($event->program_file_path) }}</button>
                                    @endif
                                    @if($event->type === 'conference' && $event->template_file_path)
                                        <button type="button" class="template-download-btn inline-flex items-center gap-1 underline hover:opacity-80 transition" data-event-id="{{ $event->id }}"><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline><path d="M9 15l3 3 5-5"></path></svg></span> {{ $event->template_file_name ?? basename($event->template_file_path) }}</button>
                                    @endif
                                    @if($event->template_url)
                                        <button type="button" class="resource-link-btn inline-flex items-center gap-1 underline hover:opacity-80 transition" data-event-id="{{ $event->id }}"><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg></span> Resources Link</button>
                                    @endif
                                    @if(in_array($event->attendance_type, ['virtual', 'both']) && $event->meet_link)
                                        <button type="button" class="meet-link-btn inline-flex items-center gap-1 underline hover:opacity-80 transition" data-event-id="{{ $event->id }}"><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M23 7l-7 5 7 5V7z"></path><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg></span> Meet Link</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ========== FEATURES ========== --}}
    <section id="features" class="py-20 px-6" data-reveal>
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="font-bold text-white mb-2" style="font-size:22px;font-weight:700">Everything You Need</h2>
            <p class="mb-14" style="color:rgba(255,255,255,0.6)">Powerful tools to run events of any size</p>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="card lift-hover reveal rounded-2xl p-8 hover:brightness-110 transition" data-reveal>
                    <div class="w-14 h-14 mx-auto mb-5 rounded-xl flex items-center justify-center text-2xl" style="background:rgba(27,108,168,0.12)">
                        <span class="icon-feature" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 9a2 2 0 0 0 2-2V5h14v2a2 2 0 1 0 0 4 2 2 0 1 0 0 4v2H5v-2a2 2 0 1 0 0-4 2 2 0 0 0 0-4z"></path><path d="M12 5v14"></path></svg></span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Registration</h3>
                    <p class="text-sm" style="color:rgba(255,255,255,0.6)">Seamless registration flows with digital ID generation, confirmation emails, and participant management.</p>
                </div>

                <div class="card lift-hover reveal reveal-delay-1 rounded-2xl p-8 hover:brightness-110 transition" data-reveal>
                    <div class="w-14 h-14 mx-auto mb-5 rounded-xl flex items-center justify-center text-2xl" style="background:rgba(27,108,168,0.12)">
                        <span class="icon-feature" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Attendee Management</h3>
                    <p class="text-sm" style="color:rgba(255,255,255,0.6)">Track attendance, manage participant data, issue digital IDs, and handle event-day check-ins effortlessly.</p>
                </div>

                <div class="card lift-hover reveal reveal-delay-2 rounded-2xl p-8 hover:brightness-110 transition" data-reveal>
                    <div class="w-14 h-14 mx-auto mb-5 rounded-xl flex items-center justify-center text-2xl" style="background:rgba(27,108,168,0.12)">
                        <span class="icon-feature" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 20h16"></path><path d="M7 16V9"></path><path d="M12 16V5"></path><path d="M17 16v-3"></path></svg></span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Analytics &amp; Surveys</h3>
                    <p class="text-sm" style="color:rgba(255,255,255,0.6)">Collect post-event evaluations, measure satisfaction ratings, and gain actionable insights from your audience.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== CTA ========== --}}
    <section class="py-24 px-6" data-reveal>
        <div class="max-w-4xl mx-auto text-center card reveal rounded-3xl p-12 md:p-16" data-reveal>
            <h2 class="font-extrabold text-white mb-4" style="font-size:clamp(24px,4vw,36px);font-weight:800;letter-spacing:-1px">
                Ready to Create Your <span class="text-em4">Event</span>?
            </h2>
            <p class="mb-8 max-w-xl mx-auto" style="color:rgba(255,255,255,0.6)">
                Join Eventure to deliver unforgettable event experiences.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('login') }}" class="lift-hover px-7 py-3.5 bg-em4 text-page rounded-[10px] text-sm font-bold hover:bg-em6 hover:scale-[1.03] transition-all shadow-lg shadow-em4/20">
                    Get Started
                </a>
            </div>
            </div>
        </div>
    </section>

    {{-- ========== FOOTER ========== --}}
    <!-- About Section -->
    <section id="about" class="py-20 px-6" style="position:relative;z-index:1;background:#0A2342;border-top:1px solid rgba(27,108,168,0.2);">
        <div class="max-w-7xl mx-auto">

            <!-- About Eventure -->
            <div class="mb-20">
                <p style="color:#5BA4CF;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.12em;margin-bottom:10px;">Who We Are</p>
                <h2 style="color:#ffffff;font-size:2rem;font-weight:700;margin-bottom:8px;">About <span style="color:#5BA4CF;">Eventure</span></h2>
                <div style="width:48px;height:3px;background:#1B6CA8;border-radius:2px;margin-bottom:36px;"></div>

                <div class="grid md:grid-cols-2 gap-16">
                    <div>
                        <p style="color:rgba(255,255,255,0.82);font-size:15px;line-height:1.9;margin-bottom:18px;">
                            Eventure is a comprehensive event management platform designed to streamline the planning, organization, and execution of events. It provides organizers with a centralized system for creating events, managing registrations, tracking attendance, generating certificates, and gathering post-event feedback. By digitizing these processes, Eventure helps reduce administrative workload while delivering a smoother and more engaging experience for attendees.
                        </p>
                        <p style="color:rgba(255,255,255,0.82);font-size:15px;line-height:1.9;">
                            Built with efficiency, accessibility, and user experience in mind, Eventure empowers organizations to manage events of all sizes with greater ease and accuracy. Whether for academic, organizational, professional, or community events, the platform offers the tools needed to coordinate activities, monitor participation, and ensure successful event outcomes.
                        </p>
                    </div>
                    <div style="display:flex;align-items:center;">
                        <blockquote style="border-left:3px solid #1B6CA8;padding:20px 24px;margin:0;background:rgba(27,108,168,0.08);border-radius:0 12px 12px 0;">
                            <p style="color:#ffffff;font-size:17px;font-style:italic;line-height:1.75;margin:0;">
                                "Great events don't happen by chance—they happen through seamless planning, meaningful connections, and the right technology."
                            </p>
                        </blockquote>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div style="border-top:1px solid rgba(27,108,168,0.2);margin-bottom:48px;"></div>

            <!-- About The Eventurers -->
            <div>
                <p style="color:#5BA4CF;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.12em;margin-bottom:10px;">The Team</p>
                <h2 style="color:#ffffff;font-size:2rem;font-weight:700;margin-bottom:8px;">About <span style="color:#5BA4CF;">The Eventurers</span></h2>
                <div style="width:48px;height:3px;background:#1B6CA8;border-radius:2px;margin-bottom:36px;"></div>

                <div class="grid md:grid-cols-2 gap-16">
                    <div>
                        <p style="color:rgba(255,255,255,0.82);font-size:15px;line-height:1.9;margin-bottom:18px;">
                            The Eventurers is the team behind Eventure—a group of dedicated developers and innovators committed to improving event management through technology. Inspired by the challenges commonly faced by event organizers, the team set out to create a solution that simplifies event operations while enhancing the overall experience for participants.
                        </p>
                        <p style="color:rgba(255,255,255,0.82);font-size:15px;line-height:1.9;">
                            Combining technical expertise, creativity, and collaboration, The Eventurers strive to develop practical and impactful digital solutions. Through Eventure, the team aims to support organizers in delivering well-managed, accessible, and memorable events while continuously exploring new ways to innovate and improve the event management landscape.
                        </p>
                    </div>
                    <div style="display:flex;align-items:flex-start;">
                        <div style="background:rgba(27,108,168,0.1);border:1px solid rgba(27,108,168,0.25);border-radius:14px;padding:28px 32px;width:100%;">
                            <p style="color:#5BA4CF;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:16px;">The Eventurers</p>
                            <ul style="list-style:none;padding:0;margin:0 0 24px 0;">
                                <li style="color:#ffffff;font-size:15px;padding:10px 0;border-bottom:1px solid rgba(27,108,168,0.2);display:flex;align-items:center;gap:10px;">
                                    <span style="width:6px;height:6px;border-radius:50%;background:#5BA4CF;display:inline-block;flex-shrink:0;"></span>Mika Sanchez
                                </li>
                                <li style="color:#ffffff;font-size:15px;padding:10px 0;border-bottom:1px solid rgba(27,108,168,0.2);display:flex;align-items:center;gap:10px;">
                                    <span style="width:6px;height:6px;border-radius:50%;background:#5BA4CF;display:inline-block;flex-shrink:0;"></span>Cyro Lalusis
                                </li>
                                <li style="color:#ffffff;font-size:15px;padding:10px 0;border-bottom:1px solid rgba(27,108,168,0.2);display:flex;align-items:center;gap:10px;">
                                    <span style="width:6px;height:6px;border-radius:50%;background:#5BA4CF;display:inline-block;flex-shrink:0;"></span>Joseph Layco
                                </li>
                                <li style="color:#ffffff;font-size:15px;padding:10px 0;display:flex;align-items:center;gap:10px;">
                                    <span style="width:6px;height:6px;border-radius:50%;background:#5BA4CF;display:inline-block;flex-shrink:0;"></span>Kristan Roy Uri
                                </li>
                            </ul>
                            <div style="border-top:1px solid rgba(27,108,168,0.2);padding-top:16px;">
                                <p style="color:rgba(255,255,255,0.5);font-size:12px;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:4px;">Mentor</p>
                                <p style="color:rgba(255,255,255,0.85);font-size:15px;margin:0;">Mr. Marvin Atanacio</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <footer class="py-12 px-6" style="border-top:1px solid rgba(27,108,168,0.2)">
        <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-8">
            <div>
                <a href="{{ url('/') }}" class="text-xl font-bold">
                    <span class="text-white">Event</span><span class="text-em4">ure</span>
                </a>
                <p style="color:rgba(255,255,255,0.35); font-size:13px; line-height:1.8; margin:0;">
                    Empowering Events. Connecting People.
                </p>
            </div>
            <div>
                <h4 class="font-semibold mb-3 text-sm text-em3">Platform</h4>
                <ul class="space-y-2 text-sm" style="color:rgba(255,255,255,0.5)">
                    <!-- Browse Events login removed for participants -->
                    <li><a href="#ongoing" class="hover:text-em4 transition">Ongoing</a></li>
                    <li><a href="#upcoming" class="hover:text-em4 transition">Upcoming</a></li>
                    <li><a href="#features" class="hover:text-em4 transition">Features</a></li>
                </ul>
            </div>
            <div id="need-help">
                <h4 class="font-semibold mb-3 text-sm text-em3">Need Help?</h4>
                <ul class="space-y-2 text-sm" style="color:rgba(255,255,255,0.5)">
                    <li><a href="https://heyzine.com/flip-book/5e4e7eda76.html" class="hover:text-em4 transition" target="_blank" rel="noopener noreferrer">All Users Manual</a></li>
                    <li><a href="https://heyzine.com/flip-book/3e91a63865.html" class="hover:text-em4 transition" target="_blank" rel="noopener noreferrer">Admin Manual</a></li>
                    <li><a href="https://heyzine.com/flip-book/641529e33a.html" class="hover:text-em4 transition" target="_blank" rel="noopener noreferrer">Event Staff Manual</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto mt-10 pt-6 text-center text-sm" style="border-top:1px solid rgba(27,108,168,0.16);color:rgba(255,255,255,0.35)">
            &copy; {{ date('Y') }} Eventure. All rights reserved.
        </div>
    </footer>

    <button id="scrollTopBtn" type="button" class="scroll-top-btn" aria-label="Scroll to top" title="Scroll to top">
        <svg viewBox="0 0 24 24" fill="none" class="w-5 h-5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 19V5" />
            <path d="M5 12l7-7 7 7" />
        </svg>
    </button>

    <!-- Privacy Notice Modal -->
    <div id="privacyNoticeModal" class="privacy-notice-modal-overlay" aria-hidden="true">
        <div class="privacy-notice-modal">
            <div class="privacy-notice-modal-header">
                <h2 class="privacy-notice-modal-title">Privacy Notice</h2>
            </div>
            <div class="privacy-notice-modal-content">
                <div class="privacy-notice-section">
                    <p class="privacy-notice-section-text">Eventure values your privacy and is committed to protecting your personal information. This Privacy Notice explains how we collect, use, store, and safeguard your data when you use the Eventure platform and related services. By registering and continuing to use Eventure, you acknowledge that your information will be processed responsibly and in accordance with applicable data protection laws and policies.</p>
                </div>

                <div class="privacy-notice-section">
                    <h3 class="privacy-notice-section-heading">Personal Data We Collect</h3>
                    <p class="privacy-notice-section-text">Eventure may collect the following information:</p>
                    <ul class="privacy-notice-bullet-list">
                        <li>Personal details such as name, email address, contact number, and account credentials</li>
                        <li>Event-related information including registrations, attendance records, and participation details</li>
                        <li>System and platform data such as login records, browser/device information, and activity logs used for security and service improvement</li>
                    </ul>
                </div>

                <div class="privacy-notice-section">
                    <h3 class="privacy-notice-section-heading">Why We Process Your Data</h3>
                    <p class="privacy-notice-section-text">Your information is collected and processed for legitimate purposes, including:</p>
                    <ul class="privacy-notice-bullet-list">
                        <li>Account registration and verification</li>
                        <li>Event registration, attendance tracking, and generation of certificate</li>
                        <li>Platform communication, announcements, and support services</li>
                        <li>System security, monitoring, and platform improvement</li>
                        <li>Compliance with applicable legal and regulatory requirements</li>
                    </ul>
                </div>

                <div class="privacy-notice-section">
                    <h3 class="privacy-notice-section-heading">Sharing and Disclosure</h3>
                    <p class="privacy-notice-section-text">Eventure does not sell personal information. Data may only be shared with authorized personnel, trusted service providers, or government authorities when required by law and with appropriate safeguards.</p>
                </div>

                <div class="privacy-notice-section">
                    <h3 class="privacy-notice-section-heading">Data Protection and Security</h3>
                    <p class="privacy-notice-section-text">Eventure implements reasonable technical and organizational measures to protect personal information from unauthorized access, misuse, loss, or disclosure. Access to data is limited only to authorized individuals.</p>
                </div>

                <div class="privacy-notice-section">
                    <h3 class="privacy-notice-section-heading">Retention of Data</h3>
                    <p class="privacy-notice-section-text">Personal information is retained only for as long as necessary to fulfill the purposes stated in this notice, comply with legal obligations, and maintain platform operations.</p>
                </div>

                <div class="privacy-notice-section">
                    <h3 class="privacy-notice-section-heading">Your Rights</h3>
                    <p class="privacy-notice-section-text">Users may request access, correction, or removal of their personal information subject to applicable laws and platform policies.</p>
                </div>

                <div class="privacy-notice-section">
                    <h3 class="privacy-notice-section-heading">Contact Us</h3>
                    <p class="privacy-notice-section-text">For questions, concerns, or privacy-related requests, you may contact Eventure at: <strong>events.inf233@gmail.com</strong></p>
                </div>
            </div>
            <div class="privacy-notice-modal-footer">
                <div class="privacy-notice-checkbox-container">
                    <input type="checkbox" id="privacyCheckbox" class="privacy-notice-checkbox" />
                    <label for="privacyCheckbox" class="privacy-notice-checkbox-label">I have read and agree to the Data Privacy Policy</label>
                </div>
                <div class="privacy-notice-buttons">
                    <button type="button" id="privacyCancelBtn" class="privacy-notice-btn privacy-notice-btn-cancel">Cancel</button>
                    <button type="button" id="acceptPrivacyBtn" class="privacy-notice-btn privacy-notice-btn-accept" disabled>I Accept</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Landing Registration Modal -->
    <div id="landingRegistrationModal" class="landing-registration-modal-overlay" aria-hidden="true">
        <div class="landing-registration-modal">
            <div class="landing-registration-header">
                <div>
                    <h2 class="landing-registration-title">Register for this event</h2>
                    <p class="text-sm text-slate-500 mt-2">Choose how you would like to register and complete the correct form.</p>
                </div>
                <button type="button" id="landingRegistrationClose" class="landing-registration-close" aria-label="Close registration">×</button>
            </div>
            <div class="landing-registration-body grid gap-4 items-start lg:grid-cols-[1.35fr_0.85fr]">
                <div class="landing-registration-panel overflow-y-auto max-h-[calc(100vh-200px)] space-y-3">
                    <div id="landingRegistrationTypeSelection" class="space-y-4">
                        <p class="text-sm text-slate-500">Register as:</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <button type="button" class="landing-registration-option rounded-2xl border border-slate-200 p-4 text-left transition-all duration-200 ease-in-out hover:border-em4 hover:bg-slate-50" data-registration-type="participant">
                                <div class="text-sm font-semibold text-slate-900">Participant</div>
                                <p class="text-sm text-slate-600 mt-2">Register as a participant and request approval for a digital ID.</p>
                            </button>
                            <button type="button" class="landing-registration-option rounded-2xl border border-slate-200 p-4 text-left transition-all duration-200 ease-in-out hover:border-em4 hover:bg-slate-50" data-registration-type="guest">
                                <div class="text-sm font-semibold text-slate-900">Guest</div>
                                <p class="text-sm text-slate-600 mt-2" id="landingGuestOptionDescription">Register as a guest speaker or exhibitor for the event.</p>
                            </button>
                        </div>
                    </div>

                    <div id="landingRegistrationFormPanel" class="hidden space-y-3">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-sm text-slate-500">You are registering as</p>
                                    <h3 id="landingFormTypeLabel" class="text-lg font-semibold text-slate-900">Participant</h3>
                                </div>
                                <button type="button" class="text-sm text-em4 hover:underline" id="landingRegistrationBack">Change</button>
                            </div>
                        </div>

                        <form id="landingRegistrationForm" class="landing-registration-form space-y-3" method="POST" action="{{ route('public.participant.store') }}">
                            @csrf
                            <input type="hidden" name="event_id" id="landingEventId" value="">
                            <input type="hidden" name="registration_type" id="landingRegistrationType" value="participant">

                            <div class="landing-registration-field">
                                <label for="landingName" class="text-sm">Name</label>
                                <input id="landingName" name="name" type="text" class="text-sm" placeholder="Full name" required>
                            </div>

                            <div class="landing-registration-field hidden" id="participantTypeField">
                                <label for="landingParticipantType" class="text-sm">Participant Type</label>
                                <select id="landingParticipantType" name="participant_type" class="text-sm" required>
                                    <option value="">Select type</option>
                                    <option value="faculty">Faculty</option>
                                    <option value="student">Student</option>
                                </select>
                            </div>

                            <div class="landing-registration-field">
                                <label for="landingEmail" class="text-sm">Email</label>
                                <input id="landingEmail" name="email" type="email" class="text-sm" placeholder="Email address" required>
                            </div>

                            <div class="landing-registration-field hidden" id="landingInstitutionField">
                                <label for="landingInstitution" class="text-sm">School / University</label>
                                <input id="landingInstitution" name="institution" type="text" class="text-sm" placeholder="e.g. NU Lipa" required>
                            </div>

                            <div class="landing-registration-field hidden" id="landingCollegeField">
                                <label for="landingCollege" class="text-sm">College / Department</label>
                                <select id="landingCollege" name="college" class="text-sm">
                                    <option value="">Select college/department</option>
                                    <option value="SACE">SACE</option>
                                    <option value="SABM">SABM</option>
                                    <option value="SAHS">SAHS</option>
                                    <option value="SHS">SHS</option>
                                    <option value="N/A">N/A</option>
                                </select>
                            </div>


                            <div class="landing-registration-actions flex flex-row justify-end items-center gap-2 mt-2">
                                <button type="button" class="inline-flex justify-center rounded-lg border border-slate-300 bg-white" id="landingRegistrationCancel" style="min-width:90px;padding:8px 16px;font-size:0.75rem;font-weight:600;color:#0f172a;transition: background 180ms ease, border-color 180ms ease;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#fff'">Cancel</button>
                                <button type="submit" id="landingRegistrationSubmit" style="min-width:120px;padding:8px 16px;font-size:0.75rem;font-weight:600;background:#1B6CA8;color:#fff;border:none;border-radius:8px;cursor:pointer;transition: filter 180ms ease;" onmouseover="this.style.filter='brightness(1.08)'" onmouseout="this.style.filter='brightness(1)'">Register</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="landing-registration-info self-start flex flex-col gap-2">
                    <div class="space-y-1">
                        <p class="landing-registration-section-title">Event Summary</p>
                        <h3 class="text-lg font-semibold text-slate-900" id="landingEventTitle">Event title</h3>
                        <p class="text-sm text-slate-600" id="landingEventDate">Date range</p>
                        <p class="text-sm text-slate-600" id="landingEventLocation">Location</p>
                    </div>
                    <div>
                        <p class="landing-registration-section-title">Registration</p>
                        <span class="landing-registration-pill" id="landingRegistrationStatus">Open</span>
                    </div>
                    <div class="landing-registration-footer">
                        <p>Once your registration is submitted, you will receive confirmation here. Registrations are reviewed and approved by admin or event staff before a digital ID is issued.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="landingToastContainer" style="position:fixed; top:20px; right:20px; z-index:10050; display:grid; gap:12px;"></div>
    <script>
        (function () {
            const modal = document.getElementById('landingRegistrationModal');
            const closeBtn = document.getElementById('landingRegistrationClose');
            const cancelBtn = document.getElementById('landingRegistrationCancel');
            const openButtons = document.querySelectorAll('.open-registration-modal');
            const registrationTypeInput = document.getElementById('landingRegistrationType');
            const registrationTypeOptions = document.querySelectorAll('.landing-registration-option');
            const participantTypeField = document.getElementById('participantTypeField');
            const landingInstitutionField = document.getElementById('landingInstitutionField');
            const landingCollegeField = document.getElementById('landingCollegeField');
            let guestRoleField = null;
            let guestBioField = null;
            let guestPaperField = null;
            let landingGuestRole = null;
            const eventTitle = document.getElementById('landingEventTitle');
            const eventDate = document.getElementById('landingEventDate');
            const eventLocation = document.getElementById('landingEventLocation');
            const registrationStatus = document.getElementById('landingRegistrationStatus');
            const landingEventId = document.getElementById('landingEventId');
            const landingRegistrationForm = document.getElementById('landingRegistrationForm');
            const landingRegistrationSubmit = document.getElementById('landingRegistrationSubmit');
            const toastContainer = document.getElementById('landingToastContainer');
            const landingFormTypeLabel = document.getElementById('landingFormTypeLabel');
            const landingGuestOptionDescription = document.getElementById('landingGuestOptionDescription');
            const landingRegistrationTypeSelection = document.getElementById('landingRegistrationTypeSelection');
            const landingRegistrationFormPanel = document.getElementById('landingRegistrationFormPanel');
            const landingRegistrationBack = document.getElementById('landingRegistrationBack');
            const participantPublicUrl = '{{ route('public.participant.store') }}';
            const guestPublicUrl = '{{ route('public.guest.store') }}';
            let currentEventType = 'standard';

            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = 'modal-floating-label modal-floating-' + type + ' modal-toast';
                toast.textContent = message;
                toastContainer.appendChild(toast);
                setTimeout(() => toast.classList.add('is-visible'), 10);
                setTimeout(() => {
                    toast.classList.remove('is-visible');
                    toast.classList.add('is-hiding');
                    setTimeout(() => { if (toast.parentNode) toast.remove(); }, 250);
                }, 4200);
            }

            function resetForm() {
                landingRegistrationForm.reset();
                registrationTypeInput.value = 'participant';
                landingRegistrationForm.action = participantPublicUrl;
                landingRegistrationSubmit.textContent = 'Register';
                landingFormTypeLabel.textContent = 'Participant';
                if (guestRoleField && guestRoleField.parentNode) {
                    guestRoleField.remove();
                    guestRoleField = null;
                }
                if (guestBioField && guestBioField.parentNode) {
                    guestBioField.remove();
                    guestBioField = null;
                }
                if (guestPaperField && guestPaperField.parentNode) {
                    guestPaperField.remove();
                    guestPaperField = null;
                }
                landingGuestRole = null;
                participantTypeField.classList.remove('hidden');
                landingInstitutionField.classList.remove('hidden');
                landingCollegeField.classList.remove('hidden');
                document.getElementById('landingParticipantType').required = true;
                document.getElementById('landingInstitution').required = true;
                landingRegistrationTypeSelection.classList.remove('hidden');
                landingRegistrationFormPanel.classList.add('hidden');
                landingRegistrationBack.classList.add('hidden');
                registrationTypeOptions.forEach((option) => option.classList.remove('border-em4', 'bg-slate-50'));
            }

            function updateEventDetails(title, dateText, locationText, statusText) {
                eventTitle.textContent = title;
                eventDate.textContent = dateText;
                eventLocation.textContent = locationText;
                registrationStatus.textContent = statusText;
            }

            function createGuestFields() {
                if (!guestRoleField) {
                    guestRoleField = document.createElement('div');
                    guestRoleField.className = 'landing-registration-field';
                    guestRoleField.id = 'landingGuestRoleField';

                    const guestRoleLabel = document.createElement('label');
                    guestRoleLabel.setAttribute('for', 'landingGuestRole');
                    guestRoleLabel.textContent = 'Role';

                    landingGuestRole = document.createElement('input');
                    landingGuestRole.id = 'landingGuestRole';
                    landingGuestRole.name = 'role';
                    landingGuestRole.type = 'text';
                    landingGuestRole.readOnly = true;
                    landingGuestRole.className = 'bg-slate-100 text-slate-500 border border-slate-200 rounded-lg px-3 py-1.5 text-sm cursor-not-allowed';
                    landingGuestRole.required = true;

                    guestRoleField.appendChild(guestRoleLabel);
                    guestRoleField.appendChild(landingGuestRole);
                    const emailField = document.getElementById('landingEmail').closest('.landing-registration-field');
                    document.getElementById('landingEmail').closest('.landing-registration-field').insertAdjacentElement('afterend', guestRoleField);
                }

                if (!guestBioField) {
                    guestBioField = document.createElement('div');
                    guestBioField.className = 'landing-registration-field';
                    guestBioField.id = 'landingGuestBioField';

                    const guestBioLabel = document.createElement('label');
                    guestBioLabel.setAttribute('for', 'landingGuestBio');
                    guestBioLabel.textContent = 'Bio / Notes';

                    const guestBioTextarea = document.createElement('textarea');
                    guestBioTextarea.id = 'landingGuestBio';
                    guestBioTextarea.name = 'bio';
                    guestBioTextarea.rows = 2;
                    guestBioTextarea.className = 'text-sm';
                    guestBioTextarea.placeholder = 'Short bio or notes';

                    guestBioField.appendChild(guestBioLabel);
                    guestBioField.appendChild(guestBioTextarea);
                    guestRoleField.insertAdjacentElement('afterend', guestBioField);
                    landingInstitutionField.classList.add('hidden');
                    landingInstitutionField.style.display = 'none';
                    landingCollegeField.classList.add('hidden');
                    landingCollegeField.style.display = 'none';

                    if (currentEventType === 'conference') {
                        if (!guestPaperField) {
                            guestPaperField = document.createElement('div');
                            guestPaperField.className = 'landing-registration-field';
                            guestPaperField.id = 'landingGuestPaperField';

                            const paperLabel = document.createElement('label');
                            paperLabel.setAttribute('for', 'landingGuestPaper');
                            paperLabel.textContent = 'Conference Paper / Presentation';

                            const paperSubtext = document.createElement('p');
                            paperSubtext.style.cssText = 'font-size:0.7rem;color:#64748b;margin:0 0 4px;';
                            paperSubtext.textContent = 'Upload your paper (PDF, DOC, DOCX, TXT, RTF, ODT, XLS, XLSX, PPT, PPTX — max 10MB)';

                            const paperInput = document.createElement('input');
                            paperInput.id = 'landingGuestPaper';
                            paperInput.name = 'conference_paper';
                            paperInput.type = 'file';
                            paperInput.accept = '.pdf,.doc,.docx,.txt,.rtf,.odt,.xls,.xlsx,.ppt,.pptx';

                            guestPaperField.appendChild(paperLabel);
                            guestPaperField.appendChild(paperSubtext);
                            guestPaperField.appendChild(paperInput);
                            guestBioField.insertAdjacentElement('afterend', guestPaperField);
                        }
                    }
                }
            }

            function setFormType(type, eventType = 'standard') {
                currentEventType = eventType;
                registrationTypeInput.value = type;
                landingFormTypeLabel.textContent = type === 'guest' ? 'Guest' : 'Participant';
                landingRegistrationSubmit.textContent = 'Register';
                landingRegistrationForm.action = type === 'guest' ? guestPublicUrl : participantPublicUrl;

                if (type === 'guest') {
                    createGuestFields();
                    participantTypeField.style.display = 'none';
                    landingInstitutionField.style.display = 'none';
                    participantTypeField.classList.add('hidden');
                    landingInstitutionField.classList.add('hidden');
                    landingCollegeField.classList.add('hidden');
                    guestRoleField.classList.remove('hidden');
                    guestBioField.classList.remove('hidden');
                    document.getElementById('landingParticipantType').required = false;
                    document.getElementById('landingInstitution').required = false;
                    landingRegistrationForm.enctype = 'multipart/form-data';
                    landingGuestRole.required = true;
                    landingGuestRole.value = eventType === 'conference' ? 'Presenter' : 'Exhibitor';
                    landingGuestRole.className = 'bg-slate-100 text-slate-500 border border-slate-200 rounded-lg px-3 py-1.5 text-sm cursor-not-allowed';
                    guestBioField.querySelector('textarea').rows = 2;
                    landingInstitutionField.classList.add('hidden');
                    landingCollegeField.classList.add('hidden');
                } else {
                    participantTypeField.style.display = '';
                    landingInstitutionField.style.display = '';
                    participantTypeField.classList.remove('hidden');
                    landingInstitutionField.classList.remove('hidden');
                    landingCollegeField.classList.remove('hidden');
                    landingRegistrationForm.enctype = 'application/x-www-form-urlencoded';
                    participantTypeField.style.display = '';
                    landingInstitutionField.style.display = '';
                    participantTypeField.classList.remove('hidden');
                    landingInstitutionField.classList.remove('hidden');
                    landingCollegeField.classList.remove('hidden');
                    if (guestRoleField && guestRoleField.parentNode) {
                        guestRoleField.remove();
                        guestRoleField = null;
                    }
                    if (guestBioField && guestBioField.parentNode) {
                        guestBioField.remove();
                        guestBioField = null;
                    }
                    if (guestPaperField && guestPaperField.parentNode) {
                        guestPaperField.remove();
                        guestPaperField = null;
                    }
                    landingGuestRole = null;
                    document.getElementById('landingParticipantType').required = true;
                    document.getElementById('landingInstitution').required = true;
                }
            }

            function showFormPanel() {
                landingRegistrationTypeSelection.classList.add('hidden');
                landingRegistrationFormPanel.classList.remove('hidden');
                landingRegistrationBack.classList.remove('hidden');
            }

            function openRegistrationModal(button) {
                const eventIdValue = button.dataset.eventId;
                const title = button.dataset.eventTitle || 'Event title';
                const dateText = button.dataset.eventDateRange || 'Date range';
                const locationText = button.dataset.eventLocation || 'Location';
                const statusText = button.dataset.eventStatus || 'Open';
                currentEventType = button.dataset.eventType || 'standard';

                landingEventId.value = eventIdValue;
                updateEventDetails(title, dateText, locationText, statusText);
                resetForm();
                setFormType('participant', currentEventType);
                landingGuestOptionDescription.textContent = currentEventType === 'conference'
                    ? 'Register as a Presenter for the event.'
                    : 'Register as an Exhibitor for the event.';
                modal.classList.add('is-visible');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function closeRegistrationModal() {
                modal.classList.remove('is-visible');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                resetForm();
            }

            // Privacy Notice Modal Logic
            const privacyNoticeModal = document.getElementById('privacyNoticeModal');
            const privacyCheckbox = document.getElementById('privacyCheckbox');
            const acceptPrivacyBtn = document.getElementById('acceptPrivacyBtn');
            const privacyCancelBtn = document.getElementById('privacyCancelBtn');

            function openPrivacyNoticeModal(button) {
                // Store event data on the privacy modal for later retrieval
                privacyNoticeModal.dataset.eventId = button.dataset.eventId;
                privacyNoticeModal.dataset.eventTitle = button.dataset.eventTitle;
                privacyNoticeModal.dataset.eventDateRange = button.dataset.eventDateRange;
                privacyNoticeModal.dataset.eventLocation = button.dataset.eventLocation;
                privacyNoticeModal.dataset.eventStatus = button.dataset.eventStatus;
                privacyNoticeModal.dataset.eventType = button.dataset.eventType;
                privacyNoticeModal.dataset.eventRegisterUrl = button.dataset.eventRegisterUrl;

                // Reset checkbox
                privacyCheckbox.checked = false;
                acceptPrivacyBtn.disabled = true;
                acceptPrivacyBtn.style.opacity = '0.5';
                acceptPrivacyBtn.style.cursor = 'not-allowed';

                // Open modal
                privacyNoticeModal.classList.add('is-visible');
                privacyNoticeModal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function closePrivacyNoticeModal(skipBodyOverflow = false) {
                privacyNoticeModal.classList.remove('is-visible');
                privacyNoticeModal.setAttribute('aria-hidden', 'true');
                privacyCheckbox.checked = false;
                acceptPrivacyBtn.disabled = true;
                acceptPrivacyBtn.style.opacity = '0.5';
                acceptPrivacyBtn.style.cursor = 'not-allowed';
                if (!skipBodyOverflow) {
                    document.body.style.overflow = '';
                }
            }

            privacyCheckbox.addEventListener('change', function() {
                acceptPrivacyBtn.disabled = !this.checked;
                acceptPrivacyBtn.style.opacity = this.checked ? '1' : '0.5';
                acceptPrivacyBtn.style.cursor = this.checked ? 'pointer' : 'not-allowed';
            });

            acceptPrivacyBtn.addEventListener('click', function() {
                if (!acceptPrivacyBtn.disabled) {
                    // Get event data from privacy modal
                    const eventId = privacyNoticeModal.dataset.eventId;
                    const eventTitle = privacyNoticeModal.dataset.eventTitle;
                    const eventDateRange = privacyNoticeModal.dataset.eventDateRange;
                    const eventLocation = privacyNoticeModal.dataset.eventLocation;
                    const eventStatus = privacyNoticeModal.dataset.eventStatus;
                    const eventType = privacyNoticeModal.dataset.eventType;
                    const eventRegisterUrl = privacyNoticeModal.dataset.eventRegisterUrl;

                    // Close privacy modal (keep body scroll locked)
                    closePrivacyNoticeModal(true);

                    // Open registration modal with event data
                    setTimeout(() => {
                        landingEventId.value = eventId;
                        updateEventDetails(eventTitle, eventDateRange, eventLocation, eventStatus);
                        resetForm();
                        setFormType('participant', eventType);
                        landingGuestOptionDescription.textContent = eventType === 'conference'
                            ? 'Register as a Presenter for the event.'
                            : 'Register as an Exhibitor for the event.';
                        modal.classList.add('is-visible');
                        modal.setAttribute('aria-hidden', 'false');
                    }, 100);
                }
            });

            privacyCancelBtn.addEventListener('click', closePrivacyNoticeModal);

            privacyNoticeModal.addEventListener('click', (event) => {
                if (event.target === privacyNoticeModal) {
                    closePrivacyNoticeModal();
                }
            });

            openButtons.forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    openPrivacyNoticeModal(button);
                });
            });

            registrationTypeOptions.forEach((option) => {
                option.addEventListener('click', () => {
                    const type = option.dataset.registrationType;
                    registrationTypeOptions.forEach((item) => item.classList.remove('border-em4', 'bg-slate-50'));
                    option.classList.add('border-em4', 'bg-slate-50');
                    setFormType(type, currentEventType);
                    showFormPanel();
                });
            });

            landingRegistrationBack.addEventListener('click', () => {
                landingRegistrationFormPanel.classList.add('hidden');
                landingRegistrationTypeSelection.classList.remove('hidden');
                landingRegistrationBack.classList.add('hidden');
            });

            closeBtn.addEventListener('click', closeRegistrationModal);
            cancelBtn.addEventListener('click', closeRegistrationModal);
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeRegistrationModal();
                }
            });

            landingRegistrationForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                const formData = new FormData(landingRegistrationForm);
                landingRegistrationSubmit.disabled = true;
                landingRegistrationSubmit.textContent = 'Submitting...';
                try {
                    const response = await fetch(landingRegistrationForm.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: formData,
                        credentials: 'same-origin'
                    });
                    const data = await response.json();
                    if (response.ok) {
                        showToast(data.message || 'Registration submitted successfully.', 'success');
                        landingRegistrationForm.reset();
                        setFormType(registrationTypeInput.value, currentEventType);
                        closeRegistrationModal();
                    } else {
                        const validationMessage = data.errors
                            ? Object.values(data.errors).flat()[0]
                            : null;
                        showToast(validationMessage || data.message || 'Registration failed. Please check your details.', 'error');
                    }
                } catch (error) {
                    console.error(error);
                    showToast('Network error. Please try again.', 'error');
                } finally {
                    landingRegistrationSubmit.disabled = false;
                    landingRegistrationSubmit.textContent = 'Register';
                }
            });

            resetForm();
        })();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const revealItems = document.querySelectorAll('[data-reveal]');
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const scrollTopBtn = document.getElementById('scrollTopBtn');

            if (scrollTopBtn) {
                const toggleScrollTop = () => {
                    if (window.scrollY > 320) {
                        scrollTopBtn.classList.add('is-visible');
                    } else {
                        scrollTopBtn.classList.remove('is-visible');
                    }
                };

                toggleScrollTop();
                window.addEventListener('scroll', toggleScrollTop, { passive: true });

                scrollTopBtn.addEventListener('click', () => {
                    window.scrollTo({
                        top: 0,
                        behavior: prefersReducedMotion ? 'auto' : 'smooth',
                    });
                });
            }

            if (prefersReducedMotion) {
                revealItems.forEach((el) => el.classList.add('is-visible'));
                return;
            }

            const revealObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.15,
                rootMargin: '0px 0px -40px 0px',
            });

            revealItems.forEach((el) => revealObserver.observe(el));
        });
    </script>

    <!-- Template Download Modal -->
    <div id="templateDownloadModal" class="template-modal-overlay fixed inset-0 bg-black/50 hidden items-center justify-center z-[9999]">
        <div class="template-modal-content bg-white rounded-2xl p-8 max-w-[640px] w-full mx-4 shadow-2xl">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Download Template</h2>
            <p class="text-gray-600 mb-6">Enter your guest or participant token to download the template.</p>
            
            <div class="space-y-4">
                <div>
                    <label for="guestToken" class="block text-sm font-medium text-gray-700 mb-2">
                        Enter your Token
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="guestToken" 
                            class="w-full px-4 py-2 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-em4 focus:border-transparent outline-none"
                            style="font-family: 'Sora', sans-serif"
                            placeholder="Paste your digital ID token here"
                        >
                        <button
                            type="button"
                            id="pasteTokenBtn"
                            class="paste-btn absolute right-1 top-1/2 transform -translate-y-1/2"
                            title="Paste from clipboard"
                            aria-label="Paste token from clipboard"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            </svg>
                        </button>
                    </div>
                    <p id="tokenError" class="mt-2 text-sm text-red-600 hidden"></p>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button 
                        type="button" 
                        id="templateDownloadCancel" 
                        class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition"
                    >
                        Cancel
                    </button>
                    <button 
                        type="button" 
                        id="templateDownloadBtn" 
                        class="flex-1 px-4 py-2.5 bg-em4 text-white rounded-lg font-medium hover:brightness-110 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Download
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Meet Link Token Verification Modal -->
    <div id="meetLinkModal" class="template-modal-overlay fixed inset-0 bg-black/50 hidden items-center justify-center z-[9999]">
        <div class="template-modal-content bg-white rounded-2xl p-8 max-w-[640px] w-full mx-4 shadow-2xl">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Join Meet Link</h2>
            <p class="text-gray-600 mb-6">Are you a registered guest?</p>
            
            <div class="space-y-4">
                <div>
                    <label for="meetLinkToken" class="block text-sm font-medium text-gray-700 mb-2">
                        Enter your Guest Token
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="meetLinkToken" 
                            class="w-full px-4 py-2 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-em4 focus:border-transparent outline-none"
                            style="font-family: 'Sora', sans-serif"
                            placeholder="Paste your digital ID token here"
                        >
                        <button
                            type="button"
                            id="pasteMeetLinkTokenBtn"
                            class="paste-btn absolute right-1 top-1/2 transform -translate-y-1/2"
                            title="Paste from clipboard"
                            aria-label="Paste token from clipboard"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            </svg>
                        </button>
                    </div>
                    <p id="meetLinkError" class="mt-2 text-sm text-red-600 hidden"></p>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button 
                        type="button" 
                        id="meetLinkCancel" 
                        class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition"
                    >
                        Cancel
                    </button>
                    <button 
                        type="button" 
                        id="meetLinkAccessBtn" 
                        class="flex-1 px-4 py-2.5 bg-em4 text-white rounded-lg font-medium hover:brightness-110 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Join
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Resource Link Modal -->
    <div id="resourceLinkModal" class="template-modal-overlay fixed inset-0 bg-black/50 hidden items-center justify-center z-[9999]">
        <div class="template-modal-content bg-white rounded-2xl p-8 max-w-[640px] w-full mx-4 shadow-2xl">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Access Resource Link</h2>
            <p class="text-gray-600 mb-6">Enter your guest or participant token to access resources.</p>
            
            <div class="space-y-4">
                <div>
                    <label for="resourceLinkToken" class="block text-sm font-medium text-gray-700 mb-2">
                        Enter your Token
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="resourceLinkToken" 
                            class="w-full px-4 py-2 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-em4 focus:border-transparent outline-none"
                            style="font-family: 'Sora', sans-serif"
                            placeholder="Paste your digital ID token here"
                        >
                        <button
                            type="button"
                            id="resourceLinkPasteBtn"
                            class="paste-btn absolute right-1 top-1/2 transform -translate-y-1/2"
                            title="Paste from clipboard"
                            aria-label="Paste token from clipboard"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            </svg>
                        </button>
                    </div>
                    <p id="resourceLinkError" class="mt-2 text-sm text-red-600 hidden"></p>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button 
                        type="button" 
                        id="resourceLinkCancel" 
                        class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition"
                    >
                        Cancel
                    </button>
                    <button 
                        type="button" 
                        id="resourceLinkAccessBtn" 
                        class="flex-1 px-4 py-2.5 bg-em4 text-white rounded-lg font-medium hover:brightness-110 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Access
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Program Download Modal -->
    <div id="programDownloadModal" class="template-modal-overlay fixed inset-0 bg-black/50 hidden items-center justify-center z-[9999]">
        <div class="template-modal-content bg-white rounded-2xl p-8 max-w-[640px] w-full mx-4 shadow-2xl">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Download Program</h2>
            <p class="text-gray-600 mb-6">Enter your guest or participant token to download the program.</p>
            
            <div class="space-y-4">
                <div>
                    <label for="programDownloadToken" class="block text-sm font-medium text-gray-700 mb-2">
                        Enter your Token
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="programDownloadToken" 
                            class="w-full px-4 py-2 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-em4 focus:border-transparent outline-none"
                            style="font-family: 'Sora', sans-serif"
                            placeholder="Paste your digital ID token here"
                        >
                        <button
                            type="button"
                            id="programPasteBtn"
                            class="paste-btn absolute right-1 top-1/2 transform -translate-y-1/2"
                            title="Paste from clipboard"
                            aria-label="Paste token from clipboard"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            </svg>
                        </button>
                    </div>
                    <p id="programDownloadError" class="mt-2 text-sm text-red-600 hidden"></p>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button 
                        type="button" 
                        id="programDownloadCancel" 
                        class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition"
                    >
                        Cancel
                    </button>
                    <button 
                        type="button" 
                        id="programDownloadBtn" 
                        class="flex-1 px-4 py-2.5 bg-em4 text-white rounded-lg font-medium hover:brightness-110 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Download
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const templateModal = document.getElementById('templateDownloadModal');
        const guestTokenInput = document.getElementById('guestToken');
        const templateDownloadBtn = document.getElementById('templateDownloadBtn');
        const templateDownloadCancel = document.getElementById('templateDownloadCancel');
        const tokenError = document.getElementById('tokenError');
        const pasteTokenBtn = document.getElementById('pasteTokenBtn');
        const scrollTopBtn = document.getElementById('scrollTopBtn');
        let currentEventId = null;

        function openModal() {
            currentEventId = event?.target?.dataset?.eventId;
            guestTokenInput.value = '';
            tokenError.classList.add('hidden');
            tokenError.textContent = '';
            
            // Show modal
            templateModal.classList.remove('hidden');
            templateModal.style.display = 'flex';
            
            // Trigger animation
            setTimeout(() => {
                templateModal.classList.add('show');
            }, 10);
            
            // Hide body scrollbar
            document.body.style.overflow = 'hidden';
            
            // Hide scroll-to-top button pointer events
            if (scrollTopBtn) {
                scrollTopBtn.style.pointerEvents = 'none';
            }
            
            guestTokenInput.focus();
        }

        function closeModal() {
            // Fade out animation
            templateModal.classList.remove('show');
            
            setTimeout(() => {
                templateModal.classList.add('hidden');
                templateModal.style.display = 'none';
                
                // Restore body scrollbar
                document.body.style.overflow = '';
                
                // Restore scroll-to-top button pointer events
                if (scrollTopBtn && scrollTopBtn.classList.contains('is-visible')) {
                    scrollTopBtn.style.pointerEvents = 'auto';
                }
            }, 300);
        }

        // Open modal on button click
        document.querySelectorAll('.template-download-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                currentEventId = btn.dataset.eventId;
                guestTokenInput.value = '';
                tokenError.classList.add('hidden');
                tokenError.textContent = '';
                
                // Show modal
                templateModal.classList.remove('hidden');
                templateModal.style.display = 'flex';
                
                // Trigger animation
                setTimeout(() => {
                    templateModal.classList.add('show');
                }, 10);
                
                // Hide body scrollbar
                document.body.style.overflow = 'hidden';
                
                // Hide scroll-to-top button pointer events
                if (scrollTopBtn) {
                    scrollTopBtn.style.pointerEvents = 'none';
                }
                
                guestTokenInput.focus();
            });
        });

        // Close modal on cancel
        templateDownloadCancel.addEventListener('click', () => {
            closeModal();
        });

        // Close modal on background click
        templateModal.addEventListener('click', (e) => {
            if (e.target === templateModal) {
                closeModal();
            }
        });

        // Paste button functionality
        pasteTokenBtn.addEventListener('click', async () => {
            try {
                const text = await navigator.clipboard.readText();
                guestTokenInput.value = text.trim();
                tokenError.classList.add('hidden');
                tokenError.textContent = '';
                guestTokenInput.focus();
                
                // Show success feedback
                const originalColor = pasteTokenBtn.style.color;
                pasteTokenBtn.classList.add('success');
                
                setTimeout(() => {
                    pasteTokenBtn.classList.remove('success');
                }, 1500);
            } catch (err) {
                // Clipboard permission denied or no content
                console.debug('Clipboard paste not available:', err);
            }
        });

        // Extract filename from Content-Disposition header
        function getFilenameFromContentDisposition(contentDisposition) {
            if (!contentDisposition) return null;
            
            // Try quoted filename first
            let match = contentDisposition.match(/filename="([^"]+)"/);
            if (match) return match[1];
            
            // Try unquoted filename
            match = contentDisposition.match(/filename=([^;\s]+)/);
            if (match) return match[1];
            
            // Try filename* (RFC 5987)
            match = contentDisposition.match(/filename\*=(?:UTF-8'')?([^;\s]+)/);
            if (match) return decodeURIComponent(match[1]);
            
            return null;
        }

        // Handle download
        templateDownloadBtn.addEventListener('click', async () => {
            const token = guestTokenInput.value.trim();
            
            if (!token) {
                tokenError.textContent = 'Token is required. Please enter your guest token.';
                tokenError.classList.remove('hidden');
                return;
            }

            templateDownloadBtn.disabled = true;
            templateDownloadBtn.textContent = 'Downloading...';

            try {
                const response = await fetch(`/events/${currentEventId}/template/download/verify`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({ token })
                });

                if (response.ok) {
                    try {
                        // File download
                        const blob = await response.blob();
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        
                        // Extract filename from Content-Disposition header
                        const contentDisposition = response.headers.get('Content-Disposition');
                        const filename = getFilenameFromContentDisposition(contentDisposition) || 'template';
                        a.download = filename;
                        
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        a.remove();
                        
                        // Close modal
                        closeModal();
                    } catch (blobError) {
                        console.error('Blob processing error:', blobError);
                        tokenError.textContent = 'Failed to process file. Please try again.';
                        tokenError.classList.remove('hidden');
                    }
                } else {
                    try {
                        const errorData = await response.json();
                        tokenError.textContent = errorData.error || 'An error occurred. Please try again.';
                    } catch {
                        tokenError.textContent = 'Server error: ' + response.status + '. Please try again.';
                    }
                    tokenError.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Fetch error:', error);
                tokenError.textContent = 'Network error. Please check your connection and try again.';
                tokenError.classList.remove('hidden');
            } finally {
                templateDownloadBtn.disabled = false;
                templateDownloadBtn.textContent = 'Download';
            }
        });

        // Allow Enter key to download
        guestTokenInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !templateDownloadBtn.disabled) {
                templateDownloadBtn.click();
            }
        });

        // ========== MEET LINK MODAL HANDLERS ==========
        const meetLinkModal = document.getElementById('meetLinkModal');
        const meetLinkTokenInput = document.getElementById('meetLinkToken');
        const meetLinkAccessBtn = document.getElementById('meetLinkAccessBtn');
        const meetLinkCancel = document.getElementById('meetLinkCancel');
        const meetLinkError = document.getElementById('meetLinkError');
        const pasteMeetLinkTokenBtn = document.getElementById('pasteMeetLinkTokenBtn');

        // Only initialize if all elements exist
        if (meetLinkModal && meetLinkTokenInput && meetLinkAccessBtn && meetLinkCancel && meetLinkError && pasteMeetLinkTokenBtn) {
            let currentMeetLinkEventId = null;

            function closeMeetLinkModal() {
                // Fade out animation
                meetLinkModal.classList.remove('show');
                
                setTimeout(() => {
                    meetLinkModal.classList.add('hidden');
                    meetLinkModal.style.display = 'none';
                    
                    // Restore body scrollbar
                    document.body.style.overflow = '';
                    
                    // Restore scroll-to-top button pointer events
                    if (scrollTopBtn && scrollTopBtn.classList.contains('is-visible')) {
                        scrollTopBtn.style.pointerEvents = 'auto';
                    }
                }, 300);
            }



            // Open modal on button click
            document.querySelectorAll('.meet-link-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    currentMeetLinkEventId = btn.dataset.eventId;
                    meetLinkTokenInput.value = '';
                    meetLinkError.classList.add('hidden');
                    meetLinkError.textContent = '';
                    
                    // Show modal
                    meetLinkModal.classList.remove('hidden');
                    meetLinkModal.style.display = 'flex';
                    
                    // Trigger animation
                    setTimeout(() => {
                        meetLinkModal.classList.add('show');
                    }, 10);
                    
                    // Hide body scrollbar
                    document.body.style.overflow = 'hidden';
                    
                    // Hide scroll-to-top button pointer events
                    if (scrollTopBtn) {
                        scrollTopBtn.style.pointerEvents = 'none';
                    }
                    
                    meetLinkTokenInput.focus();
                });
            });

            // Close token modal on cancel
            meetLinkCancel.addEventListener('click', () => {
                closeMeetLinkModal();
            });

            // Close modals on background click
            meetLinkModal.addEventListener('click', (e) => {
                if (e.target === meetLinkModal) {
                    closeMeetLinkModal();
                }
            });

            // Paste button functionality for meet link
            pasteMeetLinkTokenBtn.addEventListener('click', async () => {
                try {
                    const text = await navigator.clipboard.readText();
                    meetLinkTokenInput.value = text.trim();
                    meetLinkError.classList.add('hidden');
                    meetLinkError.textContent = '';
                    meetLinkTokenInput.focus();
                    
                    // Show success feedback
                    const originalColor = pasteMeetLinkTokenBtn.style.color;
                    pasteMeetLinkTokenBtn.classList.add('success');
                    
                    setTimeout(() => {
                        pasteMeetLinkTokenBtn.classList.remove('success');
                    }, 1500);
                } catch (err) {
                    // Clipboard permission denied or no content
                    console.debug('Clipboard paste not available:', err);
                }
            });

            // Handle meet link access (token verification)
            meetLinkAccessBtn.addEventListener('click', async () => {
                const token = meetLinkTokenInput.value.trim();
                
                if (!token) {
                    meetLinkError.textContent = 'Token is required. Please enter your guest token.';
                    meetLinkError.classList.remove('hidden');
                    return;
                }

                meetLinkAccessBtn.disabled = true;
                meetLinkAccessBtn.textContent = 'Verifying...';

                try {
                    const response = await fetch(`/events/${currentMeetLinkEventId}/meet-link/verify`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        },
                        body: JSON.stringify({ token })
                    });

                    const data = await response.json();

                    if (data.success && data.meet_link) {
                        // Close modal and open meet link
                        closeMeetLinkModal();
                        window.open(data.meet_link, '_blank');
                    } else {
                        meetLinkError.textContent = data.message || 'An error occurred. Please try again.';
                        meetLinkError.classList.remove('hidden');
                    }
                } catch (error) {
                    console.error('Meet link access error:', error);
                    meetLinkError.textContent = 'Network error. Please try again.';
                    meetLinkError.classList.remove('hidden');
                } finally {
                    meetLinkAccessBtn.disabled = false;
                    meetLinkAccessBtn.textContent = 'Access';
                }
            });

            // Allow Enter key to access meet link
            meetLinkTokenInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !meetLinkAccessBtn.disabled) {
                    meetLinkAccessBtn.click();
                }
            });
        }

        // ========== PROGRAM DOWNLOAD MODAL HANDLERS ==========
        const programDownloadModal = document.getElementById('programDownloadModal');
        const programDownloadTokenInput = document.getElementById('programDownloadToken');
        const programDownloadBtn = document.getElementById('programDownloadBtn');
        const programDownloadCancel = document.getElementById('programDownloadCancel');
        const programDownloadError = document.getElementById('programDownloadError');
        const programPasteBtn = document.getElementById('programPasteBtn');
        let currentProgramEventId = null;

        function openProgramModal() {
            currentProgramEventId = event?.target?.dataset?.eventId;
            programDownloadTokenInput.value = '';
            programDownloadError.classList.add('hidden');
            programDownloadError.textContent = '';
            
            // Show modal
            programDownloadModal.classList.remove('hidden');
            programDownloadModal.style.display = 'flex';
            
            // Trigger animation
            setTimeout(() => {
                programDownloadModal.classList.add('show');
            }, 10);
            
            // Hide body scrollbar
            document.body.style.overflow = 'hidden';
            
            // Hide scroll-to-top button pointer events
            if (scrollTopBtn) {
                scrollTopBtn.style.pointerEvents = 'none';
            }
            
            programDownloadTokenInput.focus();
        }

        function closeProgramModal() {
            // Fade out animation
            programDownloadModal.classList.remove('show');
            
            setTimeout(() => {
                programDownloadModal.classList.add('hidden');
                programDownloadModal.style.display = 'none';
                
                // Restore body scrollbar
                document.body.style.overflow = '';
                
                // Restore scroll-to-top button pointer events
                if (scrollTopBtn && scrollTopBtn.classList.contains('is-visible')) {
                    scrollTopBtn.style.pointerEvents = 'auto';
                }
            }, 300);
        }

        // Open modal on button click
        document.querySelectorAll('.program-download-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                currentProgramEventId = btn.dataset.eventId;
                programDownloadTokenInput.value = '';
                programDownloadError.classList.add('hidden');
                programDownloadError.textContent = '';
                
                // Show modal
                programDownloadModal.classList.remove('hidden');
                programDownloadModal.style.display = 'flex';
                
                // Trigger animation
                setTimeout(() => {
                    programDownloadModal.classList.add('show');
                }, 10);
                
                // Hide body scrollbar
                document.body.style.overflow = 'hidden';
                
                // Hide scroll-to-top button pointer events
                if (scrollTopBtn) {
                    scrollTopBtn.style.pointerEvents = 'none';
                }
                
                programDownloadTokenInput.focus();
            });
        });

        // Close modal on cancel
        programDownloadCancel.addEventListener('click', () => {
            closeProgramModal();
        });

        // Close modal on background click
        programDownloadModal.addEventListener('click', (e) => {
            if (e.target === programDownloadModal) {
                closeProgramModal();
            }
        });

        // Paste button functionality for program
        programPasteBtn.addEventListener('click', async () => {
            try {
                const text = await navigator.clipboard.readText();
                programDownloadTokenInput.value = text.trim();
                programDownloadError.classList.add('hidden');
                programDownloadError.textContent = '';
                programDownloadTokenInput.focus();
                
                // Show success feedback
                programPasteBtn.classList.add('success');
                
                setTimeout(() => {
                    programPasteBtn.classList.remove('success');
                }, 1500);
            } catch (err) {
                // Clipboard permission denied or no content
                console.debug('Clipboard paste not available:', err);
            }
        });

        // Handle program download
        programDownloadBtn.addEventListener('click', async () => {
            const token = programDownloadTokenInput.value.trim();
            
            if (!token) {
                programDownloadError.textContent = 'Token is required. Please enter your token.';
                programDownloadError.classList.remove('hidden');
                return;
            }

            programDownloadBtn.disabled = true;
            programDownloadBtn.textContent = 'Downloading...';

            try {
                const response = await fetch(`/events/${currentProgramEventId}/program/download/verify`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({ token })
                });

                if (response.ok) {
                    try {
                        // File download
                        const blob = await response.blob();
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        
                        // Extract filename from Content-Disposition header
                        const contentDisposition = response.headers.get('Content-Disposition');
                        const filename = getFilenameFromContentDisposition(contentDisposition) || 'program';
                        a.download = filename;
                        
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        a.remove();
                        
                        // Close modal
                        closeProgramModal();
                    } catch (blobError) {
                        console.error('Blob processing error:', blobError);
                        programDownloadError.textContent = 'Failed to process file. Please try again.';
                        programDownloadError.classList.remove('hidden');
                    }
                } else {
                    try {
                        const errorData = await response.json();
                        programDownloadError.textContent = errorData.error || 'An error occurred. Please try again.';
                    } catch {
                        programDownloadError.textContent = 'Server error: ' + response.status + '. Please try again.';
                    }
                    programDownloadError.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Fetch error:', error);
                programDownloadError.textContent = 'Network error. Please check your connection and try again.';
                programDownloadError.classList.remove('hidden');
            } finally {
                programDownloadBtn.disabled = false;
                programDownloadBtn.textContent = 'Download';
            }
        });

        // Allow Enter key to download program
        programDownloadTokenInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !programDownloadBtn.disabled) {
                programDownloadBtn.click();
            }
        });

        // ========== RESOURCE LINK MODAL HANDLERS ==========
        const resourceLinkModal = document.getElementById('resourceLinkModal');
        const resourceLinkTokenInput = document.getElementById('resourceLinkToken');
        const resourceLinkAccessBtn = document.getElementById('resourceLinkAccessBtn');
        const resourceLinkCancel = document.getElementById('resourceLinkCancel');
        const resourceLinkError = document.getElementById('resourceLinkError');
        const resourceLinkPasteBtn = document.getElementById('resourceLinkPasteBtn');
        let currentResourceLinkEventId = null;

        function openResourceLinkModal() {
            currentResourceLinkEventId = event?.target?.dataset?.eventId;
            resourceLinkTokenInput.value = '';
            resourceLinkError.classList.add('hidden');
            resourceLinkError.textContent = '';
            
            // Show modal
            resourceLinkModal.classList.remove('hidden');
            resourceLinkModal.style.display = 'flex';
            
            // Trigger animation
            setTimeout(() => {
                resourceLinkModal.classList.add('show');
            }, 10);
            
            // Hide body scrollbar
            document.body.style.overflow = 'hidden';
            
            // Hide scroll-to-top button pointer events
            if (scrollTopBtn) {
                scrollTopBtn.style.pointerEvents = 'none';
            }
            
            resourceLinkTokenInput.focus();
        }

        function closeResourceLinkModal() {
            // Fade out animation
            resourceLinkModal.classList.remove('show');
            
            setTimeout(() => {
                resourceLinkModal.classList.add('hidden');
                resourceLinkModal.style.display = 'none';
                
                // Restore body scrollbar
                document.body.style.overflow = '';
                
                // Restore scroll-to-top button pointer events
                if (scrollTopBtn && scrollTopBtn.classList.contains('is-visible')) {
                    scrollTopBtn.style.pointerEvents = 'auto';
                }
            }, 300);
        }

        // Open modal on button click
        document.querySelectorAll('.resource-link-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                currentResourceLinkEventId = btn.dataset.eventId;
                resourceLinkTokenInput.value = '';
                resourceLinkError.classList.add('hidden');
                resourceLinkError.textContent = '';
                
                // Show modal
                resourceLinkModal.classList.remove('hidden');
                resourceLinkModal.style.display = 'flex';
                
                // Trigger animation
                setTimeout(() => {
                    resourceLinkModal.classList.add('show');
                }, 10);
                
                // Hide body scrollbar
                document.body.style.overflow = 'hidden';
                
                // Hide scroll-to-top button pointer events
                if (scrollTopBtn) {
                    scrollTopBtn.style.pointerEvents = 'none';
                }
                
                resourceLinkTokenInput.focus();
            });
        });

        // Close modal on cancel
        resourceLinkCancel.addEventListener('click', () => {
            closeResourceLinkModal();
        });

        // Close modal on background click
        resourceLinkModal.addEventListener('click', (e) => {
            if (e.target === resourceLinkModal) {
                closeResourceLinkModal();
            }
        });

        // Paste button functionality for resource link
        resourceLinkPasteBtn.addEventListener('click', async () => {
            try {
                const text = await navigator.clipboard.readText();
                resourceLinkTokenInput.value = text.trim();
                resourceLinkError.classList.add('hidden');
                resourceLinkError.textContent = '';
                resourceLinkTokenInput.focus();
                
                // Show success feedback
                resourceLinkPasteBtn.classList.add('success');
                
                setTimeout(() => {
                    resourceLinkPasteBtn.classList.remove('success');
                }, 1500);
            } catch (err) {
                // Clipboard permission denied or no content
                console.debug('Clipboard paste not available:', err);
            }
        });

        // Handle resource link access
        resourceLinkAccessBtn.addEventListener('click', async () => {
            const token = resourceLinkTokenInput.value.trim();
            
            if (!token) {
                resourceLinkError.textContent = 'Token is required. Please enter your token.';
                resourceLinkError.classList.remove('hidden');
                return;
            }

            resourceLinkAccessBtn.disabled = true;
            resourceLinkAccessBtn.textContent = 'Accessing...';

            try {
                const response = await fetch(`/events/${currentResourceLinkEventId}/resource-link/verify`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({ token })
                });

                const data = await response.json();

                if (data.success && data.resource_link) {
                    // Close modal and open resource link
                    closeResourceLinkModal();
                    window.open(data.resource_link, '_blank');
                } else {
                    resourceLinkError.textContent = data.error || 'An error occurred. Please try again.';
                    resourceLinkError.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Resource link access error:', error);
                resourceLinkError.textContent = 'Network error. Please try again.';
                resourceLinkError.classList.remove('hidden');
            } finally {
                resourceLinkAccessBtn.disabled = false;
                resourceLinkAccessBtn.textContent = 'Access';
            }
        });

        // Allow Enter key to access resource link
        resourceLinkTokenInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !resourceLinkAccessBtn.disabled) {
                resourceLinkAccessBtn.click();
            }
        });

        // Close modal on Escape key for all modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (!programDownloadModal.classList.contains('hidden')) {
                    closeProgramModal();
                }
                if (!resourceLinkModal.classList.contains('hidden')) {
                    closeResourceLinkModal();
                }
                if (!meetLinkModal.classList.contains('hidden')) {
                    closeMeetLinkModal();
                }
                if (!templateModal.classList.contains('hidden')) {
                    closeModal();
                }
            }
        });
    </script>
</body>
</html>
