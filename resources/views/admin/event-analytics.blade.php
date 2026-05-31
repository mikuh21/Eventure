@extends('layouts.app')

@section('title', 'Analytics - Eventure')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-2: #0a2342;
            --surface: rgba(255, 255, 255, 0.08);
            --surface-soft: rgba(255, 255, 255, 0.05);
            --line: rgba(191, 223, 255, 0.28);
            --text: #ffffff;
            --muted: rgba(191, 223, 255, 0.82);
            --accent: #58a4cf;
            --accent-strong: #1b6ca8;
        }

        body { font-family: 'Sora', sans-serif; background: #f8fafb; color: #111827; }
        .container { max-width: none; margin: 0; padding: 0; }
        .top-nav { display: none; }
        .alert-success, .alert-error { margin: 16px; border-radius: 12px; }

        /* ── Shell ── */
        .analytics-shell { min-height: 100dvh; display: block; background: #f8fafb; }

        .nav-toggle {
            display: none;
            position: fixed;
            top: 14px;
            left: 14px;
            z-index: 1405;
            width: 42px;
            height: 42px;
            border: 1px solid rgba(191, 223, 255, 0.28);
            border-radius: 12px;
            background: var(--bg-2);
            color: #ffffff;
            box-shadow: 0 10px 24px rgba(10, 35, 66, 0.2);
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .nav-toggle svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2; }
        .nav-overlay { display: none; }

        /* ── Sidebar ── */
        .sidebar {
            box-sizing: border-box;
            background: var(--bg-2);
            border-right: 1px solid rgba(191, 223, 255, 0.26);
            display: flex; flex-direction: column;
            padding: 14px 10px; gap: 14px;
            width: 248px;
            position: fixed; left: 0; top: 0; bottom: 0; height: 100dvh;
            overflow: hidden; border-radius: 0 12px 12px 0;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            text-decoration: none;
            font-size: 1.48rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1;
            padding: 4px 8px;
            flex-shrink: 0;
        }

        .brand img {
            display: block;
            height: 2.8rem;
            width: auto;
            flex-shrink: 0;
        }

        .brand-text {
            display: inline-flex;
            letter-spacing: -0.03em;
            flex-shrink: 0;
        }

        .brand-event { color: #fff; }
        .brand-flow  { color: var(--accent); }

        .side-nav { display: grid; gap: 4px; }

        .nav-item {
            display: flex; align-items: center; gap: 8px;
            color: var(--muted); text-decoration: none;
            border-radius: 8px; padding: 8px 9px;
            border-left: 3px solid transparent;
            font-size: 0.86rem; line-height: 1.2;
            transition: background 180ms ease, color 180ms ease, border-color 180ms ease;
        }
        .nav-item svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; flex-shrink: 0; }
        .nav-item:hover { background: rgba(88,164,207,0.2); color: var(--text); }
        .nav-item.active { color: var(--accent); background: rgba(88,164,207,0.24); border-left-color: var(--accent); }

        .sidebar-footer { margin-top: auto; display: flex; align-items: center; justify-content: space-between; gap: 8px; padding: 8px; border-top: 1px solid var(--line); }
        .user-meta { display: flex; align-items: center; gap: 10px; min-width: 0; }
        .avatar { width: 34px; height: 34px; border-radius: 999px; border: 1px solid var(--line); display: grid; place-items: center; background: var(--surface); color: var(--accent); font-weight: 700; font-size: 0.78rem; flex-shrink: 0; }
        .user-text { min-width: 0; }
        .user-name { margin: 0; color: var(--text); font-size: 0.81rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { margin: 2px 0 0; color: var(--muted); font-size: 0.7rem; }
        .icon-btn { border: 1px solid var(--line); background: var(--surface-soft); color: var(--muted); border-radius: 8px; width: 32px; height: 32px; display: grid; place-items: center; cursor: pointer; transition: color 180ms ease, border-color 180ms ease, background 180ms ease; flex-shrink: 0; }
        .icon-btn:hover { color: var(--accent); border-color: rgba(88,164,207,0.48); background: rgba(88,164,207,0.18); }
        .icon-btn svg { width: 15px; height: 15px; }

        /* ── Main ── */
        .main { padding: 26px; min-height: 100dvh; margin-left: 272px; background: #f8fafb; color: #111827; }

        /* ── Page header ── */
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; margin-bottom: 20px; flex-wrap: wrap; }
        .page-header h1 { margin: 0; font-size: 1.55rem; font-weight: 700; letter-spacing: -0.01em; color: #111827; }
        .page-header .subtitle { margin: 4px 0 0; font-size: 0.85rem; color: #6b7280; }

        /* ── Filter bar ── */
        .filter-bar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; }
        .filter-bar label { font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; }
        .filter-select, .filter-input-sm { border: 1px solid #d1d5db; border-radius: 8px; padding: 7px 12px; font-family: 'Sora', sans-serif; font-size: 13px; color: #111827; background: #f8fafb; transition: border-color 150ms ease, box-shadow 150ms ease; }
        .filter-select { appearance: none; -webkit-appearance: none; -moz-appearance: none; padding-right: 34px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235BA4CF' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; background-size: 14px 14px; }
        .filter-select:focus, .filter-input-sm:focus { border-color: #1b6ca8; outline: none; box-shadow: 0 0 0 3px rgba(27,108,168,0.12); }
        .filter-input-sm { width: 86px; }
        .filter-sep { width: 1px; height: 24px; background: #e5e7eb; flex-shrink: 0; }
        .btn-filter { border: none; background: #1b6ca8; color: #ffffff; border-radius: 8px; padding: 8px 16px; font-family: 'Sora', sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 150ms ease; white-space: nowrap; }
        .btn-filter:hover { background: #0a2342; }
        .scope-badge { margin-left: auto; background: rgba(27,108,168,0.1); color: #1b6ca8; border: 1px solid rgba(27,108,168,0.25); border-radius: 999px; padding: 4px 12px; font-size: 12px; font-weight: 600; white-space: nowrap; }

        /* ── Stats grid ── */
        .stats-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 18px; }
        .stat-card { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 18px; position: relative; overflow: hidden; transition: transform 180ms ease, box-shadow 220ms ease, border-color 220ms ease; }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: var(--accent-color, #1b6ca8); }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(27,108,168,0.14); border-color: rgba(27,108,168,0.35); }
        .stat-icon { width: 36px; height: 36px; border-radius: 10px; display: grid; place-items: center; margin-bottom: 14px; }
        .stat-icon svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2; }
        .stat-label { font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; }
        .stat-value { margin-top: 6px; font-size: 2rem; font-weight: 700; line-height: 1; color: #111827; }
        .stat-sub   { margin-top: 4px; font-size: 0.75rem; color: #9ca3af; }

        /* ── Content grid ── */
        .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 14px; margin-bottom: 14px; }

        .panel { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .panel-title { margin: 0 0 14px; font-size: 0.95rem; font-weight: 700; color: #111827; }

        /* ── Chart ── */
        .chart-wrap { background: #f8fcff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 8px; }
        .chart-wrap svg { width: 100%; height: auto; display: block; }
        .chart-grid-line { stroke: rgba(107,114,128,0.18); stroke-width: 1; }
        .chart-baseline   { stroke: #d1d5db; stroke-width: 1; }
        .chart-bar        { fill: #1b6ca8; transition: fill 150ms ease; }
        .chart-bar-group:hover .chart-bar { fill: #0a2342; }
        .chart-y-label { font-size: 9.5px; fill: #9ca3af; font-family: 'Sora', sans-serif; }
        .chart-x-label { font-size: 8.5px; fill: #9ca3af; font-family: 'Sora', sans-serif; }
        .chart-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 180px; gap: 8px; color: #9ca3af; font-size: 0.85rem; }
        .chart-empty svg { width: 36px; height: 36px; color: #d1d5db; }

        /* ── Summary / progress ── */
        .summary-list { display: grid; gap: 14px; }
        .summary-item { display: grid; gap: 5px; }
        .summary-item-header { display: flex; justify-content: space-between; align-items: center; }
        .summary-item-label { font-size: 0.8rem; color: #374151; font-weight: 500; }
        .summary-item-value { font-size: 0.8rem; font-weight: 700; color: #111827; }
        .progress-bar-wrap { height: 6px; background: #e5e7eb; border-radius: 999px; overflow: hidden; }
        .progress-bar { height: 100%; border-radius: 999px; }

        /* ── Type rows ── */
        .type-row { display: flex; justify-content: space-between; align-items: center; padding: 10px; border-radius: 8px; background: #f8fafb; border: 1px solid #e5e7eb; }
        .type-label { font-size: 0.82rem; font-weight: 600; color: #374151; }
        .type-count { font-size: 1.05rem; font-weight: 700; color: #1b6ca8; }

        /* ── Events table ── */
        .table-panel { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .table-wrap { overflow-x: auto; border: 1px solid #e5e7eb; border-radius: 10px; }
        .events-table { width: 100%; border-collapse: collapse; font-size: 0.84rem; }
        .events-table th, .events-table td { text-align: left; padding: 11px 14px; border-bottom: 1px solid #f0f4f8; white-space: nowrap; }
        .events-table th { background: #f8fafb; font-size: 0.73rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; }
        .events-table tbody tr:last-child td { border-bottom: none; }
        .events-table tbody tr:hover td { background: #f8fcff; }
        .events-table td.muted { color: #9ca3af; }

        .rate-pill { display: inline-flex; align-items: center; border-radius: 999px; padding: 3px 9px; font-size: 0.72rem; font-weight: 700; }
        .rate-high { background: #ecfdf5; color: #047857; }
        .rate-mid  { background: #fffbeb; color: #b45309; }
        .rate-low  { background: #fef2f2; color: #b91c1c; }
        .rate-na   { background: #f3f4f6; color: #6b7280; }

        .event-badge { display: inline-flex; align-items: center; border-radius: 999px; padding: 3px 9px; font-size: 0.72rem; font-weight: 600; }
        .badge-conference { background: rgba(88,164,207,0.14); color: #1b6ca8; border: 1px solid rgba(88,164,207,0.3); }
        .badge-student    { background: rgba(10,35,66,0.08); color: #0a2342; border: 1px solid rgba(10,35,66,0.18); }

        .stars { color: #f59e0b; font-size: 0.9rem; letter-spacing: 0.02em; }
        .tbl-empty { text-align: center; padding: 40px; color: #9ca3af; font-size: 0.85rem; }

        @media (max-width: 1180px) { .content-grid { grid-template-columns: 1fr; } }
        @media (max-width: 900px)  { .stats-grid { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 960px)  {
            .nav-toggle { display: inline-flex; }
            .nav-overlay {
                display: block;
                position: fixed;
                inset: 0;
                z-index: 1390;
                background: rgba(10, 35, 66, 0.5);
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
                transition: opacity 180ms ease, visibility 0s linear 180ms;
            }
            .analytics-shell.is-nav-open .nav-overlay {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
                transition: opacity 180ms ease;
            }

            .analytics-shell.is-nav-open .nav-toggle {
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
            }
            .sidebar {
                width: min(82vw, 280px);
                z-index: 1400;
                transform: translateX(calc(-100% - 18px));
                transition: transform 200ms ease;
                box-shadow: 0 18px 42px rgba(10, 35, 66, 0.28);
                overflow-y: auto;
                border-radius: 0 16px 16px 0;
            }
            .analytics-shell.is-nav-open .sidebar { transform: translateX(0); }
            .main { margin-left: 0; padding: 68px 16px 16px; }
            .side-nav { grid-template-columns: 1fr; }
        }
    </style>
@endpush

@section('content')
    @php
        $adminName = auth()->user()->name ?? 'Admin';
        $roleLabel = auth()->user()->roleLabel();
        $initials  = collect(explode(' ', trim($adminName)))->filter()->map(fn ($p) => strtoupper(substr($p, 0, 1)))->take(2)->implode('');
        $initials  = $initials !== '' ? $initials : 'AD';

        $fallbackEvent      = \App\Models\Event::query()->orderByDesc('start_date')->first();
        $participantsNavUrl = $fallbackEvent ? route('events.participants.index', $fallbackEvent) : route('participants.index');

        // Bar chart: top 10 events by participants
        $chartEvents  = $eventsBreakdown->sortByDesc('participants_count')->take(10)->values();
        $chartMax     = max(1, (int) $chartEvents->max('participants_count'));
        $barCount     = max(1, $chartEvents->count());
        $plotX1 = 50; $plotX2 = 720; $plotH1 = 40; $plotH2 = 200;
        $barSlotWidth = ($plotX2 - $plotX1) / $barCount;
        $barWidth     = round($barSlotWidth * 0.58, 2);
        $barPad       = round(($barSlotWidth - $barWidth) / 2, 2);
        $plotHeight   = $plotH2 - $plotH1;
        $yTicks = [
            ['y' => 40,  'val' => $chartMax],
            ['y' => 93,  'val' => round($chartMax * 0.72)],
            ['y' => 147, 'val' => round($chartMax * 0.44)],
            ['y' => 200, 'val' => 0],
        ];

        $scopeLabel = match($analytics['period']) {
            'month' => \Carbon\Carbon::create($analytics['year'], $analytics['month'])->format('F Y'),
            'year'  => (string) $analytics['year'],
            default => 'All Time',
        };

        $conferenceCount = $eventsBreakdown->where('type', \App\Models\Event::TYPE_CONFERENCE)->count();
        $studentCount    = $eventsBreakdown->where('type', \App\Models\Event::TYPE_SCHOOL)->count();
        $nonRespondentRate = $analytics['total_participants'] > 0
            ? max(0, round(($analytics['participants_minus_evaluations'] / $analytics['total_participants']) * 100, 1))
            : 0;
    @endphp

    @php
        $analyticsAction = route('admin.analytics.events', $previewAuthQuery, false);
        $participantsNavUrl = \App\Support\PreviewAuth::appendToUrl($participantsNavUrl, $previewAuthQuery);
    @endphp

    <div class="analytics-shell">
        <button type="button" class="nav-toggle" aria-label="Open navigation" aria-controls="analyticsSidebar" aria-expanded="false">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18"></path><path d="M3 12h18"></path><path d="M3 18h18"></path></svg>
        </button>
        <div class="nav-overlay" aria-hidden="true"></div>

        {{-- Sidebar --}}
        <aside class="sidebar" id="analyticsSidebar">
            <a href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.dashboard', [], false), $previewAuthQuery) }}" class="brand">
                <img src="{{ asset('eventurelogo.png') }}" alt="Eventure logo">
                <span class="brand-text"><span class="brand-event">Even</span><span class="brand-flow">ture</span></span>
            </a>

            <nav class="side-nav" aria-label="Main navigation">
                <a href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.dashboard', [], false), $previewAuthQuery) }}" class="nav-item">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12.5 12 4l9 8.5"></path><path d="M5 10.8V20h14v-9.2"></path></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ \App\Support\PreviewAuth::appendToUrl(route('events.index', [], false), $previewAuthQuery) }}" class="nav-item">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5" width="16" height="15" rx="2"></rect><path d="M8 3v4M16 3v4M4 10h16"></path></svg>
                    <span>Events</span>
                </a>
                <a href="{{ $participantsNavUrl }}" class="nav-item">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>Participants</span>
                </a>
                <a href="{{ \App\Support\PreviewAuth::appendToUrl(route('guests.index', [], false), $previewAuthQuery) }}" class="nav-item">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"></path><path d="M2 13h20"></path></svg>
                    <span>Guests</span>
                </a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.users.index', [], false), $previewAuthQuery) }}" class="nav-item">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M20 8v6"></path><path d="M17 11h6"></path></svg>
                        <span>Event Staff</span>
                    </a>
                    <a href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.event-evaluation-forms.index', [], false), $previewAuthQuery) }}" class="nav-item">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 19.5V4.5a1.5 1.5 0 0 1 1.5-1.5h9.38a1.5 1.5 0 0 1 1.06.44l2.62 2.62a1.5 1.5 0 0 1 .44 1.06V19.5A1.5 1.5 0 0 1 17.5 21h-12A1.5 1.5 0 0 1 4 19.5Z"></path><path d="M8 9h8M8 13h8M8 17h5"></path></svg>
                        <span>Evaluation Forms</span>
                    </a>
                @endif
                <a href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.digital-id.verify.form', [], false), $previewAuthQuery) }}" class="nav-item">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"></rect><path d="M7 8h10M7 12h5M7 16h3"></path></svg>
                    <span>Verify Digital ID</span>
                </a>
                <a href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.analytics.events', [], false), $previewAuthQuery) }}" class="nav-item active">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 19h16"></path><path d="M7 16V9"></path><path d="M12 16V5"></path><path d="M17 16v-3"></path></svg>
                    <span>Analytics</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-meta">
                    <div class="avatar">{{ $initials }}</div>
                    <div class="user-text">
                        <p class="user-name">{{ $adminName }}</p>
                        <p class="user-role">{{ $roleLabel }}</p>
                    </div>
                </div>
                <form action="{{ route('logout', [], false) }}" method="POST" class="js-logout-confirm-form">
                    @csrf
                    <button type="submit" class="icon-btn" aria-label="Logout">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main --}}
        <main class="main">

            <div class="page-header">
                <div>
                    <h1>Analytics</h1>
                    <p class="subtitle">Participation and evaluation insights across your events</p>
                </div>
            </div>

            {{-- Filter bar --}}
            <form class="filter-bar" method="GET" action="{{ $analyticsAction }}">
                @foreach ($previewAuthQuery as $previewKey => $previewValue)
                    <input type="hidden" name="{{ $previewKey }}" value="{{ $previewValue }}">
                @endforeach
                <label for="period">Scope</label>
                <select id="period" name="period" class="filter-select">
                    <option value="overall" {{ $analytics['period'] === 'overall' ? 'selected' : '' }}>All Time</option>
                    <option value="year"    {{ $analytics['period'] === 'year'    ? 'selected' : '' }}>By Year</option>
                    <option value="month"   {{ $analytics['period'] === 'month'   ? 'selected' : '' }}>By Month</option>
                </select>

                <span class="filter-sep"></span>

                <div id="yearField" style="display:flex;align-items:center;gap:10px;">
                    <label for="year">Year</label>
                    <input id="year" name="year" type="number" min="2000" max="2100" value="{{ $analytics['year'] }}" class="filter-input-sm">
                </div>

                <div id="monthField" style="display:flex;align-items:center;gap:10px;">
                    <label for="month">Month</label>
                    <input id="month" name="month" type="number" min="1" max="12" value="{{ $analytics['month'] }}" class="filter-input-sm">
                </div>

                <button type="submit" class="btn-filter">Apply</button>

                @if ($analytics['period'] !== 'overall')
                    <a href="{{ $analyticsAction }}" style="font-size:12px;color:#6b7280;text-decoration:none;margin-left:2px;">Clear</a>
                @endif

                <span class="scope-badge">{{ $scopeLabel }}</span>
            </form>

            {{-- Stat cards --}}
            <div class="stats-grid">
                <article class="stat-card" style="--accent-color:#1b6ca8">
                    <div class="stat-icon" style="background:#e0f0fb;color:#1b6ca8">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5" width="16" height="15" rx="2"></rect><path d="M8 3v4M16 3v4M4 10h16"></path></svg>
                    </div>
                    <div class="stat-label">Total Events</div>
                    <div class="stat-value">{{ number_format($analytics['total_events']) }}</div>
                    <div class="stat-sub">In selected scope</div>
                </article>

                <article class="stat-card" style="--accent-color:#0a2342">
                    <div class="stat-icon" style="background:#e8eef5;color:#0a2342">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <div class="stat-label">Participants</div>
                    <div class="stat-value">{{ number_format($analytics['total_participants']) }}</div>
                    <div class="stat-sub">{{ number_format($analytics['attended_total']) }} attended ({{ $analytics['attendance_rate'] }}%)</div>
                </article>

                <article class="stat-card" style="--accent-color:#059669">
                    <div class="stat-icon" style="background:#d1fae5;color:#059669">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    </div>
                    <div class="stat-label">Evaluation Rate</div>
                    <div class="stat-value">{{ $analytics['response_rate'] }}%</div>
                    <div class="stat-sub">{{ number_format($analytics['total_evaluations']) }} of {{ number_format($analytics['total_participants']) }} responded</div>
                </article>

                <article class="stat-card" style="--accent-color:#f59e0b">
                    <div class="stat-icon" style="background:#fef3c7;color:#d97706">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    </div>
                    <div class="stat-label">Avg. Rating</div>
                    <div class="stat-value">{{ $analytics['avg_rating'] > 0 ? $analytics['avg_rating'] : '—' }}</div>
                    <div class="stat-sub">
                        @if ($analytics['avg_rating'] > 0)
                            <span class="stars">@for ($s = 1; $s <= 5; $s++){{ $s <= round($analytics['avg_rating']) ? '★' : '☆' }}@endfor</span>
                        @else
                            No evaluations yet
                        @endif
                    </div>
                </article>
            </div>

            {{-- Chart + summary panel --}}
            <div class="content-grid">

                <div class="panel">
                    <h2 class="panel-title">
                        Participants by Event
                        @if ($chartEvents->count() < $eventsBreakdown->count())
                            <span style="font-weight:400;color:#9ca3af;font-size:0.8rem;">(top {{ $chartEvents->count() }})</span>
                        @endif
                    </h2>

                    @if ($chartEvents->isEmpty())
                        <div class="chart-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <path d="M4 19h16M7 16V9M12 16V5M17 16v-3"></path>
                            </svg>
                            <span>No event data for this scope</span>
                        </div>
                    @else
                        <div class="chart-wrap">
                            <svg viewBox="0 0 760 265" role="img" aria-label="Participants per event">
                                @foreach ($yTicks as $tick)
                                    <line class="chart-grid-line" x1="{{ $plotX1 }}" y1="{{ $tick['y'] }}" x2="{{ $plotX2 }}" y2="{{ $tick['y'] }}"></line>
                                    <text class="chart-y-label" x="{{ $plotX1 - 4 }}" y="{{ $tick['y'] + 4 }}" text-anchor="end">{{ $tick['val'] }}</text>
                                @endforeach

                                @foreach ($chartEvents as $i => $ev)
                                    @php
                                        $barH   = $ev->participants_count > 0 ? round(($ev->participants_count / $chartMax) * $plotHeight, 2) : 0;
                                        $bx     = round($plotX1 + $i * $barSlotWidth + $barPad, 2);
                                        $by     = $plotH2 - $barH;
                                        $lx     = round($plotX1 + ($i + 0.5) * $barSlotWidth, 2);
                                        $label  = \Illuminate\Support\Str::limit($ev->title, 11, '…');
                                    @endphp
                                    <g class="chart-bar-group">
                                        <title>{{ $ev->title }}: {{ $ev->participants_count }} participant(s)</title>
                                        <rect class="chart-bar" x="{{ $bx }}" y="{{ $by }}" width="{{ $barWidth }}" height="{{ max(0, $barH) }}" rx="3"></rect>
                                        <text class="chart-x-label" x="{{ $lx }}" y="252" text-anchor="middle">{{ $label }}</text>
                                    </g>
                                @endforeach

                                <line class="chart-baseline" x1="{{ $plotX1 }}" y1="{{ $plotH2 }}" x2="{{ $plotX2 }}" y2="{{ $plotH2 }}"></line>
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="panel">
                    <h2 class="panel-title">Participation Summary</h2>

                    <div class="summary-list">
                        <div class="summary-item">
                            <div class="summary-item-header">
                                <span class="summary-item-label">Evaluation Response Rate</span>
                                <span class="summary-item-value">{{ $analytics['response_rate'] }}%</span>
                            </div>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar" style="width:{{ $analytics['response_rate'] }}%;background:#059669;"></div>
                            </div>
                        </div>

                        <div class="summary-item">
                            <div class="summary-item-header">
                                <span class="summary-item-label">Attendance Rate</span>
                                <span class="summary-item-value">{{ $analytics['attendance_rate'] }}%</span>
                            </div>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar" style="width:{{ $analytics['attendance_rate'] }}%;background:#1b6ca8;"></div>
                            </div>
                        </div>

                        <div class="summary-item">
                            <div class="summary-item-header">
                                <span class="summary-item-label">Non-Respondents</span>
                                <span class="summary-item-value">{{ number_format($analytics['participants_minus_evaluations']) }}</span>
                            </div>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar" style="width:{{ $nonRespondentRate }}%;background:#f59e0b;"></div>
                            </div>
                        </div>
                    </div>

                    <hr style="border:none;border-top:1px solid #f0f4f8;margin:18px 0;">

                    <h3 style="margin:0 0 10px;font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:#6b7280;">Events by Type</h3>
                    <div style="display:grid;gap:8px;">
                        <div class="type-row">
                            <span class="type-label">School Events</span>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span class="event-badge badge-student">School</span>
                                <span class="type-count">{{ $studentCount }}</span>
                            </div>
                        </div>
                        <div class="type-row">
                            <span class="type-label">Conferences</span>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span class="event-badge badge-conference">Conference</span>
                                <span class="type-count">{{ $conferenceCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Per-event breakdown table --}}
            <div class="table-panel">
                <h2 class="panel-title">Event Breakdown</h2>
                <div class="table-wrap">
                    <table class="events-table">
                        <thead>
                            <tr>
                                <th>Event</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Participants</th>
                                <th>Attended</th>
                                <th>Evaluations</th>
                                <th>Response Rate</th>
                                <th>Avg Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($eventsBreakdown as $ev)
                                @php
                                    $evRate = $ev->participants_count > 0
                                        ? round(($ev->evaluations_count / $ev->participants_count) * 100)
                                        : null;
                                    $evAttRate = $ev->participants_count > 0
                                        ? round(($ev->attended_count / $ev->participants_count) * 100)
                                        : null;
                                    $isEnded = $ev->hasEnded();
                                    $rateClass = is_null($evRate) || ! $isEnded ? 'rate-na' : ($evRate >= 70 ? 'rate-high' : ($evRate >= 40 ? 'rate-mid' : 'rate-low'));
                                    $evAvgRating = $ev->avg_rating && $isEnded
                                        ? round((float) $ev->avg_rating, 1)
                                        : null;
                                @endphp
                                <tr>
                                    <td style="font-weight:600;color:#111827;max-width:200px;overflow:hidden;text-overflow:ellipsis;">{{ $ev->title }}</td>
                                    <td class="muted">{{ $ev->dateRangeLabel() }}</td>
                                    <td><span class="event-badge {{ $ev->type === 'conference' ? 'badge-conference' : 'badge-student' }}">{{ $ev->type === 'conference' ? 'Conference' : 'School' }}</span></td>
                                    <td style="font-weight:600;">{{ number_format($ev->participants_count) }}</td>
                                    <td>
                                        {{ number_format($ev->attended_count) }}
                                        @if (!is_null($evAttRate))
                                            <span class="muted" style="font-size:0.75rem;">({{ $evAttRate }}%)</span>
                                        @endif
                                    </td>
                                    <td>{{ $isEnded ? number_format($ev->evaluations_count) : '—' }}</td>
                                    <td><span class="rate-pill {{ $rateClass }}">{{ ! $isEnded ? '—' : (is_null($evRate) ? '—' : $evRate . '%') }}</span></td>
                                    <td>
                                        @if ($evAvgRating)
                                            <span style="font-weight:700;">{{ $evAvgRating }}</span>
                                            <span class="stars" style="font-size:0.75rem;">@for ($s = 1; $s <= 5; $s++){{ $s <= round($evAvgRating) ? '★' : '☆' }}@endfor</span>
                                        @else
                                            <span class="muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="tbl-empty">No events found for this scope.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            var shell = document.querySelector('.analytics-shell');
            var toggle = document.querySelector('.nav-toggle');
            var overlay = document.querySelector('.nav-overlay');
            var links = document.querySelectorAll('.side-nav .nav-item');

            if (!shell || !toggle) {
                return;
            }

            var closeNav = function () {
                shell.classList.remove('is-nav-open');
                toggle.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            };

            var openNav = function () {
                shell.classList.add('is-nav-open');
                toggle.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
            };

            toggle.addEventListener('click', function () {
                if (shell.classList.contains('is-nav-open')) {
                    closeNav();
                    return;
                }

                openNav();
            });

            if (overlay) {
                overlay.addEventListener('click', closeNav);
            }

            links.forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.innerWidth <= 960) {
                        closeNav();
                    }
                });
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeNav();
                }
            });
        })();
    </script>
@endpush

@push('scripts')
    <script>
        (function () {
            var periodSel  = document.getElementById('period');
            var yearField  = document.getElementById('yearField');
            var monthField = document.getElementById('monthField');

            if (!periodSel) return;

            var sync = function () {
                var v = periodSel.value;
                yearField.style.display  = v === 'overall' ? 'none' : 'flex';
                monthField.style.display = v === 'month'   ? 'flex'  : 'none';
            };

            periodSel.addEventListener('change', sync);
            sync();
        })();
    </script>
@endpush

