    <!-- Login button for Admin/Event Staff -->
    <nav class="fixed top-0 inset-x-0 z-50" style="background:rgba(10,35,66,0.9);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-bottom:1px solid rgba(27,108,168,0.35)">
        <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
            <a href="{{ url('/') }}" class="text-2xl font-bold tracking-tight">
                <span class="text-em4">Even</span><span class="text-white">ture</span>
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-sm sign-in-link">Sign In</a>
            </div>
        </div>
    </nav>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Eventure — Empowering Events. Connecting People.</title>
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
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-y: auto;
        }

        .landing-registration-modal-overlay.is-visible {
            display: flex;
        }

        .landing-registration-modal {
            width: min(760px, 100%);
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 30px 70px rgba(10, 35, 66, 0.18);
            overflow: hidden;
            border: 1px solid rgba(27,108,168,0.18);
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
        }

        .landing-registration-body {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 0;
            min-height: 100%;
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
            gap: 8px;
            margin-bottom: 16px;
        }

        .landing-registration-field label {
            font-size: 0.95rem;
            font-weight: 600;
            color: #0a2342;
        }

        .landing-registration-field input,
        .landing-registration-field select,
        .landing-registration-field textarea {
            width: 100%;
            min-height: 44px;
            padding: 12px 14px;
            border: 1px solid rgba(207, 224, 239, 1);
            border-radius: 14px;
            font-size: 0.96rem;
            color: #0a2342;
            background: #ffffff;
            transition: border-color 180ms ease, box-shadow 180ms ease;
        }

        .landing-registration-field textarea {
            min-height: 104px;
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

        .landing-toast {
            min-width: 280px;
            max-width: 420px;
            color: #ffffff;
            background: rgba(27,108,168,0.95);
            border-radius: 16px;
            padding: 14px 18px;
            box-shadow: 0 18px 45px rgba(10, 35, 66, 0.18);
            margin-bottom: 12px;
            opacity: 0;
            transform: translateY(-12px);
            animation: landing-toast-in 280ms ease forwards;
        }

        .landing-toast.success { background: #16a34a; }
        .landing-toast.error { background: #b91c1c; }
        .landing-toast.info { background: rgba(27,108,168,0.95); }

        @keyframes landing-toast-in {
            to { opacity: 1; transform: translateY(0); }
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
        <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
            <a href="{{ url('/') }}" class="text-2xl font-bold tracking-tight">
                <span class="text-em4">Even</span><span class="text-white">ture</span>
            </a>

            <div class="hidden md:flex items-center gap-8 text-sm font-normal" style="color:rgba(255,255,255,0.6)">
                <a href="#ongoing" class="hover:text-em4 transition">Ongoing</a>
                <a href="#upcoming" class="hover:text-em4 transition">Upcoming</a>
                <a href="#features" class="hover:text-em4 transition">Features</a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-sm sign-in-link">Sign In</a>
            </div>
        </div>
    </nav>

    {{-- ========== ANNOUNCEMENT BANNER ========== --}}
    @isset($announcements)
        @if($announcements->isNotEmpty())
            <div class="fixed top-[72px] inset-x-0 z-40 overflow-hidden" style="background:rgba(27,108,168,0.14)">
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
                                    @if($event->type === 'conference' && $event->template_file_path)
                                        <p><button type="button" class="template-download-btn inline-flex items-center gap-1 underline hover:opacity-80 transition" data-event-id="{{ $event->id }}"><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline><path d="M9 15l3 3 5-5"></path></svg></span> Conference Paper Template</button></p>
                                    @endif
                                </div>
                                @php $now = \Carbon\Carbon::now('Asia/Manila'); @endphp
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
                <p class="mb-10" style="color:rgba(255,255,255,0.6)">Secure your spot before they fill up</p>

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
                                    @if($event->type === 'conference' && $event->template_file_path)
                                        <p><button type="button" class="template-download-btn inline-flex items-center gap-1 underline hover:opacity-80 transition" data-event-id="{{ $event->id }}"><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline><path d="M9 15l3 3 5-5"></path></svg></span> Conference Paper Template</button></p>
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
                                    @if($event->type === 'conference' && $event->template_file_path)
                                        <button type="button" class="template-download-btn inline-flex items-center gap-1 underline hover:opacity-80 transition" data-event-id="{{ $event->id }}"><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline><path d="M9 15l3 3 5-5"></path></svg></span> Conference Paper Template</button>
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
    <footer class="py-12 px-6" style="border-top:1px solid rgba(27,108,168,0.2)">
        <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-8">
            <div>
                <a href="{{ url('/') }}" class="text-xl font-bold">
                    <span class="text-em4">Even</span><span class="text-white">ture</span>
                </a>
                <p class="mt-3 text-sm" style="color:rgba(255,255,255,0.5)">Empowering Events. Connecting People.</p>
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
            <div>
                <h4 class="font-semibold mb-3 text-sm text-em3">Account</h4>
                <ul class="space-y-2 text-sm" style="color:rgba(255,255,255,0.5)">
                    <!-- Sign In removed for participants -->
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-3 text-sm text-em3">Legal</h4>
                <ul class="space-y-2 text-sm" style="color:rgba(255,255,255,0.5)">
                    <li><a href="#" class="hover:text-em4 transition">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-em4 transition">Terms of Service</a></li>
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

    <!-- Landing Registration Modal -->
    <div id="landingRegistrationModal" class="landing-registration-modal-overlay" aria-hidden="true">
        <div class="landing-registration-modal">
            <div class="landing-registration-header">
                <div>
                    <h2 class="landing-registration-title">Register for this event</h2>
                    <p class="text-sm text-slate-500 mt-2">Choose whether you are registering as a participant or a guest.</p>
                </div>
                <button type="button" id="landingRegistrationClose" class="landing-registration-close" aria-label="Close registration">×</button>
            </div>
            <div class="landing-registration-body">
                <div class="landing-registration-panel">
                    <div class="landing-registration-field">
                        <label for="registrationType">Register As</label>
                        <select id="registrationType" class="landing-registration-field-control" aria-label="Register as">
                            <option value="participant">Participant</option>
                            <option value="guest">Guest</option>
                        </select>
                    </div>
                    <form id="landingRegistrationForm" class="landing-registration-form" method="POST" action="{{ url('/events/0/participants') }}">
                        @csrf
                        <input type="hidden" name="event_id" id="landingEventId" value="">
                        <input type="hidden" name="redirect_to" value="{{ url()->current() }}">

                        <div class="landing-registration-field">
                            <label for="landingName">Name</label>
                            <input id="landingName" name="name" type="text" placeholder="Full name" required>
                        </div>
                        <div class="landing-registration-field" id="participantTypeField">
                            <label for="landingParticipantType">Participant Type</label>
                            <select id="landingParticipantType" name="participant_type" required>
                                <option value="">Select type</option>
                                <option value="faculty">Faculty</option>
                                <option value="student">Student</option>
                            </select>
                        </div>
                        <div class="landing-registration-field">
                            <label for="landingEmail">Email</label>
                            <input id="landingEmail" name="email" type="email" placeholder="Email address" required>
                        </div>
                        <div class="landing-registration-field" id="landingInstitutionField">
                            <label for="landingInstitution">School / University</label>
                            <input id="landingInstitution" name="institution" type="text" placeholder="School or university" required>
                        </div>
                        <div class="landing-registration-field hidden" id="guestRoleField">
                            <label for="landingGuestRole">Guest Role</label>
                            <input id="landingGuestRole" name="role" type="text" placeholder="Presenter, Exhibitor, etc.">
                        </div>
                        <div class="landing-registration-field hidden" id="guestBioField">
                            <label for="landingGuestBio">Bio / Notes</label>
                            <textarea id="landingGuestBio" name="bio" rows="4" placeholder="Short bio or notes"></textarea>
                        </div>
                        <div class="landing-registration-field">
                            <button type="submit" class="landing-registration-submit" id="landingRegistrationSubmit">Register Participant</button>
                        </div>
                    </form>
                </div>
                <div class="landing-registration-info">
                    <div>
                        <p class="landing-registration-section-title">Event Summary</p>
                        <h3 class="text-lg font-semibold text-slate-900" id="landingEventTitle">Event title</h3>
                        <p class="text-sm text-slate-600 mt-2" id="landingEventDate">Date range</p>
                        <p class="text-sm text-slate-600" id="landingEventLocation">Location</p>
                    </div>
                    <div>
                        <p class="landing-registration-section-title">Registration</p>
                        <span class="landing-registration-pill" id="landingRegistrationStatus">Open</span>
                    </div>
                    <div class="landing-registration-footer">
                        <p>Once your registration is submitted, you will receive confirmation here. Participants receive their digital ID by email after registration; guest registrations are reviewed and approved by event staff.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="landingToastContainer" style="position:fixed; top:20px; right:20px; z-index:10000; display:grid; gap:12px;"></div>
    <script>
        (function () {
            const modal = document.getElementById('landingRegistrationModal');
            const closeBtn = document.getElementById('landingRegistrationClose');
            const openButtons = document.querySelectorAll('.open-registration-modal');
            const registrationType = document.getElementById('registrationType');
            const participantTypeField = document.getElementById('participantTypeField');
            const landingInstitutionField = document.getElementById('landingInstitutionField');
            const guestRoleField = document.getElementById('guestRoleField');
            const guestBioField = document.getElementById('guestBioField');
            const eventTitle = document.getElementById('landingEventTitle');
            const eventDate = document.getElementById('landingEventDate');
            const eventLocation = document.getElementById('landingEventLocation');
            const registrationStatus = document.getElementById('landingRegistrationStatus');
            const landingEventId = document.getElementById('landingEventId');
            const landingRegistrationForm = document.getElementById('landingRegistrationForm');
            const landingRegistrationSubmit = document.getElementById('landingRegistrationSubmit');
            const toastContainer = document.getElementById('landingToastContainer');
            const participantActionBase = '{{ url('/events/0/participants') }}';
            const guestActionUrl = '{{ route('guests.store') }}';

            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = 'landing-toast ' + type;
                toast.textContent = message;
                toastContainer.appendChild(toast);
                setTimeout(() => toast.classList.add('is-visible'), 10);
                setTimeout(() => { if (toast.parentNode) toast.remove(); }, 4200);
            }

            let currentParticipantActionUrl = participantActionBase;

            function setFormType(type, eventType = 'standard') {
                if (type === 'guest') {
                    participantTypeField.classList.add('hidden');
                    landingInstitutionField.classList.add('hidden');
                    guestRoleField.classList.remove('hidden');
                    guestBioField.classList.remove('hidden');
                    landingRegistrationSubmit.textContent = 'Register as Guest';
                    landingRegistrationForm.action = guestActionUrl;
                    landingRegistrationForm.querySelector('[name="participant_type"]')?.removeAttribute('required');
                    landingRegistrationForm.querySelector('[name="institution"]')?.removeAttribute('required');
                    landingRegistrationForm.querySelector('[name="role"]')?.setAttribute('required', 'required');
                } else {
                    participantTypeField.classList.remove('hidden');
                    landingInstitutionField.classList.remove('hidden');
                    guestRoleField.classList.add('hidden');
                    guestBioField.classList.add('hidden');
                    landingRegistrationSubmit.textContent = 'Register Participant';
                    landingRegistrationForm.action = currentParticipantActionUrl;
                    landingRegistrationForm.querySelector('[name="participant_type"]')?.setAttribute('required', 'required');
                    landingRegistrationForm.querySelector('[name="institution"]')?.setAttribute('required', 'required');
                    landingRegistrationForm.querySelector('[name="role"]')?.removeAttribute('required');
                }
            }

            function openRegistrationModal(button) {
                const eventId = button.dataset.eventId;
                landingEventId.value = eventId;
                currentParticipantActionUrl = button.dataset.eventRegisterUrl || participantActionBase.replace('/0/', '/' + eventId + '/');
                eventTitle.textContent = button.dataset.eventTitle || 'Event title';
                eventDate.textContent = button.dataset.eventDateRange || 'Date range';
                eventLocation.textContent = button.dataset.eventLocation || 'Location';
                registrationStatus.textContent = button.dataset.eventStatus || 'Open';
                registrationType.value = 'participant';
                setFormType('participant', button.dataset.eventType || 'standard');
                modal.classList.add('is-visible');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function closeRegistrationModal() {
                modal.classList.remove('is-visible');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            openButtons.forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    openRegistrationModal(button);
                });
            });

            closeBtn.addEventListener('click', closeRegistrationModal);
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeRegistrationModal();
                }
            });

            registrationType.addEventListener('change', () => {
                setFormType(registrationType.value, document.querySelector('.open-registration-modal[data-event-id="' + landingEventId.value + '"]')?.dataset?.eventType || 'standard');
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
                        setFormType(registrationType.value, document.querySelector('.open-registration-modal[data-event-id="' + landingEventId.value + '"]')?.dataset?.eventType || 'standard');
                        closeRegistrationModal();
                    } else {
                        showToast(data.message || 'Registration failed. Please check your details.', 'error');
                    }
                } catch (error) {
                    console.error(error);
                    showToast('Network error. Please try again.', 'error');
                } finally {
                    landingRegistrationSubmit.disabled = false;
                    landingRegistrationSubmit.textContent = registrationType.value === 'guest' ? 'Register as Guest' : 'Register Participant';
                }
            });
        })();
    </script>

    <!-- Template Download Modal -->
    <div id="templateDownloadModal" class="template-modal-overlay fixed inset-0 bg-black/50 hidden items-center justify-center z-[9999]">
        <div class="template-modal-content bg-white rounded-2xl p-8 max-w-[640px] w-full mx-4 shadow-2xl">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Download Conference Paper Template</h2>
            <p class="text-gray-600 mb-6">Are you a registered guest?</p>
            
            <div class="space-y-4">
                <div>
                    <label for="guestToken" class="block text-sm font-medium text-gray-700 mb-2">
                        Enter your Guest Token
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
                    // File download
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'Conference-Paper-Template.' + (currentEventId || 'doc');
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    a.remove();
                    
                    // Close modal
                    closeModal();
                } else {
                    const errorData = await response.json();
                    tokenError.textContent = errorData.error || 'An error occurred. Please try again.';
                    tokenError.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Download error:', error);
                tokenError.textContent = 'Network error. Please try again.';
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
    </script>
</body>
</html>
