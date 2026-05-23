@extends('layouts.app')

@section('title', 'Events - Eventure')

@push('styles')
    <style>
        :root {
            --color-ice-white: #E8F4FD;
            --color-sky: #BFDFFF;
            --color-steel-blue: #5BA4CF;
            --color-ocean: #1B6CA8;
            --color-midnight: #0A2342;
            --color-coral: #FF6B35;
        }

        .events-page {
            background: var(--color-ice-white);
            border: 1px solid var(--color-sky);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(10,35,66,0.08);
        }

        .events-title {
            font-weight: 600;
            color: var(--color-midnight);
        }

        .btn-create-event {
            background: var(--color-coral);
            color: #ffffff;
            border: 1px solid var(--color-coral);
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
        }

        .btn-create-event:hover {
            background: #e45525;
            border-color: #e45525;
            color: #ffffff;
        }

        .filters-row {
            display: flex;
            gap: 12px;
            align-items: center;
            margin: 16px 0 20px;
            flex-wrap: wrap;
        }

        .overview-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin: 16px 0;
        }

        .overview-card {
            background: #ffffff;
            border: 1px solid var(--color-sky);
            border-radius: 10px;
            padding: 14px 16px;
            box-shadow: 0 2px 8px rgba(10,35,66,0.06);
        }

        /* Ensure single-column fields (date/datetime) in modals are block and full width */
        .single-column label,
        .single-column input,
        .single-column select,
        .single-column textarea {
            display: block !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .input-error {
            color: #c0392b;
            font-size: 13px;
            margin-top: 6px;
            display: none;
        }

        .overview-label {
            color: var(--color-ocean);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 600;
        }

        .overview-value {
            margin-top: 6px;
            color: var(--color-midnight);
            font-size: 30px;
            line-height: 1;
            font-weight: 700;
        }

        .search-field {
            position: relative;
            width: 250px;
        }

        .search-field .filter-input {
            width: 100%;
            padding-left: 34px;
        }

        .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 14px;
            height: 14px;
            color: var(--color-steel-blue);
            pointer-events: none;
        }

        .filter-input,
        .filter-select {
            border: 1px solid var(--color-sky);
            border-radius: 8px;
            padding: 6px 12px;
            background: #ffffff;
            color: var(--color-midnight);
            font-size: 13px;
            font-family: 'Sora', sans-serif;
            box-sizing: border-box;
        }

        .filter-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding-right: 34px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235BA4CF' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 14px 14px;
        }

        .filter-input:focus,
        .filter-select:focus {
            outline: none;
            border-color: var(--color-steel-blue);
            box-shadow: 0 0 0 3px rgba(91, 164, 207, 0.18);
        }

        .showing-text {
            margin-left: auto;
            color: var(--color-ocean);
            font-size: 13px;
        }

        .filters-row .btn {
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            padding: 6px 12px;
            line-height: 1.2;
        }

        .events-table-wrap {
            overflow-x: auto;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        /* Mobile-only: prevent date inputs in modal from overflowing container */
        @media (max-width: 980px) {
            .single-column input[type="date"],
            .single-column input[type="datetime-local"] {
                max-width: 100% !important;
                box-sizing: border-box !important;
            }

            .field.single-column {
                overflow: hidden;
                padding: 0;
            }
        }
        .events-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
            border: none;
            background: #ffffff;
        }

        .events-table thead th {
            background: var(--color-midnight);
            color: #ffffff;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.05em;
            font-weight: 600;
            padding: 14px 16px;
        }

        .events-table tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--color-sky);
            color: var(--color-midnight);
            vertical-align: middle;
            font-size: 13px;
        }

        .events-table tbody tr:hover {
            background: var(--color-ice-white);
        }

        .cell-muted {
            color: var(--color-ocean);
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 500;
            padding: 2px 8px;
        }

        .badge-type-student {
            background: var(--color-sky);
            color: var(--color-ocean);
        }

        .badge-type-conference {
            background: var(--color-midnight);
            color: var(--color-sky);
        }

        .badge-attendance-face-to-face {
            background: #cfe8ff;
            color: #0A2342;
        }

        .badge-attendance-virtual {
            background: #ede4ff;
            color: #5f3dc4;
        }

        .badge-attendance-both {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-status-open {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-status-closed {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-status-pending {
            background: #fef9c3;
            color: #854d0e;
        }

        .event-actions {
            display: flex;
            gap: 6px;
            flex-wrap: nowrap;
            align-items: center;
        }

        .btn-action {
            border-radius: 6px;
            padding: 5px 8px;
            font-size: 11px;
            line-height: 1.2;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            cursor: pointer;
            transition: all 160ms ease;
            font-family: 'Sora', sans-serif;
        }

        .btn-view {
            border: 1px solid var(--color-steel-blue);
            color: var(--color-steel-blue);
            background: #ffffff;
        }

        .btn-view:hover {
            background: var(--color-sky);
            color: var(--color-ocean);
        }

        .btn-edit {
            border: 1px solid var(--color-ocean);
            color: var(--color-ocean);
            background: #ffffff;
        }

        .btn-edit:hover {
            background: var(--color-ice-white);
        }

        .btn-participants {
            border: 1px solid var(--color-midnight);
            color: #ffffff;
            background: var(--color-midnight);
        }

        .btn-participants:hover {
            background: var(--color-ocean);
            border-color: var(--color-ocean);
            color: #ffffff;
        }

        .btn-delete-icon {
            border: 1px solid #fca5a5;
            background: #fff1f2;
            color: #b91c1c;
            padding: 0;
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            cursor: pointer;
            transition: background 160ms ease, border-color 160ms ease, color 160ms ease;
        }

        .btn-delete-icon:hover {
            border-color: #ef4444;
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-delete-icon svg {
            width: 14px;
            height: 14px;
        }

        /* ── Delete Confirmation Modal ─────────────────────────────── */
        .delete-confirm-modal {
            position: fixed;
            inset: 0;
            background: rgba(10, 35, 66, 0.55);
            z-index: 1100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 180ms ease, visibility 0s linear 180ms;
        }

        .delete-confirm-modal.is-visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition: opacity 180ms ease;
        }

        .delete-confirm-panel {
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

        .delete-confirm-modal.is-visible .delete-confirm-panel {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .delete-confirm-title {
            margin: 0 0 8px;
            font-family: 'Sora', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--color-midnight);
        }

        .delete-confirm-body {
            margin: 0 0 22px;
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
        }

        .delete-confirm-name {
            font-weight: 600;
            color: var(--color-midnight);
        }

        .delete-confirm-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-delete-cancel {
            border: 1px solid var(--color-sky);
            background: #ffffff;
            color: var(--color-ocean);
            border-radius: 8px;
            padding: 9px 18px;
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 160ms ease, border-color 160ms ease;
        }

        .btn-delete-cancel:hover {
            background: var(--color-ice-white);
            border-color: var(--color-steel-blue);
        }

        .btn-delete-confirm {
            border: 1px solid #b91c1c;
            background: #b91c1c;
            color: #ffffff;
            border-radius: 8px;
            padding: 9px 18px;
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 160ms ease, border-color 160ms ease;
        }

        .btn-delete-confirm:hover {
            background: #991b1b;
            border-color: #991b1b;
        }

        /* ── Edit Event Modal ─────────────────────────────── */
        .edit-event-modal {
            position: fixed;
            inset: 0;
            background: rgba(10, 35, 66, 0.55);
            z-index: 1100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 180ms ease, visibility 0s linear 180ms;
        }

        .edit-event-modal.is-visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition: opacity 180ms ease;
        }

        .edit-event-panel {
            background: #ffffff;
            border-radius: 16px;
            width: 100%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 8px 40px rgba(10, 35, 66, 0.22);
            transform: translateY(10px) scale(0.98);
            opacity: 0;
            transition: transform 220ms ease, opacity 220ms ease;
            display: flex;
            flex-direction: column;
        }

        .edit-event-modal.is-visible .edit-event-panel {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .edit-event-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 28px 28px 20px;
            border-bottom: 1px solid var(--color-sky);
        }

        .edit-event-title {
            margin: 0;
            font-family: 'Sora', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--color-midnight);
        }

        .edit-event-close {
            background: none;
            border: none;
            padding: 8px;
            cursor: pointer;
            color: var(--color-ocean);
            border-radius: 6px;
            transition: background-color 160ms ease;
        }

        .edit-event-close:hover {
            background: var(--color-ice-white);
        }

        .edit-event-close svg {
            width: 20px;
            height: 20px;
        }

        .edit-event-form {
            padding: 24px 28px 0;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            flex: 1;
        }

        .edit-event-form-grid {
            display: contents;
        }

        .edit-event-form .field {
            margin: 0;
        }

        .edit-event-form label {
            display: block;
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--color-midnight);
        }

        .edit-event-form input,
        .edit-event-form select,
        .edit-event-form textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--color-sky);
            border-radius: 8px;
            background: #ffffff;
            color: var(--color-midnight);
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            transition: border-color 160ms ease, box-shadow 160ms ease;
        }

        .edit-event-form select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235BA4CF' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 14px 14px;
        }

        .edit-event-form input:focus,
        .edit-event-form select:focus,
        .edit-event-form textarea:focus {
            outline: none;
            border-color: var(--color-steel-blue);
            box-shadow: 0 0 0 3px rgba(91, 164, 207, 0.18);
        }

        .edit-event-form input:not([type="file"]),
        .edit-event-form select {
            min-height: 40px;
        }

        .edit-event-form input[type="file"] {
            min-height: 40px;
            padding: 4px 8px;
            cursor: pointer;
        }

        .edit-event-form input[type="file"]::file-selector-button {
            font-family: 'Sora', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #ffffff;
            background: var(--color-ocean);
            border: 1px solid var(--color-ocean);
            border-radius: 6px;
            padding: 0 10px;
            height: 30px;
            margin-right: 10px;
            cursor: pointer;
            transition: background-color 160ms ease, border-color 160ms ease;
        }

        .edit-event-form input[type="file"]::-webkit-file-upload-button {
            font-family: 'Sora', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #ffffff;
            background: var(--color-ocean);
            border: 1px solid var(--color-ocean);
            border-radius: 6px;
            padding: 0 10px;
            height: 30px;
            margin-right: 10px;
            cursor: pointer;
            transition: background-color 160ms ease, border-color 160ms ease;
        }

        .edit-event-form input[type="file"]:hover::file-selector-button,
        .edit-event-form input[type="file"]:hover::-webkit-file-upload-button {
            background: var(--color-midnight);
            border-color: var(--color-midnight);
        }

        .edit-event-form textarea {
            min-height: 80px;
            resize: vertical;
        }

        .edit-event-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 16px 0 28px 0;
            border-top: 1px solid var(--color-sky);
            margin-top: 12px;
            grid-column: 1 / -1;
        }

        .btn-edit-cancel {
            border: 1px solid var(--color-sky);
            background: #ffffff;
            color: var(--color-ocean);
            border-radius: 8px;
            padding: 9px 18px;
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 160ms ease, border-color 160ms ease;
        }

        .btn-edit-cancel:hover {
            background: var(--color-ice-white);
            border-color: var(--color-steel-blue);
        }

        .btn-edit-save {
            border: 1px solid var(--color-ocean);
            background: var(--color-ocean);
            color: #ffffff;
            border-radius: 8px;
            padding: 8px 16px;
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 160ms ease, border-color 160ms ease;
        }

        .btn-edit-save:hover {
            background: var(--color-midnight);
            border-color: var(--color-midnight);
        }

        @media (max-width: 980px) {
            .edit-event-panel {
                max-width: 100%;
                margin: 0 8px;
                border-radius: 12px;
            }

            .edit-event-header {
                padding: 16px;
            }

            .edit-event-form {
                grid-template-columns: 1fr;
                padding: 16px;
                gap: 12px;
            }

            .edit-event-form input,
            .edit-event-form select,
            .edit-event-form textarea {
                font-size: 15px;
                padding: 10px 12px;
                min-height: 44px;
            }

            .edit-event-form input[type="date"],
            .edit-event-form input[type="datetime-local"] {
                min-height: 48px;
                padding: 8px 10px;
            }

            .edit-event-actions {
                padding: 12px;
                gap: 8px;
            }
        }

        .empty-state {
            text-align: center;
            padding: 30px 16px;
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            color: var(--color-steel-blue);
            margin-bottom: 12px;
        }

        .empty-state h3 {
            margin: 0 0 8px;
            color: var(--color-midnight);
            font-size: 18px;
            font-weight: 600;
        }

        .empty-state p {
            margin: 0 0 16px;
            color: var(--color-ocean);
            font-size: 14px;
        }

        .events-pagination {
            margin-top: 14px;
        }

        .events-pagination nav div,
        .events-pagination nav span,
        .events-pagination nav a {
            font-size: 13px;
        }

        .events-pagination nav a,
        .events-pagination nav span[aria-current="page"] {
            border-radius: 6px !important;
        }

        .events-pagination nav a {
            color: var(--color-ocean) !important;
            border-color: var(--color-sky) !important;
        }

        .events-pagination nav span[aria-current="page"] span {
            background: var(--color-ocean) !important;
            color: #ffffff !important;
            border-color: var(--color-ocean) !important;
            border-radius: 6px !important;
        }

        @media (max-width: 960px) {
            .overview-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 8px;
                align-items: stretch;
            }

            .overview-card {
                padding: 12px 10px;
                text-align: center;
                min-width: 0;
                box-sizing: border-box;
            }

            .overview-label {
                font-size: 0.66rem;
                line-height: 1.25;
            }

            .overview-value {
                margin-top: 8px;
                font-size: 1.2rem;
            }

            .filters-row {
                align-items: stretch;
            }

            .search-field {
                width: 100%;
                flex: 1 1 100%;
                max-width: 100%;
                min-width: 0;
            }

            .filter-input {
                width: 100%;
            }

            .search-field .filter-input {
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
            }
        }

        @media (max-width: 560px) {
            .overview-grid {
                gap: 6px;
            }

            .overview-card {
                padding: 10px 8px;
            }

            .overview-label {
                font-size: 0.6rem;
            }

            .overview-value {
                font-size: 1.02rem;
            }

            .search-field {
                flex-basis: 100%;
            }
        }

        .modal-floating-label {
            position: fixed;
            top: 20px;
            right: 20px;
            max-width: min(420px, calc(100% - 40px));
            border-radius: 10px;
            padding: 10px 14px;
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            line-height: 1.4;
            box-shadow: 0 10px 24px rgba(10, 35, 66, 0.2);
            z-index: 1505;
            animation: toast-in 180ms ease-out;
            transition: opacity 220ms ease, transform 220ms ease;
        }

        .modal-floating-error {
            background: #f8d7da;
            color: #842029;
        }

        .modal-floating-warning {
            background: #fff3cd;
            color: #664d03;
        }

        .modal-floating-success {
            background: #d1e7dd;
            color: #0f5132;
        }

        .modal-floating-info {
            background: #cff4fc;
            color: #055160;
        }

        .modal-toast.is-hiding {
            opacity: 0;
            transform: translateY(-10px);
        }

        @keyframes toast-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@section('content')
    <div class="events-page">
        <div class="header-row">
            <h1 class="events-title">Events</h1>
            @auth
                @if (auth()->user()->canAccessBackoffice())
                    <a class="btn btn-create-event" href="{{ route('events.create') }}">Create Event</a>
                @endif
            @endauth
        </div>

        <div class="overview-grid">
            <div class="overview-card">
                <div class="overview-label">Total Events</div>
                <div class="overview-value">{{ number_format($overview['total_events'] ?? 0) }}</div>
            </div>
            <div class="overview-card">
                <div class="overview-label">School Events</div>
                <div class="overview-value">{{ number_format($overview['student_events'] ?? 0) }}</div>
            </div>
            <div class="overview-card">
                <div class="overview-label">Conference</div>
                <div class="overview-value">{{ number_format($overview['conference_events'] ?? 0) }}</div>
            </div>
        </div>

        <form class="filters-row" action="{{ route('events.index') }}" method="GET">
            <div class="search-field">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input
                    class="filter-input"
                    type="text"
                    name="search"
                    placeholder="Search events..."
                    value="{{ request('search') }}"
                >
            </div>

            <select class="filter-select" name="registration">
                <option value="">All Statuses</option>
                <option value="open" {{ request('registration') === 'open' ? 'selected' : '' }}>Open</option>
                <option value="closed" {{ request('registration') === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>

            <select class="filter-select" name="type">
                <option value="">All Types</option>
                <option value="school_event" {{ request('type') === 'school_event' ? 'selected' : '' }}>School Event</option>
                <option value="conference" {{ request('type') === 'conference' ? 'selected' : '' }}>Conference</option>
            </select>

            <button class="btn btn-primary" type="button" id="filtersApplyBtn">Apply</button>

            <span class="showing-text" id="showingCount" data-total="{{ $events->count() }}">Showing {{ $events->count() }} event(s)</span>
        </form>

        @if ($events->count() === 0)
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="4" width="18" height="17" rx="2"></rect>
                    <path d="M8 2v4M16 2v4M3 10h18"></path>
                    <path d="M9 15l6-6"></path>
                    <path d="M15 15l-6-6"></path>
                </svg>
                <h3>No events found</h3>
                <p>Try adjusting your filters or create a new event.</p>
                @auth
                    @if (auth()->user()->canAccessBackoffice())
                        <a class="btn btn-create-event" href="{{ route('events.create') }}">Create Event</a>
                    @endif
                @endauth
            </div>
        @else
            <div class="events-table-wrap">
                <table class="events-table">
                    <thead>
                    <tr>
                        <th style="width:28%">Title</th>
                        <th style="width:12%">Type</th>
                        <th style="width:12%">Attendance</th>
                        <th style="width:16%">Date</th>
                        <th style="width:12%">Registration</th>
                        <th style="width:12%">Location</th>
                        <th style="width:8%">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($events as $event)
                        @php
                            $registrationStatus = $event->isRegistrationOpen() ? 'Open' : 'Closed';
                            $typeLabel = $event->type === 'conference' ? 'Conference' : 'School Event';
                        @endphp
                        <tr data-event-title="{{ strtolower($event->title) }}" data-event-type="{{ $event->type }}" data-event-registration="{{ $event->isRegistrationOpen() ? 'open' : 'closed' }}">
                            <td>{{ $event->title }}</td>
                            <td>
                                <span class="badge-pill {{ $event->type === 'conference' ? 'badge-type-conference' : 'badge-type-student' }}">
                                    {{ $typeLabel }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-pill {{ $event->attendanceTypeBadgeClass() }}">
                                    {{ $event->attendanceTypeLabel() }}
                                </span>
                            </td>
                            <td class="cell-muted">{{ $event->dateRangeLabel() }}</td>
                            <td>
                                <span class="badge-pill {{ $registrationStatus === 'Open' ? 'badge-status-open' : ($registrationStatus === 'Closed' ? 'badge-status-closed' : 'badge-status-pending') }}">
                                    {{ $registrationStatus }}
                                </span>
                            </td>
                            <td class="cell-muted">{{ $event->location }}</td>
                            <td>
                                <div class="event-actions">
                                    @auth
                                        @if (auth()->user()->canAccessBackoffice())
                                            <a class="btn-action btn-view" href="{{ route('events.show', $event) }}">View</a>
                                            @if (!auth()->user()->hasRole('event_staff') || $event->created_by === auth()->id())
                                                <button
                                                    type="button"
                                                    class="btn-action btn-edit js-event-edit-trigger"
                                                    data-event-id="{{ $event->id }}"
                                                    data-event-title="{{ $event->title }}"
                                                    data-event-type="{{ $event->type }}"
                                                    data-event-title-field="{{ $event->event_title }}"
                                                    data-conference-title="{{ $event->conference_title }}"
                                                    data-theme="{{ $event->theme }}"
                                                    data-keywords="{{ is_array($event->keywords) ? implode(', ', $event->keywords) : '' }}"
                                                    data-description="{{ $event->description }}"
                                                    data-attendance-type="{{ $event->attendance_type ?? 'face_to_face' }}"
                                                    data-start-date="{{ $event->start_date?->format('Y-m-d') }}"
                                                    data-end-date="{{ $event->end_date?->format('Y-m-d') }}"
                                                    data-start-registration="{{ $event->start_registration?->format('Y-m-d\\TH:i') }}"
                                                    data-end-registration="{{ $event->end_registration?->format('Y-m-d\\TH:i') }}"
                                                    data-location="{{ $event->location }}"
                                                    data-poster-path="{{ $event->poster_path }}"
                                                    data-template-file-path="{{ $event->template_file_path }}"
                                                >Edit</button>
                                            @endif
                                            @if (!auth()->user()->hasRole('event_staff') || $event->created_by === auth()->id())
                                                <a class="btn-action btn-participants" href="{{ route('events.participants.index', $event) }}?event_id={{ $event->id }}">Participants</a>
                                            @endif
                                            @if (!auth()->user()->hasRole('event_staff') || $event->created_by === auth()->id())
                                                <form id="delete-event-form-{{ $event->id }}" action="{{ route('events.destroy', $event) }}" method="POST" style="display:none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button
                                                    type="button"
                                                    class="btn-delete-icon js-event-delete-trigger"
                                                    aria-label="Delete event"
                                                    title="Delete event"
                                                    data-form-id="delete-event-form-{{ $event->id }}"
                                                    data-event-title="{{ $event->title }}"
                                                >
                                                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                        <path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m-8 0 1 12a1 1 0 0 0 1 .9h6a1 1 0 0 0 1-.9l1-12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        @else
                                            <a class="btn btn-primary" href="{{ route('events.participants.create', $event) }}">Register</a>
                                        @endif
                                    @else
                                        <a class="btn btn-primary" href="{{ route('events.participants.create', $event) }}">Register</a>
                                    @endauth
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    <tr id="liveSearchEmpty" style="display: none;">
                        <td colspan="7" class="cell-muted" style="text-align: center;">No matching events on this page.</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="events-pagination">
                {{ $events->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteConfirmModal" class="delete-confirm-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="deleteConfirmTitle">
        <div class="delete-confirm-panel">
            <h2 id="deleteConfirmTitle" class="delete-confirm-title">Delete Event</h2>
            <p class="delete-confirm-body">
                Are you sure you want to delete <span id="deleteConfirmName" class="delete-confirm-name"></span>?
                This action <strong>cannot be undone</strong> and will permanently remove this event and all associated data.
            </p>
            <div class="delete-confirm-actions">
                <button type="button" class="btn-delete-cancel" id="deleteConfirmCancel">Cancel</button>
                <button type="button" class="btn-delete-confirm" id="deleteConfirmSubmit">Yes, Delete</button>
            </div>
        </div>
    </div>

    {{-- Edit Event Modal --}}
    <div id="editEventModal" class="edit-event-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="editEventModalTitle">
        <div class="edit-event-panel">
            <div class="edit-event-header">
                <h2 id="editEventModalTitle" class="edit-event-title">Edit Event</h2>
                <button type="button" class="edit-event-close" id="editEventClose" aria-label="Close edit modal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <form id="editEventForm" class="edit-event-form" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="editEventId" name="event_id">

                <div class="edit-event-form-grid">
                    <div class="field">
                        <label for="editType">Event Type</label>
                        <select id="editType" name="type">
                            <option value="school_event">School Event</option>
                            <option value="conference">Conference</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="editAttendanceType">Type of Attendance</label>
                        <select id="editAttendanceType" name="attendance_type">
                            <option value="face_to_face">Face-to-face</option>
                            <option value="virtual">Virtual</option>
                            <option value="both">Both</option>
                        </select>
                    </div>

                    <div class="field" id="edit-event-title-field">
                        <label for="editEventTitle">School Event Title</label>
                        <input id="editEventTitle" name="event_title" type="text">
                    </div>

                    <div class="field" id="edit-conference-title-field">
                        <label for="editConferenceTitle">Conference Title</label>
                        <input id="editConferenceTitle" name="conference_title" type="text">
                    </div>

                    <div class="field" id="edit-conference-theme-field">
                        <label for="editTheme">Theme</label>
                        <input id="editTheme" name="theme" type="text">
                    </div>

                    <div class="field" id="edit-conference-keywords-field">
                        <label for="editKeywords">Keywords (comma-separated)</label>
                        <input id="editKeywords" name="keywords" type="text" placeholder="AI, Mechatronics, Robotics">
                    </div>

                    <div class="field" id="edit-standard-description-field">
                        <label for="editDescription">Description</label>
                        <textarea id="editDescription" name="description" rows="3"></textarea>
                    </div>

                    <div class="field single-column span-2">
                        <label for="editStartDate">Start Date</label>
                        <input id="editStartDate" name="start_date" type="date" required>
                        <div class="input-error" id="editStartDate_error">Please select a future date</div>
                    </div>

                    <div class="field single-column span-2">
                        <label for="editEndDate">End Date</label>
                        <input id="editEndDate" name="end_date" type="date" required>
                        <div class="input-error" id="editEndDate_error">Please select a future date</div>
                    </div>

                    <div class="field single-column span-2">
                        <label for="editStartRegistration">Start Registration</label>
                        <input id="editStartRegistration" name="start_registration" type="datetime-local" required>
                        <div class="input-error" id="editStartRegistration_error">Please select a future date</div>
                    </div>

                    <div class="field single-column span-2">
                        <label for="editEndRegistration">End Registration</label>
                        <input id="editEndRegistration" name="end_registration" type="datetime-local" required>
                        <div class="input-error" id="editEndRegistration_error">Please select a future date</div>
                    </div>

                    <div class="field">
                        <label for="editLocation">Location</label>
                        <input id="editLocation" name="location" type="text" required>
                    </div>

                    <div class="field" id="edit-poster-field">
                        <label for="editPoster">Event Poster</label>
                        <input id="editPoster" name="poster" type="file" accept="image/*">
                        <div id="currentPosterContainer" style="margin-top: 8px;"></div>
                    </div>

                    <div class="field" id="edit-template-file-field">
                        <label for="editTemplateFile">Conference Template (DOC/PDF)</label>
                        <input id="editTemplateFile" name="template_file" type="file" accept=".pdf,.doc,.docx">
                        <div id="currentTemplateContainer" style="margin-top: 8px;"></div>
                    </div>
                </div>

                <div class="edit-event-actions">
                    <button type="button" class="btn-edit-cancel btn-cancel" id="editEventCancel">Cancel</button>
                    <button type="submit" class="btn-edit-save">Update Event</button>
                </div>
            </form>
        </div>
    </div>

    <div id="toastContainer"></div>
@endsection

@push('scripts')
    <script>
        function showToast(message, type = 'info', duration = 4000) {
            const toastContainer = document.getElementById('toastContainer');
            if (!toastContainer) return;

            const toast = document.createElement('div');
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

        (function () {
            const searchInput = document.querySelector('input[name="search"]');
            const tableRows = Array.from(document.querySelectorAll('.events-table tbody tr[data-event-title]'));
            const liveSearchEmpty = document.getElementById('liveSearchEmpty');
            const showingCount = document.getElementById('showingCount');
            const registrationSelect = document.querySelector('select[name="registration"]');
            const typeSelect = document.querySelector('select[name="type"]');
            const applyBtn = document.getElementById('filtersApplyBtn');

            if (!searchInput || tableRows.length === 0) {
                return;
            }

            // Applied filters are only set when the user clicks Apply; search works live regardless
            let appliedFilters = {
                type: typeSelect ? typeSelect.value : '',
                registration: registrationSelect ? registrationSelect.value : ''
            };

            const normalize = (value) => value.toLowerCase().trim();

            const mapType = (val) => {
                if (!val) return '';
                return val;
            };

            const filterRows = () => {
                const query = normalize(searchInput.value);
                const terms = query === '' ? [] : query.split(/\s+/).filter(Boolean);
                let visibleCount = 0;

                tableRows.forEach((row) => {
                    const title = normalize(row.getAttribute('data-event-title') || '');
                    const rowType = row.getAttribute('data-event-type') || '';
                    const rowRegistration = (row.getAttribute('data-event-registration') || '').toLowerCase();

                    // also include visible type label (e.g. 'School Event' or 'Conference') in search
                    let typeText = '';
                    const badge = row.querySelector('.badge-pill');
                    if (badge && badge.innerText) {
                        typeText = normalize(badge.innerText);
                    } else {
                        // fallback map
                        typeText = normalize(rowType === 'conference' ? 'conference' : 'school event');
                    }

                    const searchable = (title + ' ' + typeText).trim();
                    const matchesSearch = terms.length === 0 || terms.every((term) => searchable.includes(term));

                    const typeFilter = mapType(appliedFilters.type);
                    const matchesType = !typeFilter || typeFilter === rowType;

                    const matchesRegistration = !appliedFilters.registration || appliedFilters.registration === rowRegistration;

                    const isVisible = matchesSearch && matchesType && matchesRegistration;
                    row.style.display = isVisible ? '' : 'none';

                    if (isVisible) visibleCount++;
                });

                if (liveSearchEmpty) liveSearchEmpty.style.display = visibleCount === 0 ? '' : 'none';
                if (showingCount) {
                    const total = Number(showingCount.getAttribute('data-total')) || tableRows.length;
                    showingCount.textContent = 'Showing ' + visibleCount + ' of ' + total + ' event(s)';
                }
            };

            // Live search
            searchInput.addEventListener('input', filterRows);

            // Apply button applies dropdown filters (client-side) without submitting the form
            if (applyBtn) {
                applyBtn.addEventListener('click', function (e) {
                    appliedFilters.type = typeSelect ? typeSelect.value : '';
                    appliedFilters.registration = registrationSelect ? registrationSelect.value : '';
                    filterRows();
                    // update URL query string without reloading so state is visible
                    try {
                        const params = new URLSearchParams(window.location.search);
                        if (appliedFilters.type) params.set('type', appliedFilters.type); else params.delete('type');
                        if (appliedFilters.registration) params.set('registration', appliedFilters.registration); else params.delete('registration');
                        const newUrl = window.location.pathname + '?' + params.toString();
                        window.history.replaceState({}, '', newUrl);
                    } catch (err) {
                        // ignore
                    }
                });
            }

            // Initial filter using server-populated select values
            filterRows();
        })();

        // ── Delete Confirmation Modal ──────────────────────────────────────
        (function () {
            var deleteModal = document.getElementById('deleteConfirmModal');
            var deleteConfirmName = document.getElementById('deleteConfirmName');
            var deleteConfirmCancel = document.getElementById('deleteConfirmCancel');
            var deleteConfirmSubmit = document.getElementById('deleteConfirmSubmit');
            var pendingDeleteForm = null;

            var openDeleteModal = function (eventTitle, formId) {
                pendingDeleteForm = document.getElementById(formId);
                if (!deleteModal || !pendingDeleteForm) return;
                if (deleteConfirmName) deleteConfirmName.innerText = eventTitle || 'this event';
                deleteModal.classList.add('is-visible');
                deleteModal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            };

            var closeDeleteModal = function () {
                if (!deleteModal) return;
                deleteModal.classList.remove('is-visible');
                deleteModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                pendingDeleteForm = null;
            };

            document.body.addEventListener('click', function (event) {
                var deleteButton = event.target.closest('.js-event-delete-trigger');
                if (deleteButton) {
                    event.preventDefault();
                    openDeleteModal(deleteButton.dataset.eventTitle, deleteButton.dataset.formId);
                    return;
                }
            });

            if (deleteConfirmCancel) deleteConfirmCancel.addEventListener('click', closeDeleteModal);

            if (deleteModal) {
                deleteModal.addEventListener('click', function (e) {
                    if (e.target === deleteModal) closeDeleteModal();
                });
            }

            if (deleteConfirmSubmit) {
                deleteConfirmSubmit.addEventListener('click', function () {
                    if (pendingDeleteForm) pendingDeleteForm.submit();
                });
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && deleteModal && deleteModal.classList.contains('is-visible')) {
                    closeDeleteModal();
                }
            });
        })();

        // ── Edit Event Modal ──────────────────────────────────────
        (function () {
            var editModal = document.getElementById('editEventModal');
            var editForm = document.getElementById('editEventForm');
            var editEventId = document.getElementById('editEventId');
            var editType = document.getElementById('editType');
            var editEventTitle = document.getElementById('editEventTitle');
            var editConferenceTitle = document.getElementById('editConferenceTitle');
            var editTheme = document.getElementById('editTheme');
            var editKeywords = document.getElementById('editKeywords');
            var editDescription = document.getElementById('editDescription');
            var editAttendanceType = document.getElementById('editAttendanceType');
            var editStartDate = document.getElementById('editStartDate');
            var editEndDate = document.getElementById('editEndDate');
            var editStartRegistration = document.getElementById('editStartRegistration');
            var editEndRegistration = document.getElementById('editEndRegistration');
            var editLocation = document.getElementById('editLocation');
            var editPoster = document.getElementById('editPoster');
            var editTemplateFile = document.getElementById('editTemplateFile');
            var currentPosterContainer = document.getElementById('currentPosterContainer');
            var currentTemplateContainer = document.getElementById('currentTemplateContainer');
            var editEventClose = document.getElementById('editEventClose');
            var editEventCancel = document.getElementById('editEventCancel');

            // Field visibility elements
            var eventTitleField = document.getElementById('edit-event-title-field');
            var conferenceTitleField = document.getElementById('edit-conference-title-field');
            var conferenceThemeField = document.getElementById('edit-conference-theme-field');
            var conferenceKeywordsField = document.getElementById('edit-conference-keywords-field');
            var standardDescriptionField = document.getElementById('edit-standard-description-field');
            var posterField = document.getElementById('edit-poster-field');
            var templateFileField = document.getElementById('edit-template-file-field');

            var openEditModal = function (eventData) {
                if (!editModal || !editForm) return;

                // Set form action URL
                editForm.action = '{{ url('events') }}/' + eventData.eventId;

                // Populate form fields
                editEventId.value = eventData.eventId;
                editType.value = eventData.eventType;
                editEventTitle.value = eventData.eventTitleField || eventData.eventTitle || '';
                editConferenceTitle.value = eventData.conferenceTitle || '';
                editTheme.value = eventData.theme || '';
                editKeywords.value = eventData.keywords || '';
                editDescription.value = eventData.description || '';
                editAttendanceType.value = eventData.attendanceType || 'face_to_face';
                editStartDate.value = eventData.startDate || '';
                editEndDate.value = eventData.endDate || '';
                editStartRegistration.value = eventData.startRegistration || '';
                editEndRegistration.value = eventData.endRegistration || '';
                editLocation.value = eventData.location || '';

                // Clear file inputs
                editPoster.value = '';
                editTemplateFile.value = '';

                // Show current poster if exists
                if (eventData.posterPath) {
                    currentPosterContainer.innerHTML = '<p style="font-size:12px;margin-bottom:4px;color:#666;">Current Poster:</p><img src="https://sesmcvjwmkphgkzawewn.supabase.co/storage/v1/object/public/event-posters/' + eventData.posterPath + '" style="width:100px;height:100px;object-fit:contain;border:1px solid #ddd;border-radius:4px;">';
                } else {
                    currentPosterContainer.innerHTML = '';
                }

                // Show current template if exists and conference
                if (eventData.templateFilePath && eventData.eventType === 'conference') {
                    var ext = eventData.templateFilePath.split('.').pop();
                    currentTemplateContainer.innerHTML = '<p style="font-size:12px;margin-bottom:4px;color:#666;">Current Template:</p><a href="/events/' + eventData.eventId + '/template/download" style="font-size:12px;color:#2563eb;text-decoration:underline;">📄 Conference-Paper-Template.' + ext + '</a>';
                } else {
                    currentTemplateContainer.innerHTML = '';
                }

                // Show/hide fields based on event type
                toggleFieldsByType();

                // Show modal
                editModal.classList.add('is-visible');
                editModal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            };

            var closeEditModal = function () {
                if (!editModal) return;
                editModal.classList.remove('is-visible');
                editModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                editForm.reset();
                currentPosterContainer.innerHTML = '';
                currentTemplateContainer.innerHTML = '';
            };

            var toggleFieldsByType = function () {
                var isConference = editType.value === 'conference';

                if (eventTitleField) eventTitleField.style.display = isConference ? 'none' : 'block';
                if (standardDescriptionField) standardDescriptionField.style.display = isConference ? 'none' : 'block';

                if (conferenceTitleField) conferenceTitleField.style.display = isConference ? 'block' : 'none';
                if (conferenceThemeField) conferenceThemeField.style.display = isConference ? 'block' : 'none';
                if (conferenceKeywordsField) conferenceKeywordsField.style.display = isConference ? 'block' : 'none';
                if (templateFileField) templateFileField.style.display = isConference ? 'block' : 'none';
            };

            // Event listeners
            document.body.addEventListener('click', function (event) {
                var editButton = event.target.closest('.js-event-edit-trigger');
                if (editButton) {
                    event.preventDefault();
                    var eventData = {
                        eventId: editButton.dataset.eventId,
                        eventTitle: editButton.dataset.eventTitle,
                        eventType: editButton.dataset.eventType,
                        eventTitleField: editButton.dataset.eventTitleField,
                        conferenceTitle: editButton.dataset.conferenceTitle,
                        theme: editButton.dataset.theme,
                        keywords: editButton.dataset.keywords,
                        description: editButton.dataset.description,
                        attendanceType: editButton.dataset.attendanceType,
                        startDate: editButton.dataset.startDate,
                        endDate: editButton.dataset.endDate,
                        startRegistration: editButton.dataset.startRegistration,
                        endRegistration: editButton.dataset.endRegistration,
                        location: editButton.dataset.location,
                        posterPath: editButton.dataset.posterPath,
                        templateFilePath: editButton.dataset.templateFilePath
                    };
                    openEditModal(eventData);
                }
            });

            if (editType) {
                editType.addEventListener('change', toggleFieldsByType);
            }

            if (editAttendanceType) {
                editAttendanceType.addEventListener('change', function () {
                    // no-op for now, but ensures the field is available in the edit flow
                });
            }

            if (editEventClose) {
                editEventClose.addEventListener('click', closeEditModal);
            }

            if (editEventCancel) {
                editEventCancel.addEventListener('click', closeEditModal);
            }

            // Close modal when clicking outside on the modal background
            if (editModal) {
                editModal.addEventListener('click', function (e) {
                    if (e.target === editModal) {
                        closeEditModal();
                    }
                });
            }

            if (editForm) {
                editForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    var formData = new FormData(editForm);
                    var submitBtn = editForm.querySelector('.btn-edit-save');
                    var originalText = submitBtn.textContent;

                    // Show loading state
                    submitBtn.textContent = 'Updating...';
                    submitBtn.disabled = true;

                    fetch(editForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                    .then(function (response) {
                        return response.json().then(function (data) {
                            return { status: response.status, data: data };
                        });
                    })
                    .then(function (result) {
                        if (result.status === 200) {
                            // Success - show toast, close modal, then reload page
                            showToast('Event updated successfully.', 'success', 1800);
                            closeEditModal();
                            window.setTimeout(function () {
                                location.reload();
                            }, 1200);
                        } else {
                            // Error - show message
                            showToast('Error updating event: ' + (result.data.message || 'Unknown error'), 'error');
                        }
                    })
                    .catch(function (error) {
                        console.error('Error:', error);
                        showToast('Error updating event. Please try again.', 'error');
                    })
                    .finally(function () {
                        // Reset button state
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    });
                });
            }

            // Initialize date pickers
            var initializeDatePickers = function() {
                var today = new Date();
                var todayDate = new Date(today.getTime() - today.getTimezoneOffset() * 60000).toISOString().split('T')[0];

                ['editStartDate', 'editEndDate'].forEach(function(id) {
                    var input = document.getElementById(id);
                    if (input) {
                        input.setAttribute('min', todayDate);
                    }
                });
            };
            initializeDatePickers();

            // Date validation disabled for edit modal - allows editing events to any date
            var enforceFutureSelectionForEdit = function() {
                // No validation for edit modal
            };
            enforceFutureSelectionForEdit();

            // Load Flatpickr on mobile to provide a visual minDate in picker UI
            var loadFlatpickrOnMobileForEdit = function() {
                var isSmall = window.matchMedia('(max-width: 980px)').matches;
                var isTouch = ('ontouchstart' in window) || navigator.maxTouchPoints > 0;
                if (!isSmall || !isTouch) return;

                var loadCss = function(href) {
                    return new Promise(function(resolve) {
                        var link = document.createElement('link');
                        link.rel = 'stylesheet';
                        link.href = href;
                        link.onload = resolve;
                        document.head.appendChild(link);
                    });
                };

                var loadScript = function(src) {
                    return new Promise(function(resolve) {
                        var s = document.createElement('script');
                        s.src = src;
                        s.onload = resolve;
                        document.head.appendChild(s);
                    });
                };

                Promise.all([
                    loadCss('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css'),
                    loadScript('https://cdn.jsdelivr.net/npm/flatpickr')
                ]).then(function() {
                    var today = new Date();

                    ['editStartDate','editEndDate'].forEach(function(id) {
                        var el = document.getElementById(id);
                        if (!el) return;
                        flatpickr(el, {
                            dateFormat: 'Y-m-d',
                            minDate: today,
                            disableMobile: true,
                        });
                    });

                    ['editStartRegistration','editEndRegistration'].forEach(function(id) {
                        var el = document.getElementById(id);
                        if (!el) return;
                        flatpickr(el, {
                            enableTime: true,
                            time_24hr: false,
                            dateFormat: "Y-m-d\\TH:i",
                            minDate: today,
                            disableMobile: true,
                            minuteIncrement: 1
                        });
                    });
                }).catch(function() {
                    // fallback handled elsewhere
                });
            };
            loadFlatpickrOnMobileForEdit();

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && editModal && editModal.classList.contains('is-visible')) {
                    closeEditModal();
                }
            });
        })();
    </script>
@endpush
