<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Event Management System')</title>
    <link rel="icon" type="image/png" sizes="any" href="{{ asset('eventureicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('eventureicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --scrollbar-thumb: rgba(107, 114, 128, 0.45);
            --scrollbar-thumb-hover: rgba(107, 114, 128, 0.68);
            --scrollbar-track: transparent;
        }

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

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 24px;
        }

        .top-nav {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #111827;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
        }

        .btn-danger {
            background: #dc2626;
            border-color: #dc2626;
            color: #ffffff;
        }

        .btn-secondary {
            background: #4b5563;
            border-color: #4b5563;
            color: #ffffff;
        }

        .btn-cancel,
        .btn.btn-cancel,
        button.btn-delete-cancel,
        button.btn-confirm-cancel,
        button.btn-edit-cancel,
        a.admin-management-cancel-btn,
        button.btn.admin-management-btn-secondary,
        button.btn-secondary.modal-close-btn,
        button.btn-secondary.event-questions-modal-close,
        button.btn-secondary.create-form-modal-close,
        button[id^="cancel"],
        button[id$="Cancel"] {
            border: 1px solid #bfdfff;
            background: #ffffff;
            color: #1b6ca8;
            border-radius: 8px;
            padding: 9px 18px;
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 160ms ease, border-color 160ms ease;
        }

        .btn-cancel:hover,
        .btn.btn-cancel:hover,
        button.btn-delete-cancel:hover,
        button.btn-confirm-cancel:hover,
        button.btn-edit-cancel:hover,
        a.admin-management-cancel-btn:hover,
        button.btn.admin-management-btn-secondary:hover,
        button.btn-secondary.modal-close-btn:hover,
        button.btn-secondary.event-questions-modal-close:hover,
        button.btn-secondary.create-form-modal-close:hover,
        button[id^="cancel"]:hover,
        button[id$="Cancel"]:hover {
            background: #e8f4fd;
            border-color: #5ba4cf;
            color: #1b6ca8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th, td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 14px;
        }

        .field {
            margin-bottom: 14px;
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .alert-success {
            background: #ecfdf5;
            border: 1px solid #34d399;
            color: #065f46;
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .alert-success-main {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            min-width: 0;
        }

        .alert-success-icon {
            width: 18px;
            height: 18px;
            flex: 0 0 18px;
            margin-top: 1px;
            color: #059669;
        }

        .alert-success-text {
            font-size: 13px;
            line-height: 1.45;
            word-break: break-word;
        }

        .alert-dismiss {
            border: none;
            background: transparent;
            color: #047857;
            font-size: 17px;
            line-height: 1;
            padding: 0;
            width: 20px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 160ms ease, color 160ms ease;
        }

        .alert-dismiss:hover {
            background: rgba(4, 120, 87, 0.12);
            color: #065f46;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #ef4444;
            color: #991b1b;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 16px;
        }

        .pagination {
            margin-top: 12px;
        }

        .confirm-modal {
            position: fixed;
            inset: 0;
            background: rgba(10, 35, 66, 0.55);
            z-index: 1500;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 180ms ease, visibility 0s linear 180ms;
        }

        .confirm-modal.is-visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition: opacity 180ms ease;
        }

        .confirm-panel {
            background: #ffffff;
            border-radius: 16px;
            width: 100%;
            max-width: 400px;
            padding: 28px;
            box-shadow: 0 8px 40px rgba(10, 35, 66, 0.22);
            transform: translateY(10px) scale(0.98);
            opacity: 0;
            transition: transform 220ms ease, opacity 220ms ease;
        }

        .confirm-modal.is-visible .confirm-panel {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .confirm-title {
            margin: 0 0 8px;
            font-family: 'Sora', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: #0a2342;
        }

        .confirm-body {
            margin: 0 0 22px;
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
        }

        .confirm-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-confirm-cancel {
            border: 1px solid #bfdfff;
            background: #ffffff;
            color: #1b6ca8;
            border-radius: 8px;
            padding: 9px 18px;
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 160ms ease, border-color 160ms ease;
        }

        .btn-confirm-cancel:hover {
            background: #e8f4fd;
            border-color: #5ba4cf;
        }

        .btn-confirm-submit {
            border: 1px solid #0a2342;
            background: #0a2342;
            color: #ffffff;
            border-radius: 8px;
            padding: 9px 18px;
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 160ms ease, border-color 160ms ease;
        }

        .btn-confirm-submit:hover {
            background: #1b6ca8;
            border-color: #1b6ca8;
        }

        @media (max-width: 640px) {
            .container {
                padding: 12px;
            }

            .confirm-panel {
                padding: 24px 18px;
            }

            .confirm-actions {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .btn-confirm-cancel,
            .btn-confirm-submit {
                width: 100%;
                text-align: center;
            }
        }

        body.admin-shell-theme {
            font-family: 'Sora', sans-serif;
            background: #f8fafb;
            color: #111827;
        }

        .admin-shell {
            min-height: 100dvh;
            display: block;
            background: #f8fafb;
        }

        .admin-nav-toggle {
            display: none;
            position: fixed;
            top: 14px;
            left: 14px;
            z-index: 1405;
            width: 42px;
            height: 42px;
            border: 1px solid rgba(191, 223, 255, 0.28);
            border-radius: 12px;
            background: #0a2342;
            color: #ffffff;
            box-shadow: 0 10px 24px rgba(10, 35, 66, 0.2);
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .admin-nav-toggle svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        .admin-nav-overlay {
            display: none;
        }

        .admin-sidebar {
            background: #0a2342;
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
            box-sizing: border-box;
            overflow: hidden;
            border-radius: 0 12px 12px 0;
        }

        .admin-brand {
            text-decoration: none;
            font-size: 1.48rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            padding: 2px 8px;
        }

        .admin-brand-event {
            color: #fff;
        }

        .admin-brand-flow {
            color: #58a4cf;
        }

        .admin-side-nav {
            display: grid;
            gap: 4px;
        }

        .admin-nav-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(191, 223, 255, 0.82);
            text-decoration: none;
            border-radius: 8px;
            padding: 8px 9px;
            border-left: 3px solid transparent;
            font-size: 0.86rem;
            line-height: 1.2;
            transition: background 180ms ease, color 180ms ease, border-color 180ms ease;
        }

        .admin-nav-item svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        .admin-nav-item:hover {
            background: rgba(88, 164, 207, 0.2);
            color: #ffffff;
        }

        .admin-nav-item.active {
            color: #58a4cf;
            background: rgba(88, 164, 207, 0.24);
            border-left-color: #58a4cf;
        }

        .admin-sidebar-footer {
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 8px;
            border-top: 1px solid rgba(191, 223, 255, 0.28);
        }

        .admin-user-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .admin-avatar {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            border: 1px solid rgba(191, 223, 255, 0.28);
            display: grid;
            place-items: center;
            background: rgba(255, 255, 255, 0.08);
            color: #58a4cf;
            font-weight: 700;
            font-size: 0.78rem;
        }

        .admin-user-name {
            margin: 0;
            color: #ffffff;
            font-size: 0.81rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-user-role {
            margin: 2px 0 0;
            color: rgba(191, 223, 255, 0.82);
            font-size: 0.7rem;
        }

        .admin-icon-btn {
            border: 1px solid rgba(191, 223, 255, 0.28);
            background: rgba(255, 255, 255, 0.05);
            color: rgba(191, 223, 255, 0.82);
            border-radius: 8px;
            width: 32px;
            height: 32px;
            display: grid;
            place-items: center;
            cursor: pointer;
            transition: color 180ms ease, border-color 180ms ease, background 180ms ease;
        }

        .admin-icon-btn:hover {
            color: #58a4cf;
            border-color: rgba(88, 164, 207, 0.48);
            background: rgba(88, 164, 207, 0.18);
        }

        .admin-main {
            margin-left: 272px;
            min-height: 100dvh;
            padding: 20px;
            background: #f8fafb;
        }

        .admin-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .admin-content h1 {
            margin: 0;
            font-size: 1.55rem;
            font-weight: 700;
            letter-spacing: -0.01em;
            color: #111827;
        }

        .admin-content h2 {
            margin: 0 0 12px;
            font-size: 1.1rem;
            font-weight: 700;
            color: #111827;
        }

        .admin-content .header-row {
            margin-bottom: 10px;
        }

        .admin-content .top-nav {
            display: none;
        }

        .admin-content .card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            padding: 18px;
            transition: transform 180ms ease, box-shadow 220ms ease, border-color 220ms ease;
        }

        .admin-content .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(27, 108, 168, 0.16);
            border-color: rgba(27, 108, 168, 0.42);
        }

        .admin-content .btn {
            border: 1px solid #e5e7eb;
            background: #ffffff;
            color: #111827;
            border-radius: 10px;
            transition: all 180ms ease;
        }

        .admin-content .btn:hover {
            border-color: rgba(27, 108, 168, 0.35);
            background: #f4faff;
            color: #1b6ca8;
        }

        .admin-content .btn-primary {
            background: #1b6ca8;
            border-color: #1b6ca8;
            color: #ffffff;
        }

        .admin-content .btn-primary:hover {
            background: #0a2342;
            border-color: #0a2342;
            color: #ffffff;
        }

        .admin-content .btn-secondary {
            background: #0a2342;
            border-color: #0a2342;
            color: #ffffff;
        }

        .admin-content .btn-secondary:hover {
            background: #1b6ca8;
            border-color: #1b6ca8;
            color: #ffffff;
        }

        .admin-content .btn-danger {
            background: #ff6b35;
            border-color: #ff6b35;
            color: #ffffff;
        }

        .admin-content .btn-danger:hover {
            background: #e45525;
            border-color: #e45525;
        }

        .admin-content input,
        .admin-content textarea,
        .admin-content select {
            border: 1px solid #dbe7f3;
            background: #ffffff;
            color: #111827;
            border-radius: 10px;
            transition: border-color 180ms ease, box-shadow 180ms ease;
        }

        .admin-content input:focus,
        .admin-content textarea:focus,
        .admin-content select:focus {
            border-color: rgba(27, 108, 168, 0.55);
            box-shadow: 0 0 0 3px rgba(88, 164, 207, 0.18);
            outline: none;
        }

        .admin-content table {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            background: #ffffff;
            margin-top: 12px;
        }

        .admin-content th,
        .admin-content td {
            border-bottom: 1px solid #e5e7eb;
        }

        .admin-content th {
            background: #f8fafb;
            color: #6b7280;
            font-weight: 600;
        }

        .admin-content tbody tr:hover {
            background: rgba(88, 164, 207, 0.12);
        }

        .admin-content .alert-error {
            background: rgba(255, 107, 53, 0.13);
            border: 1px solid rgba(255, 107, 53, 0.42);
            color: #7a2d11;
            border-radius: 10px;
        }

        @keyframes toastSlideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @media (max-width: 768px) {
            #toastContainer,
            .toast-container,
            [id*="toast"] {
                position: fixed !important;
                top: 16px !important;
                right: 16px !important;
                left: auto !important;
                bottom: auto !important;
                width: calc(100% - 80px) !important;
                max-width: 320px !important;
                z-index: 9999 !important;
            }

            .toast,
            [class*="toast"] {
                animation: toastSlideDown 250ms ease forwards;
            }
        }

        @media (max-width: 960px) {
            .admin-nav-toggle {
                display: inline-flex;
            }

            .admin-nav-overlay {
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

            .admin-shell.is-nav-open .admin-nav-overlay {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
                transition: opacity 180ms ease;
            }

            .admin-shell.is-nav-open .admin-nav-toggle {
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
            }

            .admin-sidebar {
                width: min(82vw, 280px);
                z-index: 1400;
                transform: translateX(calc(-100% - 18px));
                transition: transform 200ms ease;
                box-shadow: 0 18px 42px rgba(10, 35, 66, 0.28);
                overflow-y: auto;
                border-radius: 0 16px 16px 0;
            }

            .admin-shell.is-nav-open .admin-sidebar {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
                padding: 68px 12px 12px;
            }

            .admin-side-nav {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @stack('styles')
</head>
@php
    $useAdminShell = auth()->check() && auth()->user()->canAccessBackoffice() && !request()->routeIs('admin.dashboard') && !request()->routeIs('admin.analytics.*');
    $adminName = $useAdminShell ? (auth()->user()->name ?? 'Admin') : '';
    $adminRoleLabel = $useAdminShell ? auth()->user()->roleLabel() : '';
    $adminInitials = $useAdminShell
        ? (collect(explode(' ', trim($adminName)))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('') ?: 'AD')
        : '';
    $flashMessage = session('status') ?? session('success');
    $isParticipantsRoute = request()->routeIs('participants.index')
        || request()->routeIs('events.participants.*')
        || request()->routeIs('participants.submissions.*')
        || request()->routeIs('participants.evaluations.*')
        || request()->routeIs('participants.digital-id.*');
    $isEventsRoute = request()->routeIs('events.*') && ! $isParticipantsRoute;
    $routeEvent = request()->route('event');
    $currentEvent = $routeEvent instanceof \App\Models\Event ? $routeEvent : null;
    $fallbackEvent = $currentEvent ?? \App\Models\Event::query()->orderByDesc('start_date')->first();
    $participantsNavUrl = $fallbackEvent
        ? \App\Support\PreviewAuth::appendToUrl(route('events.participants.index', $fallbackEvent, false), $previewAuthQuery)
        : \App\Support\PreviewAuth::appendToUrl(route('participants.index', [], false), $previewAuthQuery);
    $suppressGlobalAlertsForParticipantsModal = request()->routeIs('events.participants.index')
        && (session()->has('participant_registered') || $errors->has('name') || $errors->has('email') || $errors->has('registration'));
@endphp
<body class="{{ $useAdminShell ? 'admin-shell-theme' : '' }}">
@if ($useAdminShell)
    <div class="admin-shell">
        <button type="button" class="admin-nav-toggle" aria-label="Open navigation" aria-controls="adminSidebar" aria-expanded="false">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18"></path><path d="M3 12h18"></path><path d="M3 18h18"></path></svg>
        </button>
        <div class="admin-nav-overlay" aria-hidden="true"></div>
        <aside class="admin-sidebar" id="adminSidebar">
            <a href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.dashboard', [], false), $previewAuthQuery) }}" class="admin-brand">
                <span class="admin-brand-event">Even</span><span class="admin-brand-flow">ture</span>
            </a>

            <nav class="admin-side-nav" aria-label="Main navigation">
                <a href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.dashboard', [], false), $previewAuthQuery) }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12.5 12 4l9 8.5"></path><path d="M5 10.8V20h14v-9.2"></path></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ \App\Support\PreviewAuth::appendToUrl(route('events.index', [], false), $previewAuthQuery) }}" class="admin-nav-item {{ $isEventsRoute ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5" width="16" height="15" rx="2"></rect><path d="M8 3v4M16 3v4M4 10h16"></path></svg>
                    <span>Events</span>
                </a>
                <a href="{{ $participantsNavUrl }}" class="admin-nav-item {{ $isParticipantsRoute ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>Participants</span>
                </a>
                <a href="{{ \App\Support\PreviewAuth::appendToUrl(route('guests.index', [], false), $previewAuthQuery) }}" class="admin-nav-item {{ request()->routeIs('guests.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"></path><path d="M2 13h20"></path></svg>
                    <span>Guests</span>
                </a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.users.index', [], false), $previewAuthQuery) }}" class="admin-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M20 8v6"></path><path d="M17 11h6"></path></svg>
                        <span>Event Staff</span>
                    </a>
                    <a href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.event-evaluation-forms.index', [], false), $previewAuthQuery) }}" class="admin-nav-item {{ request()->routeIs('admin.event-evaluation-forms.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 19.5V4.5a1.5 1.5 0 0 1 1.5-1.5h9.38a1.5 1.5 0 0 1 1.06.44l2.62 2.62a1.5 1.5 0 0 1 .44 1.06V19.5A1.5 1.5 0 0 1 17.5 21h-12A1.5 1.5 0 0 1 4 19.5Z"></path><path d="M8 9h8M8 13h8M8 17h5"></path></svg>
                        <span>Evaluation Forms</span>
                    </a>
                @endif
                <a href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.digital-id.verify.form', [], false), $previewAuthQuery) }}" class="admin-nav-item {{ request()->routeIs('admin.digital-id.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"></rect><path d="M7 8h10M7 12h5M7 16h3"></path></svg>
                    <span>Verify Digital ID</span>
                </a>
                <a href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.analytics.events', [], false), $previewAuthQuery) }}" class="admin-nav-item {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 19h16"></path><path d="M7 16V9"></path><path d="M12 16V5"></path><path d="M17 16v-3"></path></svg>
                    <span>Analytics</span>
                </a>
            </nav>

            <div class="admin-sidebar-footer">
                <div class="admin-user-meta">
                    <div class="admin-avatar">{{ $adminInitials }}</div>
                    <div style="min-width:0;">
                        <p class="admin-user-name">{{ $adminName }}</p>
                        <p class="admin-user-role">{{ $adminRoleLabel }}</p>
                    </div>
                </div>
                <form action="{{ route('logout', [], false) }}" method="POST" class="js-logout-confirm-form">
                    @csrf
                    <button type="submit" class="admin-icon-btn" aria-label="Logout">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </button>
                </form>
            </div>
        </aside>

        <main class="admin-main">
            <div class="admin-content">
                @if ($flashMessage && ! $suppressGlobalAlertsForParticipantsModal)
                    <div class="alert-success" role="status" aria-live="polite">
                        <div class="alert-success-main">
                            <svg class="alert-success-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 6 9 17l-5-5"></path>
                            </svg>
                            <span class="alert-success-text">{{ $flashMessage }}</span>
                        </div>
                        <button type="button" class="alert-dismiss" aria-label="Dismiss confirmation" onclick="this.closest('.alert-success').remove()">&times;</button>
                    </div>
                @endif

                @if ($errors->any() && ! $suppressGlobalAlertsForParticipantsModal)
                    <div class="alert-error">
                        <strong>Please fix the following:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
@else
    <div class="container">
        <div class="top-nav">
            <a class="btn" href="{{ route('participants.public.events', [], false) }}">Participant Events</a>
            @auth
                @if (auth()->user()->canAccessBackoffice())
                    <a class="btn" href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.dashboard', [], false), $previewAuthQuery) }}">Dashboard</a>
                    <a class="btn" href="{{ \App\Support\PreviewAuth::appendToUrl(route('events.index', [], false), $previewAuthQuery) }}">Events</a>
                    <a class="btn" href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.analytics.events', [], false), $previewAuthQuery) }}">Event Analytics</a>
                    @if (auth()->user()->isAdmin())
                        <a class="btn" href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.users.index', [], false), $previewAuthQuery) }}">Event Staff</a>
                        <a class="btn" href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.evaluation-questions.index', [], false), $previewAuthQuery) }}">Evaluation Forms</a>
                    @endif
                    <a class="btn" href="{{ \App\Support\PreviewAuth::appendToUrl(route('admin.digital-id.verify.form', [], false), $previewAuthQuery) }}">Verify Digital ID</a>
                @endif
            @endauth
            @auth
                <form action="{{ route('logout', [], false) }}" method="POST" class="js-logout-confirm-form" style="display:inline;">
                    @csrf
                    <button class="btn" type="submit">Logout ({{ auth()->user()->role }})</button>
                </form>
            @else
                <!-- Login button removed for participants -->
            @endauth
        </div>

        @if ($flashMessage && ! $suppressGlobalAlertsForParticipantsModal)
            <div class="alert-success" role="status" aria-live="polite">
                <div class="alert-success-main">
                    <svg class="alert-success-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20 6 9 17l-5-5"></path>
                    </svg>
                    <span class="alert-success-text">{{ $flashMessage }}</span>
                </div>
                <button type="button" class="alert-dismiss" aria-label="Dismiss confirmation" onclick="this.closest('.alert-success').remove()">&times;</button>
            </div>
        @endif

        @if ($errors->any() && ! $suppressGlobalAlertsForParticipantsModal)
            <div class="alert-error">
                <strong>Please fix the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
@endif
<div id="logoutConfirmModal" class="confirm-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="logoutConfirmTitle">
    <div class="confirm-panel">
        <h2 id="logoutConfirmTitle" class="confirm-title">Confirm Logout</h2>
        <p class="confirm-body">Are you sure you want to log out of your account?</p>
        <div class="confirm-actions">
            <button type="button" class="btn-confirm-cancel" id="logoutConfirmCancel">Cancel</button>
            <button type="button" class="btn-confirm-submit" id="logoutConfirmSubmit">Yes, Logout</button>
        </div>
    </div>
</div>
<script>
    (function () {
        var previewAuth = @json($previewAuthQuery);
        var adminShell = document.querySelector('.admin-shell');
        var adminNavToggle = document.querySelector('.admin-nav-toggle');
        var adminNavOverlay = document.querySelector('.admin-nav-overlay');
        var adminNavLinks = document.querySelectorAll('.admin-nav-item');

        var closeAdminNav = function () {
            if (!adminShell || !adminNavToggle) {
                return;
            }

            adminShell.classList.remove('is-nav-open');
            adminNavToggle.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        };

        var openAdminNav = function () {
            if (!adminShell || !adminNavToggle) {
                return;
            }

            adminShell.classList.add('is-nav-open');
            adminNavToggle.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        };

        if (adminNavToggle && adminShell) {
            adminNavToggle.addEventListener('click', function () {
                if (adminShell.classList.contains('is-nav-open')) {
                    closeAdminNav();
                    return;
                }

                openAdminNav();
            });
        }

        if (adminNavOverlay) {
            adminNavOverlay.addEventListener('click', closeAdminNav);
        }

        adminNavLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth <= 960) {
                    closeAdminNav();
                }
            });
        });

        if (!previewAuth || Object.keys(previewAuth).length === 0) {
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeAdminNav();
                }
            });

            return;
        }

        var applyPreviewParamsToUrl = function (rawUrl) {
            if (!rawUrl || rawUrl.startsWith('#') || rawUrl.startsWith('javascript:') || rawUrl.startsWith('mailto:') || rawUrl.startsWith('tel:')) {
                return rawUrl;
            }

            try {
                var url = new URL(rawUrl, window.location.origin);

                if (url.origin !== window.location.origin) {
                    return rawUrl;
                }

                Object.keys(previewAuth).forEach(function (key) {
                    url.searchParams.set(key, previewAuth[key]);
                });

                return url.pathname + url.search + url.hash;
            } catch (error) {
                return rawUrl;
            }
        };

        var upsertHiddenInput = function (form, name, value) {
            var selector = 'input[type="hidden"][name="' + name + '"]';
            var input = form.querySelector(selector);

            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                form.appendChild(input);
            }

            input.value = value;
        };

        var patchLink = function (link) {
            var href = link.getAttribute('href');

            if (!href) {
                return;
            }

            link.setAttribute('href', applyPreviewParamsToUrl(href));
        };

        var patchForm = function (form) {
            var action = form.getAttribute('action') || window.location.pathname + window.location.search;
            var method = (form.getAttribute('method') || 'GET').toUpperCase();

            if (method === 'GET') {
                form.setAttribute('action', applyPreviewParamsToUrl(action));
            }

            Object.keys(previewAuth).forEach(function (key) {
                upsertHiddenInput(form, key, previewAuth[key]);
            });
        };

        document.querySelectorAll('a[href]').forEach(patchLink);
        document.querySelectorAll('form').forEach(patchForm);

        document.addEventListener('submit', function (event) {
            var form = event.target;

            if (form instanceof HTMLFormElement) {
                patchForm(form);
            }
        }, true);

        document.addEventListener('click', function (event) {
            var link = event.target.closest('a[href]');

            if (link) {
                patchLink(link);
            }
        }, true);

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeAdminNav();
            }
        });
    })();

    (function () {
        var logoutModal = document.getElementById('logoutConfirmModal');
        var logoutConfirmCancel = document.getElementById('logoutConfirmCancel');
        var logoutConfirmSubmit = document.getElementById('logoutConfirmSubmit');
        var pendingLogoutForm = null;

        var openLogoutModal = function (form) {
            pendingLogoutForm = form;

            if (!logoutModal) {
                return;
            }

            logoutModal.classList.add('is-visible');
            logoutModal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        };

        var closeLogoutModal = function () {
            if (!logoutModal) {
                return;
            }

            logoutModal.classList.remove('is-visible');
            logoutModal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            pendingLogoutForm = null;
        };

        document.querySelectorAll('.js-logout-confirm-form').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                openLogoutModal(form);
            });
        });

        if (logoutConfirmCancel) {
            logoutConfirmCancel.addEventListener('click', closeLogoutModal);
        }

        if (logoutModal) {
            logoutModal.addEventListener('click', function (event) {
                if (event.target === logoutModal) {
                    closeLogoutModal();
                }
            });
        }

        if (logoutConfirmSubmit) {
            logoutConfirmSubmit.addEventListener('click', function () {
                if (pendingLogoutForm) {
                    pendingLogoutForm.submit();
                }
            });
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && logoutModal && logoutModal.classList.contains('is-visible')) {
                closeLogoutModal();
            }
        });
    })();
</script>
@stack('scripts')
</body>
</html>
