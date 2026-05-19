@extends('layouts.app')

@section('title', (auth()->user()->roleLabel() ?? 'Admin').' Dashboard - Eventure')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-1: #e8f4fd;
            --bg-2: #0a2342;
            --surface: rgba(255, 255, 255, 0.08);
            --surface-soft: rgba(255, 255, 255, 0.05);
            --line: rgba(191, 223, 255, 0.28);
            --text: #ffffff;
            --muted: rgba(191, 223, 255, 0.82);
            --accent: #58a4cf;
            --accent-strong: #1b6ca8;
        }

        body {
            font-family: 'Sora', sans-serif;
            background: #f8fafb;
            color: #111827;
        }

        .container {
            max-width: none;
            margin: 0;
            padding: 0;
        }

        .top-nav {
            display: none;
        }

        .alert-success,
        .alert-error {
            margin: 16px;
            border-radius: 12px;
        }

        .dashboard-shell {
            min-height: 100dvh;
            display: block;
            background: #f8fafb;
        }

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

        .nav-toggle svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        .nav-overlay {
            display: none;
        }

        .sidebar {
            box-sizing: border-box;
            background: var(--bg-2);
            border-right: 1px solid rgba(191, 223, 255, 0.26);
            display: flex;
            flex-direction: column;
            padding: 14px 10px;
            gap: 14px;
            width: 248px;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            height: 100dvh;
            overflow: hidden;
            border-radius: 0 12px 12px 0;
        }

        .brand {
            text-decoration: none;
            font-size: 1.48rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            padding: 2px 8px;
        }

        .brand-event {
            color: #fff;
        }

        .brand-flow {
            color: var(--accent);
        }

        .side-nav {
            display: grid;
            gap: 4px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            text-decoration: none;
            border-radius: 8px;
            padding: 8px 9px;
            border-left: 3px solid transparent;
            font-size: 0.86rem;
            line-height: 1.2;
            transition: background 180ms ease, color 180ms ease, border-color 180ms ease;
        }

        .nav-item svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        .nav-item:hover {
            background: rgba(88, 164, 207, 0.2);
            color: var(--text);
        }

        .nav-item.active {
            color: var(--accent);
            background: rgba(88, 164, 207, 0.24);
            border-left-color: var(--accent);
        }

        .sidebar-footer {
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 8px;
            border-top: 1px solid var(--line);
        }

        .user-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            border: 1px solid var(--line);
            display: grid;
            place-items: center;
            background: var(--surface);
            color: var(--accent);
            font-weight: 700;
            font-size: 0.78rem;
        }

        .user-text {
            min-width: 0;
        }

        .user-name {
            margin: 0;
            color: var(--text);
            font-size: 0.81rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            margin: 2px 0 0;
            color: var(--muted);
            font-size: 0.7rem;
        }

        .icon-btn {
            border: 1px solid var(--line);
            background: var(--surface-soft);
            color: var(--muted);
            border-radius: 8px;
            width: 32px;
            height: 32px;
            display: grid;
            place-items: center;
            cursor: pointer;
            transition: color 180ms ease, border-color 180ms ease, background 180ms ease;
        }

        .icon-btn:hover {
            color: var(--accent);
            border-color: rgba(88, 164, 207, 0.48);
            background: rgba(88, 164, 207, 0.18);
        }

        .icon-btn svg {
            width: 15px;
            height: 15px;
        }

        .main {
            padding: 26px;
            overflow: visible;
            min-height: 100dvh;
            margin-left: 272px;
            background: #f8fafb;
            color: #111827;
            box-sizing: border-box;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 18px;
        }

        .header h1 {
            margin: 0;
            font-size: 1.55rem;
            font-weight: 700;
            letter-spacing: -0.01em;
            color: #111827;
        }

        .header .date {
            color: #6b7280;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 16px;
        }

        .card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: transform 180ms ease, box-shadow 220ms ease, border-color 220ms ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(27, 108, 168, 0.16);
            border-color: rgba(27, 108, 168, 0.42);
        }

        .stat-card {
            padding: 16px;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: #1b6ca8;
            opacity: 1;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.84rem;
        }

        .stat-value {
            margin-top: 10px;
            font-size: 1.9rem;
            font-weight: 700;
            color: #1b6ca8;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 14px;
        }

        .panel {
            padding: 16px;
            box-sizing: border-box;
        }

        .panel-title {
            margin: 0 0 12px;
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
        }

        .chart-wrap {
            background: #ffffff;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            padding: 10px;
            box-sizing: border-box;
        }

        .chart-wrap svg {
            width: 100%;
            height: auto;
            display: block;
        }

        .chart-grid-line {
            stroke: rgba(107, 114, 128, 0.22);
            stroke-width: 1;
        }

        .chart-baseline {
            stroke: #d1d5db;
            stroke-width: 1;
        }

        .chart-bar {
            fill: #1b6ca8;
            transition: fill 150ms ease;
        }

        .chart-bar-group:hover .chart-bar {
            fill: #0a2342;
        }

        .chart-y-label {
            font-size: 10px;
            fill: #9ca3af;
            font-family: 'Sora', sans-serif;
        }

        .chart-x-label {
            font-size: 9px;
            fill: #9ca3af;
            font-family: 'Sora', sans-serif;
        }

        .table-wrap {
            overflow: auto;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #ffffff;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            color: #111827;
            font-size: 0.87rem;
        }

        th {
            color: #6b7280;
            font-weight: 600;
            background: #f8fafb;
        }

        tbody tr {
            transition: background 160ms ease;
        }

        tbody tr:hover {
            background: rgba(88, 164, 207, 0.12);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 0.74rem;
            font-weight: 600;
        }

        .badge-ongoing {
            background: rgba(34, 197, 94, 0.14);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.35);
        }

        .badge-upcoming {
            background: rgba(88, 164, 207, 0.14);
            color: #1b6ca8;
            border: 1px solid rgba(88, 164, 207, 0.35);
        }

        .badge-completed {
            background: rgba(255, 107, 53, 0.14);
            color: #ff6b35;
            border: 1px solid rgba(255, 107, 53, 0.35);
        }

        .upcoming-list {
            margin: 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 10px;
        }

        .up-item {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 11px;
            transition: border-color 160ms ease, transform 160ms ease, background 160ms ease;
        }

        .up-item:hover {
            transform: translateY(-1px);
            border-color: rgba(88, 164, 207, 0.4);
            background: rgba(88, 164, 207, 0.12);
        }

        .up-name {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
            color: #111827;
        }

        .up-meta {
            margin-top: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            color: #6b7280;
            font-size: 0.8rem;
        }

        .mini-badge {
            border-radius: 999px;
            padding: 3px 8px;
            background: rgba(88, 164, 207, 0.14);
            border: 1px solid rgba(88, 164, 207, 0.35);
            color: #1b6ca8;
            font-size: 0.7rem;
            font-weight: 600;
        }

        @media (max-width: 1180px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 960px) {
            .header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .stats-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 8px;
                align-items: stretch;
            }

            .stat-card {
                padding: 12px 10px;
                min-width: 0;
            }

            .stat-label {
                font-size: 0.68rem;
                line-height: 1.25;
            }

            .stat-value {
                margin-top: 8px;
                font-size: 1.25rem;
                line-height: 1.1;
            }

            .nav-toggle {
                display: inline-flex;
            }

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

            .dashboard-shell.is-nav-open .nav-overlay {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
                transition: opacity 180ms ease;
            }

            .dashboard-shell.is-nav-open .nav-toggle {
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
                padding: 14px 10px;
            }

            .dashboard-shell.is-nav-open .sidebar {
                transform: translateX(0);
            }

            .main {
                padding: 68px 16px 16px;
                margin-left: 0;
                width: 100%;
                max-width: 100%;
                overflow-x: hidden;
                display: grid;
                justify-items: center;
            }

            .main > * {
                width: min(100%, 760px);
                margin-inline: auto;
            }

            .content-grid {
                grid-template-columns: 1fr;
                justify-items: center;
                gap: 12px;
                width: min(100%, 760px);
            }

            .content-grid > * {
                width: 100%;
                min-width: 0;
                max-width: 100%;
            }

            .content-grid > div {
                width: 100%;
                min-width: 0;
            }

            .stats-grid {
                width: min(100%, 760px);
                margin-inline: auto;
            }

            .panel {
                width: 100%;
                max-width: 100%;
                margin-inline: auto;
            }

            .panel-title {
                text-align: center;
            }

            .chart-wrap,
            .table-wrap,
            .upcoming-list {
                width: 100%;
                max-width: 100%;
                margin-inline: auto;
            }

            .card {
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
            }

            .chart-wrap {
                overflow: hidden;
            }

            table {
                table-layout: fixed;
            }

            th,
            td {
                padding: 10px 8px;
                font-size: 0.78rem;
                white-space: normal;
                word-break: break-word;
            }

            .up-item,
            .table-wrap,
            .chart-wrap {
                text-align: left;
            }

            .side-nav {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 560px) {
            .main {
                padding: 68px 12px 12px;
            }

            .main > *,
            .stats-grid,
            .content-grid {
                width: min(100%, 100vw - 24px);
            }

            .stats-grid {
                gap: 6px;
            }

            .stat-card {
                padding: 10px 8px;
            }

            .stat-label {
                font-size: 0.62rem;
            }

            .stat-value {
                font-size: 1.08rem;
            }

            .header h1 {
                font-size: 1.3rem;
            }

            .header .date {
                font-size: 0.8rem;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $adminName = auth()->user()->name ?? 'Admin';
        $roleLabel = auth()->user()->roleLabel();
        $initials = collect(explode(' ', trim($adminName)))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('');
        $initials = $initials !== '' ? $initials : 'AD';
        $statCards = [
            ['label' => 'Total Events', 'value' => $stats['total_events'] ?? 0],
            ['label' => 'Total Participants', 'value' => $stats['total_participants'] ?? 0],
            ['label' => 'Upcoming Events', 'value' => $stats['upcoming_events'] ?? 0],
        ];

        $chartRows = collect($registrationChart ?? []);
        $chartMax = max(1, (int) $chartRows->max('total'));
        // Bar chart geometry
        $barBaseline  = 220;
        $barTop       = 40;
        $barPlotHeight = $barBaseline - $barTop;   // 180
        $barPlotX1    = 44;
        $barPlotX2    = 730;
        $barCount     = max(1, $chartRows->count());
        $barSlotWidth = ($barPlotX2 - $barPlotX1) / $barCount;  // 22.87 for 30 days
        $barWidth     = round($barSlotWidth * 0.62, 2);
        $barPad       = round(($barSlotWidth - $barWidth) / 2, 2);
        $yTicks = [
            ['y' => 40,  'val' => $chartMax],
            ['y' => 85,  'val' => round($chartMax * 0.75)],
            ['y' => 130, 'val' => round($chartMax * 0.5)],
            ['y' => 175, 'val' => round($chartMax * 0.25)],
        ];
        $fallbackEvent = \App\Models\Event::query()->orderByDesc('start_date')->first();
        $participantsNavUrl = $fallbackEvent
            ? \App\Support\PreviewAuth::appendToUrl(route('events.participants.index', $fallbackEvent, false), $previewAuthQuery)
            : \App\Support\PreviewAuth::appendToUrl(route('participants.index', [], false), $previewAuthQuery);
    @endphp

    <div class="dashboard-shell">
        <button type="button" class="nav-toggle" aria-label="Open navigation" aria-controls="dashboardSidebar" aria-expanded="false">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18"></path><path d="M3 12h18"></path><path d="M3 18h18"></path></svg>
        </button>
        <div class="nav-overlay" aria-hidden="true"></div>
        <aside class="sidebar" id="dashboardSidebar">
            <a href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.dashboard', [], false), $previewAuthQuery) }}" class="brand">
                <span class="brand-event">Even</span><span class="brand-flow">ture</span>
            </a>

            <nav class="side-nav" aria-label="Main navigation">
                <a href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.dashboard', [], false), $previewAuthQuery) }}" class="nav-item active">
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
                <a href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.analytics.events', [], false), $previewAuthQuery) }}" class="nav-item">
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

        <main class="main">
            <div class="header">
                <h1>Good day, {{ $adminName }} 👋</h1>
                <div class="date">{{ now()->format('l, F d, Y') }}</div>
            </div>

            <section class="stats-grid">
                @foreach ($statCards as $card)
                    <article class="card stat-card">
                        <div class="stat-label">{{ $card['label'] }}</div>
                        <div class="stat-value">{{ number_format($card['value']) }}</div>
                    </article>
                @endforeach
            </section>

            <div class="content-grid">
                <div>
                    <section class="card panel">
                        <h2 class="panel-title">Event Registrations over the last 30 days</h2>
                        <div class="chart-wrap">
                            <svg viewBox="0 0 760 270" role="img" aria-label="Registrations per day, last 30 days">
                                {{-- Y-axis grid lines + labels --}}
                                @foreach ($yTicks as $tick)
                                    <line class="chart-grid-line" x1="44" y1="{{ $tick['y'] }}" x2="730" y2="{{ $tick['y'] }}"></line>
                                    <text class="chart-y-label" x="38" y="{{ $tick['y'] + 4 }}" text-anchor="end">{{ $tick['val'] }}</text>
                                @endforeach

                                {{-- Bars --}}
                                @foreach ($chartRows->values() as $i => $row)
                                    @php
                                        $barH     = $row['total'] > 0 ? round(($row['total'] / $chartMax) * $barPlotHeight, 2) : 0;
                                        $bx       = round($barPlotX1 + $i * $barSlotWidth + $barPad, 2);
                                        $by       = $barBaseline - $barH;
                                        $labelX   = round($barPlotX1 + ($i + 0.5) * $barSlotWidth, 2);
                                        $showLabel = ($i % 5 === 0) || ($i === $barCount - 1);
                                    @endphp
                                    <g class="chart-bar-group">
                                        <title>{{ $row['label'] }}: {{ $row['total'] }} registration(s)</title>
                                        <rect class="chart-bar" x="{{ $bx }}" y="{{ $by }}" width="{{ $barWidth }}" height="{{ max(0, $barH) }}" rx="2"></rect>
                                        @if ($showLabel)
                                            <text class="chart-x-label" x="{{ $labelX }}" y="248" text-anchor="middle">{{ $row['label'] }}</text>
                                        @endif
                                    </g>
                                @endforeach

                                {{-- Baseline --}}
                                <line class="chart-baseline" x1="44" y1="220" x2="730" y2="220"></line>
                            </svg>
                        </div>
                    </section>

                    <section class="card panel">
                        <h2 class="panel-title">Recent Events</h2>
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Event Name</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Participants</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentEvents as $event)
                                        @php
                                            $status = $event->hasEnded() ? 'Completed' : (($event->start_date?->isFuture()) ? 'Upcoming' : 'Ongoing');
                                        @endphp
                                        <tr style="cursor: pointer;" onclick="window.location.href='{{ route('events.show', $event) }}'">
                                            <td>{{ $event->title }}</td>
                                            <td>{{ $event->dateRangeLabel() }}</td>
                                            <td>
                                                <span class="badge {{ $status === 'Ongoing' ? 'badge-ongoing' : ($status === 'Upcoming' ? 'badge-upcoming' : 'badge-completed') }}">
                                                    {{ $status }}
                                                </span>
                                            </td>
                                            <td>{{ number_format($event->participants_count ?? 0) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" style="color: #999;">No events available yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

                <aside class="card panel">
                    <h2 class="panel-title">Upcoming Events</h2>
                    <ul class="upcoming-list">
                        @forelse ($upcomingEvents as $item)
                            <a href="{{ route('events.show', $item) }}" style="text-decoration: none; color: inherit;">
                                <li class="up-item" style="cursor: pointer;">
                                    <p class="up-name">{{ $item->title }}</p>
                                    <div class="up-meta">
                                        <span>{{ $item->dateRangeLabel() }}</span>
                                        <span class="mini-badge">Scheduled</span>
                                    </div>
                                </li>
                            </a>
                        @empty
                            <a href="{{ route('events.create') }}" style="text-decoration: none; color: inherit;">
                                <li class="up-item" style="cursor: pointer;">
                                    <p class="up-name">No upcoming events</p>
                                    <div class="up-meta">
                                        <span>Create an event to get started</span>
                                    </div>
                                </li>
                            </a>
                        @endforelse
                    </ul>
                </aside>
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            var shell = document.querySelector('.dashboard-shell');
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
