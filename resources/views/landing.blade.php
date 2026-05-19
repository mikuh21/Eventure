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
                <h2 class="font-bold text-white" style="font-size:22px;font-weight:700">Happening Now</h2>
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
                                        LIVE
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-2">{{ $event->title }}</h3>
                                <div class="space-y-1 text-sm" style="color:rgba(255,255,255,0.6)">
                                    <p><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 21s7-5.2 7-11a7 7 0 1 0-14 0c0 5.8 7 11 7 11z"></path><circle cx="12" cy="10" r="2.5"></circle></svg></span> {{ $event->location ?? 'TBA' }}</p>
                                    <p><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M16 3v4M8 3v4M3 10h18"></path></svg></span> {{ $event->dateRangeLabel() }}</p>
                                    <p><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></span> {{ $event->participants_count ?? $event->participants->count() }} attendees</p>
                                    @if($event->type === 'conference' && $event->template_file_path)
                                        <p><a href="{{ url('events/' . $event->id . '/template/download') }}" class="inline-flex items-center gap-1 underline hover:opacity-80 transition"><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline><path d="M9 15l3 3 5-5"></path></svg></span> Conference Paper Template</a></p>
                                    @endif
                                </div>
                                <a href="{{ route('events.participants.create', $event) }}" class="mt-4 inline-block px-5 py-2.5 bg-live text-white rounded-[10px] text-sm font-semibold hover:brightness-110 transition">
                                    Join Now
                                </a>
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
                                    @if($event->theme)
                                        <p><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 3a9 9 0 1 0 0 18h1a3 3 0 0 0 0-6h-1a1.5 1.5 0 0 1 0-3h4a5 5 0 0 0 0-10h-4z"></path><circle cx="7.5" cy="9" r="1"></circle><circle cx="10" cy="6.5" r="1"></circle><circle cx="14" cy="6.5" r="1"></circle></svg></span> {{ $event->theme }}</p>
                                    @endif
                                    @if($event->type === 'conference' && $event->template_file_path)
                                        <p><a href="{{ url('events/' . $event->id . '/template/download') }}" class="inline-flex items-center gap-1 underline hover:opacity-80 transition"><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline><path d="M9 15l3 3 5-5"></path></svg></span> Conference Paper Template</a></p>
                                    @endif
                                </div>
                                <div class="mt-auto">
                                    @if($event->isRegistrationOpen())
                                        <a href="{{ route('events.participants.create', $event) }}" class="block w-full text-center px-5 py-2.5 rounded-[10px] text-sm font-semibold text-em4 hover:bg-em4/25 transition" style="background:rgba(27,108,168,0.15);border:1px solid rgba(27,108,168,0.35)">
                                            Register Now
                                        </a>
                                    @else
                                        <span class="block w-full text-center px-5 py-2.5 rounded-[10px] text-sm cursor-not-allowed" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.35)">
                                            Registration Closed
                                        </span>
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
                                    <span><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></span> {{ $event->participants_count ?? $event->participants->count() }} attended</span>
                                    @if($event->averageRating())
                                        <span><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="m12 3.8 2.57 5.2 5.74.83-4.16 4.05.98 5.72L12 16.9l-5.13 2.7.98-5.72L3.69 9.83l5.74-.83L12 3.8z" fill="currentColor" stroke="none"></path></svg></span> {{ $event->averageRating() }}/5</span>
                                    @endif
                                    @if($event->type === 'conference' && $event->template_file_path)
                                        <a href="{{ url('events/' . $event->id . '/template/download') }}" class="inline-flex items-center gap-1 underline hover:opacity-80 transition"><span class="icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline><path d="M9 15l3 3 5-5"></path></svg></span> Conference Paper Template</a>
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
</body>
</html>
