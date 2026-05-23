@extends('layouts.app')

@section('title', 'Guests - Eventure')

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

        .guests-filter-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .guests-filter-wrap label {
            margin: 0;
            color: var(--color-midnight);
            font-family: 'Sora', sans-serif;
            font-size: 13px;
        }

        .guests-filter-select {
            border: 1px solid var(--color-sky);
            border-radius: 8px;
            padding: 9px 36px 9px 14px;
            color: var(--color-midnight);
            background-color: #ffffff;
            font-size: 14px;
            min-width: 280px;
            font-family: 'Sora', sans-serif;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235BA4CF' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 14px 14px;
        }

        .guests-filter-select:focus {
            border-color: var(--color-steel-blue);
            outline: none;
            box-shadow: 0 0 0 3px rgba(91, 164, 207, 0.18);
        }

        .guests-info-strip {
            background: var(--color-ice-white);
            border-left: 3px solid var(--color-steel-blue);
            color: var(--color-ocean);
            padding: 8px 14px;
            border-radius: 0 6px 6px 0;
            font-size: 13px;
            margin-bottom: 16px;
            font-family: 'Sora', sans-serif;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
        }

        .guests-clear-link {
            color: var(--color-coral);
            margin-left: 12px;
            text-decoration: none;
            font-weight: 600;
        }

        .guests-empty-state {
            text-align: center;
            padding: 34px 16px;
            color: var(--color-ocean);
            font-family: 'Sora', sans-serif;
        }

        .guests-empty-state svg {
            width: 56px;
            height: 56px;
            color: var(--color-steel-blue);
            margin-bottom: 10px;
        }

        .guests-empty-state h3 {
            margin: 0 0 8px;
            color: var(--color-midnight);
            font-size: 17px;
            font-weight: 600;
        }

        .guests-empty-state p {
            margin: 0;
            color: var(--color-ocean);
            font-size: 13px;
        }

        .guests-table-wrap {
            overflow-x: auto;
            border: 1px solid var(--color-sky);
            border-radius: 10px;
            background: #ffffff;
        }

        .guests-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
            background: #ffffff;
        }

        .guests-table thead th {
            background: var(--color-midnight);
            color: #ffffff;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.05em;
            font-weight: 600;
            padding: 14px 16px;
        }

        .guests-table tbody td {
            border-bottom: 1px solid var(--color-sky);
            padding: 12px 14px;
            color: var(--color-midnight);
            font-size: 13px;
            font-family: 'Sora', sans-serif;
            vertical-align: middle;
        }

        .guest-status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            text-transform: capitalize;
            letter-spacing: 0.01em;
            white-space: nowrap;
        }

        .guest-status-badge.status-pending {
            background: #fef7c3;
            color: #92400e;
            border: 1px solid #f7dd72;
        }

        .guest-status-badge.status-approved {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .guest-status-badge.status-denied {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .guests-table tbody tr:last-child td {
            border-bottom: none;
        }

        .guests-table tbody tr:hover {
            background: var(--color-ice-white);
        }

        .guest-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .guest-actions-row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
        }

        .guest-actions-row .btn-action,
        .guest-actions-row .btn-delete-icon,
        .guest-actions-row form {
            margin: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 32px;
        }

        .guest-actions-row .btn-delete-icon {
            width: 32px;
            height: 32px;
            padding: 0;
        }

        .guest-actions-row .btn-delete-icon svg {
            width: 14px;
            height: 14px;
        }

        @media (max-width: 640px) {
            .guest-actions-row {
                gap: 6px;
            }

            .guest-actions-row .btn-action {
                padding: 4px 8px !important;
                font-size: 12px !important;
                line-height: 1 !important;
                border-radius: 6px !important;
                min-width: 0 !important;
            }

            .guest-actions-row .btn-delete-icon {
                width: 30px !important;
                height: 30px !important;
            }

            .guest-actions-row .btn-delete-icon svg {
                width: 14px !important;
                height: 14px !important;
            }

            .guest-actions-row .btn-action,
            .guest-actions-row .btn-delete-icon {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
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
            border: 1px solid transparent;
            background: #ffffff;
            color: var(--color-midnight);
            font-family: 'Sora', sans-serif;
        }

        .btn-view { border-color: var(--color-steel-blue); color: var(--color-steel-blue); }
        .btn-view:hover { background: var(--color-sky); color: var(--color-ocean); }
        .btn-approve {
            border-color: #16a34a;
            color: #14532d;
            background: #dcfce7;
        }
        .btn-approve:hover {
            background: #bbf7d0;
            border-color: #15803d;
            color: #14532d;
        }
        .btn-edit { border-color: var(--color-ocean); color: var(--color-ocean); }
        .btn-edit:hover { background: var(--color-ice-white); }
        .btn-evals { border-color: var(--color-midnight); color: #ffffff; background: var(--color-midnight); }
        .btn-evals:hover { background: var(--color-ocean); border-color: var(--color-ocean); }

        .btn-delete {
            border-color: #fca5a5;
            color: #b91c1c;
            background: #fff1f2;
        }

        .btn-delete:hover {
            border-color: #ef4444;
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-delete-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-delete-icon svg {
            width: 14px;
            height: 14px;
        }

        .guests-empty-table {
            text-align: center;
            color: var(--color-ocean);
            font-size: 14px;
            padding: 32px;
            font-family: 'Sora', sans-serif;
        }

        .guest-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(10, 35, 66, 0.38);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 90;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 180ms ease, visibility 0s linear 180ms;
        }

        .guest-modal-overlay.is-visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition: opacity 180ms ease;
        }

        .guest-modal {
            width: min(760px, 100%);
            background: #ffffff;
            border: 1px solid var(--color-sky);
            border-radius: 12px;
            box-shadow: 0 18px 45px rgba(10, 35, 66, 0.22);
            padding: 18px;
            transform: translateY(10px) scale(0.98);
            opacity: 0;
            transition: transform 220ms ease, opacity 220ms ease;
        }

        .guest-modal-overlay.is-visible .guest-modal {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .guest-modal-title {
            margin: 0;
            color: var(--color-midnight);
            font-family: 'Sora', sans-serif;
            font-size: 1.35rem;
            font-weight: 700;
        }

        .guest-modal-close {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid var(--color-sky);
            background: #ffffff;
            color: var(--color-ocean);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-family: 'Sora', sans-serif;
            font-size: 18px;
            line-height: 1;
            transition: background-color 160ms ease, color 160ms ease, border-color 160ms ease;
        }

        .guest-modal-close:hover {
            background: var(--color-ice-white);
            border-color: var(--color-steel-blue);
            color: var(--color-midnight);
        }

        .guest-modal-form {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 14px;
            align-items: start;
            margin-top: 10px;
            min-width: 0;
        }

        .guest-modal-form .field {
            margin: 0;
            min-width: 0;
        }

        .guest-modal-form .field-full,
        .guest-modal-form-actions {
            grid-column: 1 / -1;
        }

        .guest-modal-form label {
            margin: 0 0 6px;
            color: var(--color-midnight);
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            display: inline-block;
        }

        .guest-modal-form input {
            width: 100%;
            min-height: 40px;
            padding: 8px 10px;
            border: 1px solid #cfe0ef;
            border-radius: 8px;
            background: #ffffff;
            color: var(--color-midnight);
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            box-sizing: border-box;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: none;
        }

        .guest-modal-form input[type="file"] {
            padding: 8px 10px;
            border-radius: 8px;
            background: #ffffff;
            color: var(--color-midnight);
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            border: 1px solid #cfe0ef;
        }

        .guest-modal-form input[type="file"]::file-selector-button,
        .guest-modal-form input[type="file"]::-webkit-file-upload-button {
            margin-right: 8px;
            padding: 8px 12px;
            border: 1px solid #cfe0ef;
            border-radius: 8px;
            background: #f8fafc;
            color: var(--color-midnight);
            cursor: pointer;
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            transition: background-color 160ms ease, border-color 160ms ease;
        }

        .guest-modal-form input[type="file"]:hover::file-selector-button,
        .guest-modal-form input[type="file"]:hover::-webkit-file-upload-button {
            background: #eef4fb;
            border-color: var(--color-steel-blue);
        }

        .guest-modal-form select {
            width: 100%;
            min-height: 40px;
            padding: 8px 10px;
            border: 1px solid #cfe0ef;
            border-radius: 8px;
            background: #ffffff;
            color: var(--color-midnight);
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            box-sizing: border-box;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235BA4CF' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 14px 14px;
        }

        .guest-modal-form textarea {
            min-height: 92px;
            resize: vertical;
            font-family: 'Sora', sans-serif;
        }

        .guest-modal-form input:focus,
        .guest-modal-form select:focus,
        .guest-modal-form textarea:focus {
            border-color: var(--color-steel-blue);
            outline: none;
            box-shadow: 0 0 0 3px rgba(91, 164, 207, 0.18);
        }

        .guest-modal-form-readonly {
            width: 100%;
            min-height: 40px;
            padding: 8px 10px;
            border: 1px solid #cfe0ef;
            border-radius: 8px;
            background: #f5f5f5;
            color: var(--color-midnight);
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            display: flex;
            align-items: center;
            cursor: not-allowed;
            box-sizing: border-box;
        }

        .guest-modal-form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .guest-modal-form-actions .btn-cancel,
        .guest-modal-form-actions .btn-cancel-edit {
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

        .guest-modal-form-actions .btn-cancel:hover,
        .guest-modal-form-actions .btn-cancel-edit:hover {
            background: var(--color-ice-white);
            border-color: var(--color-steel-blue);
            color: var(--color-ocean);
        }

        #openGuestModal,
        .guest-modal-form-actions .btn,
        .guest-modal-form-actions .btn-primary {
            font-family: 'Sora', sans-serif;
        }

        #openGuestModal:disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
            background: #ccc !important;
            color: #666 !important;
            border-color: #ccc !important;
        }

        .guest-modal-error {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            border-radius: 10px;
            padding: 10px 14px;
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .guest-modal-form input:disabled,
        .guest-modal-form input[readonly],
        .guest-modal-form select:disabled,
        .guest-modal-form textarea:disabled,
        .guest-modal-form textarea[readonly] {
            background: #f5f5f5;
            color: var(--color-ocean);
            cursor: not-allowed;
        }

        .guest-modal-form.edit-mode {
            grid-template-columns: minmax(0, 1fr);
        }

        .guest-modal-form.edit-mode .guest-modal-form-actions #cancelEditBtn {
            order: -1;
        }

        .guest-modal-form.edit-mode .guest-modal-form-actions #saveGuestBtn {
            order: 0;
        }

        @media (max-width: 720px) {
            .guest-modal-form {
                grid-template-columns: minmax(0, 1fr);
            }

            .guest-modal-form .field,
            .guest-modal-form .field-full,
            .guest-modal-form-actions {
                grid-column: auto;
            }
        }

        .guest-modal-view-field {
            margin-bottom: 16px;
        }

        .guest-modal-view-field label {
            display: block;
            margin: 0 0 6px;
            color: var(--color-midnight);
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            font-weight: 600;
        }

        .guest-modal-view-field-value {
            padding: 8px 10px;
            background: var(--color-ice-white);
            border: 1px solid var(--color-sky);
            border-radius: 8px;
            color: var(--color-midnight);
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            max-width: 100%;
            display: block;
            width: 100%;
            word-break: break-word;
        }

        /* Delete Confirmation Modal */
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

        .btn-approve-confirm {
            border-color: #16a34a;
            background: #16a34a;
        }

        .btn-approve-confirm:hover {
            background: #15803d;
            border-color: #15803d;
        }

        .modal-floating-label {
            position: relative;
            min-width: 280px;
            max-width: min(420px, calc(100% - 40px));
            border-radius: 10px;
            padding: 10px 14px;
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            line-height: 1.4;
            box-shadow: 0 10px 24px rgba(10, 35, 66, 0.2);
            color: inherit;
            opacity: 0;
            transform: translateY(-6px);
            animation: toast-in 180ms ease-out forwards;
            transition: opacity 220ms ease, transform 220ms ease;
        }

        .modal-floating-label.is-hiding {
            opacity: 0;
            transform: translateY(-6px);
        }

        .modal-toast {
            position: relative;
            margin: 0;
            pointer-events: none;
            opacity: 1;
        }

        .modal-floating-success {
            color: #065f46;
            background: #ecfdf5;
            border: 1px solid #34d399;
        }

        .modal-floating-error {
            color: #991b1b;
            background: #fef2f2;
            border: 1px solid #fca5a5;
        }

        .modal-floating-info {
            color: #0c4a6e;
            background: #e0f2fe;
            border: 1px solid #7dd3fc;
        }

        .modal-floating-warning {
            color: #92400e;
            background: #fef3c7;
            border: 1px solid #fde68a;
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

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .guest-actions {
                gap: 4px;
                flex-wrap: wrap;
            }

            .btn-action {
                padding: 4px 6px;
                font-size: 10px;
                border-radius: 5px;
            }

            .btn-delete-icon {
                width: 32px;
                height: 32px;
            }

            .btn-delete-icon svg {
                width: 16px;
                height: 16px;
            }

            .guest-modal {
                width: min(90vw, 100%);
                padding: 16px;
                border-radius: 10px;
            }

            .guest-modal-title {
                font-size: 1.1rem;
            }

            .guest-modal-form {
                grid-template-columns: 1fr;
                gap: 10px 12px;
            }

            .guest-modal-form-actions {
                flex-direction: column;
                gap: 8px;
            }

            .guest-modal-form-actions .btn {
                width: 100%;
            }

            .guest-modal-view-field {
                margin-bottom: 12px;
            }

            .guest-modal-view-field label {
                font-size: 12px;
            }

            .guest-modal-view-field-value {
                font-size: 13px;
                padding: 6px 8px;
                min-height: auto;
            }

            .guests-table thead th {
                padding: 10px 8px;
                font-size: 11px;
            }

            .guests-table tbody td {
                padding: 10px 8px;
                font-size: 12px;
            }

            .guests-filter-select {
                min-width: 200px;
                font-size: 13px;
                padding: 8px 32px 8px 12px;
            }

            .guests-info-strip {
                font-size: 12px;
                padding: 6px 10px;
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 480px) {
            .btn-action {
                padding: 3px 5px;
                font-size: 9px;
            }

            .guest-modal {
                padding: 12px;
            }

            .guest-modal-title {
                font-size: 1rem;
            }

            .guest-modal-form input,
            .guest-modal-form select,
            .guest-modal-form textarea {
                min-height: 36px;
                font-size: 13px;
                font-family: 'Sora', sans-serif;
            }

            .guest-actions {
                gap: 3px;
            }

            .guests-filter-select {
                min-width: 100%;
                width: 100%;
            }

            .guests-page-header {
                flex-direction: column-reverse;
            }
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="header-row">
            <h1>Guests</h1>
            <div class="actions">
                @if ($selectedEvent)
                    <button class="btn btn-primary" type="button" id="openGuestModal" {{ $selectedEvent->hasEnded() || (auth()->user()->hasRole('event_staff') && $selectedEvent->created_by !== auth()->id()) ? 'disabled' : '' }}>Add Guest</button>
                @endif
            </div>
        </div>

        <form class="guests-filter-wrap" action="{{ route('guests.index') }}" method="GET">
            <label for="event_id">Filter by event</label>
            <select id="event_id" name="event_id" class="guests-filter-select" onchange="this.form.submit()">
                <option value="">-- Select an Event --</option>
                @foreach ($events as $item)
                    <option value="{{ $item->id }}" {{ (string) request('event_id') === (string) $item->id ? 'selected' : '' }}>
                        {{ $item->title }} - {{ $item->getStatus() }} ({{ $item->dateRangeLabel() }})
                    </option>
                @endforeach
            </select>
        </form>

        @if (isset($guestModuleReady) && ! $guestModuleReady)
            <div class="guests-info-strip" style="border-left-color: var(--color-coral);">
                <span><strong>Guest module setup required:</strong> Guests tables are not available yet.</span>
                <span>Run migrations in your app runtime and refresh this page.</span>
            </div>
        @endif

        @if ($selectedEvent)
            <div class="guests-info-strip">
                <span>
                    Viewing guests for: <strong>{{ $selectedEvent->title }}</strong>
                    <a class="guests-clear-link" href="{{ route('guests.index') }}">x Clear</a>
                </span>
                <span>{{ number_format($guests->total()) }} guest(s)</span>
            </div>
        @endif

        @if (!request('event_id'))
            <div class="guests-empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"></path>
                    <path d="M2 13h20"></path>
                </svg>
                <h3>Select an event to view guests</h3>
                <p>Use the dropdown above to choose an event.</p>
            </div>
        @else
            <div class="guests-table-wrap">
                <table class="guests-table">
                    <thead>
                    <tr>
                        <th style="width:18%">Name</th>
                        <th style="width:20%">Email</th>
                        <th style="width:12%">Role</th>
                        <th style="width:14%">Registered At</th>
                        <th style="width:10%">Status</th>
                        <th style="width:14%">Submission</th>
                        <th style="width:12%">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($guests->count() === 0)
                        <tr>
                            <td colspan="5" class="guests-empty-table">No guests registered for this event yet.</td>
                        </tr>
                    @else
                        @foreach ($guests as $guest)
                            <tr data-guest-id="{{ $guest->id }}">
                                <td>{{ $guest->name }}</td>
                                <td>{{ $guest->email }}</td>
                                <td>{{ ucfirst($guest->role) }}</td>
                                <td>{{ \Carbon\Carbon::parse($guest->created_at)->format('M d, Y h:i A') }}</td>
                                <td><span class="guest-status-badge status-{{ $guest->status ?? 'approved' }}">{{ ucfirst($guest->status ?? 'approved') }}</span></td>
                                <td>
                                    @if ($guest->conference_paper_path)
                                        @php $fileName = pathinfo($guest->conference_paper_path, PATHINFO_BASENAME); @endphp
                                        <a href="{{ route('guests.download-paper', $guest) }}" target="_blank">
                                            {{ $fileName }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    <div class="guest-actions">
                                    <div class="guest-actions-row">
                                        <button class="btn-action btn-view" type="button" data-guest-id="{{ $guest->id }}" data-guest-name="{{ $guest->name }}" data-guest-email="{{ $guest->email }}" data-guest-role="{{ $guest->role }}" data-guest-bio="{{ $guest->bio }}" data-guest-event="{{ $guest->event->title }}" data-guest-event-id="{{ $guest->event_id }}" onclick="openViewGuestModal(this)">View</button>
                                        <form id="delete-form-guest-{{ $guest->id }}" action="{{ route('guests.destroy', $guest) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            @if (!auth()->user()->hasRole('event_staff') || $selectedEvent->created_by === auth()->id())
                                                <button type="button" class="btn-action btn-delete btn-delete-icon js-delete-trigger" aria-label="Delete guest" title="Delete guest" data-form-id="delete-form-guest-{{ $guest->id }}" data-guest-name="{{ $guest->name }}">
                                                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                        <path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m-8 0 1 12a1 1 0 0 0 1 .9h6a1 1 0 0 0 1-.9l1-12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                    @if (($guest->status ?? 'approved') === 'pending' && (auth()->user()->hasRole('admin') || (auth()->user()->hasRole('event_staff') && $selectedEvent->created_by === auth()->id())))
                                        <div class="guest-actions-row">
                                            <form class="js-approve-form" action="{{ route('events.guests.approve', [$selectedEvent, $guest]) }}" method="POST" style="display:inline;" data-guest-name="{{ $guest->name }}">
                                                @csrf
                                                <button class="btn-action btn-approve" type="submit">Approve</button>
                                            </form>
                                            <form class="js-deny-form" action="{{ route('events.guests.deny', [$selectedEvent, $guest]) }}" method="POST" style="display:inline;" data-guest-name="{{ $guest->name }}">
                                                @csrf
                                                <button class="btn-action btn-delete" type="submit">Deny</button>
                                            </form>
                                        </div>
                                    @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>

            @if ($guests->count() > 0)
                <div class="pagination">{{ $guests->links() }}</div>
            @endif
        @endif
    </div>

    <div class="guest-modal-overlay" id="addGuestModal" aria-hidden="true">
        <div class="guest-modal" role="dialog" aria-modal="true" aria-labelledby="addGuestModalTitle">
            @php
                $guestModalErrorMessage = $errors->first('event_id')
                    ?: $errors->first('name')
                    ?: $errors->first('email')
                    ?: $errors->first('role')
                    ?: $errors->first('bio')
                    ?: $errors->first('guests');
            @endphp

            @if ($guestModalErrorMessage)
                <div class="guest-modal-error">{{ $guestModalErrorMessage }}</div>
            @endif

            <div class="header-row">
                <h2 id="addGuestModalTitle" class="guest-modal-title">Add Guest</h2>
                <button class="guest-modal-close" type="button" id="closeGuestModal" aria-label="Close add guest modal">&times;</button>
            </div>

            <form class="guest-modal-form" action="{{ route('guests.store') }}" method="POST">
                @csrf

                <div class="field field-full">
                    <label for="modal_guest_event_id">Event</label>
                    <select id="modal_guest_event_id" name="event_id" required>
                        <option value="">Select event</option>
                        @foreach ($upcomingEvents as $item)
                            <option value="{{ $item->id }}" {{ (string) old('event_id', request('event_id')) === (string) $item->id ? 'selected' : '' }}>
                                {{ $item->title }} ({{ $item->dateRangeLabel() }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="modal_guest_name">Guest Name</label>
                    <input id="modal_guest_name" name="name" type="text" value="{{ old('name') }}" required>
                </div>

                <div class="field">
                    <label for="modal_guest_email">Email</label>
                    <input id="modal_guest_email" name="email" type="email" value="{{ old('email') }}" required>
                </div>

                <div class="field field-full">
                    <label for="modal_guest_role">Role</label>
                    <div class="guest-modal-form-readonly" id="guestRoleDisplay">Exhibitor</div>
                    <input id="guestRoleInput" name="role" type="hidden" value="Exhibitor">
                </div>

                <div class="field field-full">
                    <label for="modal_guest_bio">Bio / Notes</label>
                    <textarea id="modal_guest_bio" name="bio" rows="4">{{ old('bio') }}</textarea>
                </div>

                <div class="guest-modal-form-actions">
                    <button class="btn btn-cancel" type="button" id="cancelGuestModal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Save Guest</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Approve Confirmation Modal -->
    <div id="approveConfirmModal" class="delete-confirm-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="approveConfirmTitle">
        <div class="delete-confirm-panel">
            <h2 id="approveConfirmTitle" class="delete-confirm-title">Approve Guest</h2>
            <p class="delete-confirm-body">Are you sure you want to approve <span id="approveConfirmName" class="delete-confirm-name"></span>? An email with the Digital ID will be sent.</p>
            <div class="delete-confirm-actions">
                <button type="button" class="btn-delete-cancel" id="approveConfirmCancel">Cancel</button>
                <button type="button" class="btn-delete-confirm btn-approve-confirm" id="approveConfirmSubmit">Yes, Approve</button>
            </div>
        </div>
    </div>

    <!-- Deny Confirmation Modal -->
    <div id="denyConfirmModal" class="delete-confirm-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="denyConfirmTitle">
        <div class="delete-confirm-panel">
            <h2 id="denyConfirmTitle" class="delete-confirm-title">Deny Guest</h2>
            <p class="delete-confirm-body">Are you sure you want to deny and remove <span id="denyConfirmName" class="delete-confirm-name"></span>?</p>
            <div class="delete-confirm-actions">
                <button type="button" class="btn-delete-cancel" id="denyConfirmCancel">Cancel</button>
                <button type="button" class="btn-delete-confirm" id="denyConfirmSubmit">Yes, Deny</button>
            </div>
        </div>
    </div>

    <!-- Toast container -->
    <div id="toastContainer" style="position:fixed; top:16px; right:16px; z-index:1400"></div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteConfirmModal" class="delete-confirm-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="deleteConfirmTitle">
        <div class="delete-confirm-panel">
            <h2 id="deleteConfirmTitle" class="delete-confirm-title">Delete Guest</h2>
            <p class="delete-confirm-body">
                Are you sure you want to delete <span id="deleteConfirmName" class="delete-confirm-name"></span>?
                This action <strong>cannot be undone</strong> and will permanently remove this guest.
            </p>
            <div class="delete-confirm-actions">
                <button type="button" class="btn-delete-cancel" id="deleteConfirmCancel">Cancel</button>
                <button type="button" class="btn-delete-confirm" id="deleteConfirmSubmit">Yes, Delete</button>
            </div>
        </div>
    </div>

    <!-- View/Edit Guest Modal -->
    <div class="guest-modal-overlay" id="viewGuestModal" aria-hidden="true">
        <div class="guest-modal" role="dialog" aria-modal="true" aria-labelledby="viewGuestModalTitle">
            <div class="header-row">
                <h2 id="viewGuestModalTitle" class="guest-modal-title">Guest Details</h2>
                <button class="guest-modal-close" type="button" id="closeViewGuestModal" aria-label="Close guest modal">&times;</button>
            </div>

            <form id="editGuestForm" class="guest-modal-form">
                @csrf
                @method('PUT')

                <div class="field field-full">
                    <label for="view_guest_event_id">Event</label>
                    <select id="view_guest_event_id" name="event_id" required disabled>
                        @foreach ($events as $item)
                            <option value="{{ $item->id }}">{{ $item->title }} ({{ $item->dateRangeLabel() }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="field field-full">
                    <label for="view_guest_name">Guest Name</label>
                    <input id="view_guest_name" name="name" type="text" required>
                </div>

                <div class="field field-full">
                    <label for="view_guest_email">Email</label>
                    <input id="view_guest_email" name="email" type="email" required>
                </div>

                <div class="field field-full">
                    <label for="view_guest_role">Role</label>
                    <input id="view_guest_role" name="role" type="text" required readonly>
                </div>

                <div class="field field-full">
                    <label for="view_guest_bio">Bio / Notes</label>
                    <textarea id="view_guest_bio" name="bio" rows="4"></textarea>
                </div>

                <div class="guest-modal-form-actions" style="margin-top: 16px;">
                    <button class="btn btn-cancel" type="button" id="closeViewGuestModalBtn">Close</button>
                    <button class="btn btn-primary" type="button" id="editGuestBtn" style="display: none;">Edit Guest</button>
                    <button class="btn btn-primary" type="submit" id="saveGuestBtn" style="display: none;">Save Changes</button>
                    <button class="btn btn-cancel btn-cancel-edit" type="button" id="cancelEditBtn" style="display: none;">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            function showToast(message, type = 'info', duration = 4000) {
                var toastContainer = document.getElementById('toastContainer');
                if (!toastContainer) return;
                var toast = document.createElement('div');
                toast.className = 'modal-floating-label modal-floating-' + type + ' modal-toast';
                toast.textContent = message;
                toastContainer.appendChild(toast);
                setTimeout(function () {
                    toast.classList.add('is-visible');
                }, 10);

                window.setTimeout(function () {
                    if (!toast) return;
                    toast.classList.add('is-hiding');
                    window.setTimeout(function () {
                        if (toast && toast.parentNode) {
                            toast.parentNode.removeChild(toast);
                        }
                    }, 220);
                }, duration);
            }

            var approveModal = document.getElementById('approveConfirmModal');
            var approveNameEl = document.getElementById('approveConfirmName');
            var approveCancel = document.getElementById('approveConfirmCancel');
            var approveSubmit = document.getElementById('approveConfirmSubmit');
            var pendingApproveForm = null;

            var denyModal = document.getElementById('denyConfirmModal');
            var denyNameEl = document.getElementById('denyConfirmName');
            var denyCancel = document.getElementById('denyConfirmCancel');
            var denySubmit = document.getElementById('denyConfirmSubmit');
            var pendingDenyForm = null;

            document.querySelectorAll('form.js-approve-form').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    pendingApproveForm = form;
                    if (approveNameEl) approveNameEl.innerText = form.dataset.guestName || 'this guest';
                    if (approveModal) {
                        approveModal.classList.add('is-visible');
                        approveModal.setAttribute('aria-hidden', 'false');
                        document.body.style.overflow = 'hidden';
                    }
                });
            });

            if (approveCancel) approveCancel.addEventListener('click', function () {
                if (!approveModal) return;
                approveModal.classList.remove('is-visible');
                approveModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                pendingApproveForm = null;
            });

            if (approveModal) {
                approveModal.addEventListener('click', function (e) {
                    if (e.target === approveModal) {
                        approveModal.classList.remove('is-visible');
                        approveModal.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                        pendingApproveForm = null;
                    }
                });
            }

            if (approveSubmit) approveSubmit.addEventListener('click', function () {
                if (!pendingApproveForm) return;
                var url = pendingApproveForm.action;
                var formData = new FormData(pendingApproveForm);
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData,
                    credentials: 'same-origin'
                }).then(function (resp) {
                    return resp.json().then(function (data) { return { status: resp.status, data: data }; });
                }).then(function (result) {
                    if (result.status >= 200 && result.status < 300) {
                        showToast(result.data.message || 'Guest approved and digital ID email sent.', 'success');
                        var row = pendingApproveForm.closest('tr');
                        if (row) {
                            var statusBadge = row.querySelector('.guest-status-badge');
                            if (statusBadge) {
                                statusBadge.textContent = 'Approved';
                                statusBadge.className = 'guest-status-badge status-approved';
                            }

                            var actions = row.querySelector('.guest-actions');
                            if (actions) {
                                var actionRows = actions.querySelectorAll('.guest-actions-row');
                                if (actionRows.length > 1) {
                                    actionRows[actionRows.length - 1].remove();
                                }
                            }
                        }
                    } else {
                        showToast(result.data.message || 'Failed to approve.', 'error');
                    }
                }).catch(function (err) {
                    showToast('Error approving guest.', 'error');
                }).finally(function () {
                    if (approveModal) {
                        approveModal.classList.remove('is-visible');
                        approveModal.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                    }
                    pendingApproveForm = null;
                });
            });

            document.querySelectorAll('form.js-deny-form').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    pendingDenyForm = form;
                    if (denyNameEl) denyNameEl.innerText = form.dataset.guestName || 'this guest';
                    if (denyModal) {
                        denyModal.classList.add('is-visible');
                        denyModal.setAttribute('aria-hidden', 'false');
                        document.body.style.overflow = 'hidden';
                    }
                });
            });

            if (denyCancel) denyCancel.addEventListener('click', function () {
                if (!denyModal) return;
                denyModal.classList.remove('is-visible');
                denyModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                pendingDenyForm = null;
            });

            if (denyModal) {
                denyModal.addEventListener('click', function (e) {
                    if (e.target === denyModal) {
                        denyModal.classList.remove('is-visible');
                        denyModal.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                        pendingDenyForm = null;
                    }
                });
            }

            if (denySubmit) denySubmit.addEventListener('click', function () {
                if (!pendingDenyForm) return;
                var url = pendingDenyForm.action;
                var formData = new FormData(pendingDenyForm);
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData,
                    credentials: 'same-origin'
                }).then(function (resp) {
                    return resp.json().then(function (data) { return { status: resp.status, data: data }; });
                }).then(function (result) {
                    if (result.status >= 200 && result.status < 300) {
                        showToast(result.data.message || 'Guest denied.', 'success');
                        var row = pendingDenyForm.closest('tr');
                        if (row) row.parentNode.removeChild(row);
                    } else {
                        showToast(result.data.message || 'Failed to deny guest.', 'error');
                    }
                }).catch(function (err) {
                    showToast('Error denying guest.', 'error');
                }).finally(function () {
                    if (denyModal) {
                        denyModal.classList.remove('is-visible');
                        denyModal.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                    }
                    pendingDenyForm = null;
                });
            });
        })();
    </script>

@endsection


@push('scripts')
    <script>
        // Event types mapping for role auto-assignment
        const eventTypes = @json($upcomingEvents->pluck('type', 'id'));

        function updateGuestRole(eventId) {
            if (!eventId) {
                document.getElementById('guestRoleDisplay').textContent = 'Exhibitor';
                document.getElementById('guestRoleInput').value = 'Exhibitor';
                return;
            }

            const eventType = eventTypes[eventId];
            const role = eventType === 'conference' ? 'Presenter' : 'Exhibitor';
            document.getElementById('guestRoleDisplay').textContent = role;
            document.getElementById('guestRoleInput').value = role;
        }

        // View Guest Modal Functions
        let currentGuestId = null;

        function openViewGuestModal(button) {
            currentGuestId = button.dataset.guestId;
            const name = button.dataset.guestName;
            const email = button.dataset.guestEmail;
            const role = button.dataset.guestRole;
            const bio = button.dataset.guestBio;
            const event = button.dataset.guestEvent;
            const eventId = button.dataset.guestEventId;

            document.getElementById('view_guest_name').value = name;
            document.getElementById('view_guest_email').value = email;
            document.getElementById('view_guest_role').value = role;
            document.getElementById('view_guest_bio').value = bio || '';
            document.getElementById('view_guest_event_id').value = eventId;

            const form = document.getElementById('editGuestForm');
            form.action = '/guests/' + currentGuestId;

            document.getElementById('viewGuestModal').classList.add('is-visible');
            document.getElementById('viewGuestModal').setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';

            setViewMode();
        }

        function closeViewGuestModal() {
            document.getElementById('viewGuestModal').classList.remove('is-visible');
            document.getElementById('viewGuestModal').setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            currentGuestId = null;
            setViewMode();
        }

        function setViewMode() {
            // Show input fields in readonly state
            document.getElementById('view_guest_name').readOnly = true;
            document.getElementById('view_guest_email').readOnly = true;
            document.getElementById('view_guest_role').readOnly = true;
            document.getElementById('view_guest_bio').readOnly = true;

            document.getElementById('editGuestBtn').style.display = 'inline-block';
            document.getElementById('closeViewGuestModalBtn').style.display = 'inline-block';
            document.getElementById('saveGuestBtn').style.display = 'none';
            document.getElementById('cancelEditBtn').style.display = 'none';

            document.getElementById('viewGuestModalTitle').textContent = 'Guest Details';
            document.getElementById('editGuestForm').classList.remove('edit-mode');
        }

        function setEditMode() {
            // Show input fields in editable state
            document.getElementById('view_guest_name').readOnly = false;
            document.getElementById('view_guest_email').readOnly = false;
            document.getElementById('view_guest_role').readOnly = true;
            document.getElementById('view_guest_bio').readOnly = false;

            document.getElementById('editGuestBtn').style.display = 'none';
            document.getElementById('closeViewGuestModalBtn').style.display = 'none';
            document.getElementById('saveGuestBtn').style.display = 'inline-block';
            document.getElementById('cancelEditBtn').style.display = 'inline-block';

            document.getElementById('viewGuestModalTitle').textContent = 'Edit Guest Details';
            document.getElementById('editGuestForm').classList.add('edit-mode');
        }

        // Modal Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            // View modal close buttons
            const closeViewBtn = document.getElementById('closeViewGuestModal');
            const closeViewModalBtn = document.getElementById('closeViewGuestModalBtn');
            const viewModal = document.getElementById('viewGuestModal');

            if (closeViewBtn) closeViewBtn.addEventListener('click', closeViewGuestModal);
            if (closeViewModalBtn) closeViewModalBtn.addEventListener('click', closeViewGuestModal);
            if (viewModal) {
                viewModal.addEventListener('click', function(e) {
                    if (e.target === viewModal) closeViewGuestModal();
                });
            }

            // Edit button - switch to edit mode
            const editGuestBtn = document.getElementById('editGuestBtn');
            if (editGuestBtn) {
                editGuestBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    setEditMode();
                });
            }

            // Cancel edit button - switch back to view mode
            const cancelEditBtn = document.getElementById('cancelEditBtn');
            if (cancelEditBtn) {
                cancelEditBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    setViewMode();
                });
            }

            // Form submission
            const editForm = document.getElementById('editGuestForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    this.submit();
                });
            }



            // Escape key closes modal
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeViewGuestModal();
                }
            });
        });

        // Delete Confirmation Modal
        (function () {
            var deleteModal = document.getElementById('deleteConfirmModal');
            var deleteConfirmName = document.getElementById('deleteConfirmName');
            var deleteConfirmCancel = document.getElementById('deleteConfirmCancel');
            var deleteConfirmSubmit = document.getElementById('deleteConfirmSubmit');
            var pendingDeleteForm = null;

            var openDeleteModal = function (guestName, formId) {
                pendingDeleteForm = document.getElementById(formId);
                if (!deleteModal || !pendingDeleteForm) return;
                if (deleteConfirmName) deleteConfirmName.innerText = guestName || 'this guest';
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
                var deleteButton = event.target.closest('.js-delete-trigger');
                if (deleteButton) {
                    event.preventDefault();
                    openDeleteModal(deleteButton.dataset.guestName, deleteButton.dataset.formId);
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

        (function () {
            var modal = document.getElementById('addGuestModal');
            var openButton = document.getElementById('openGuestModal');
            var closeButton = document.getElementById('closeGuestModal');
            var cancelButton = document.getElementById('cancelGuestModal');
            var eventSelect = document.getElementById('modal_guest_event_id');

            if (!modal || !openButton) {
                return;
            }

            var openModal = function () {
                modal.classList.add('is-visible');
                modal.setAttribute('aria-hidden', 'false');
                // Set initial role based on pre-selected event
                if (eventSelect && eventSelect.value) {
                    updateGuestRole(eventSelect.value);
                }
            };

            var closeModal = function () {
                modal.classList.remove('is-visible');
                modal.setAttribute('aria-hidden', 'true');
            };

            openButton.addEventListener('click', openModal);

            if (closeButton) {
                closeButton.addEventListener('click', closeModal);
            }

            if (cancelButton) {
                cancelButton.addEventListener('click', closeModal);
            }

            // Listen for event dropdown changes
            if (eventSelect) {
                eventSelect.addEventListener('change', function () {
                    updateGuestRole(this.value);
                });
            }

            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeModal();
                }
            });

            if ({{ ($errors->hasAny(['event_id', 'name', 'email', 'role', 'bio', 'guests']) || request()->boolean('open_add_guest')) ? 'true' : 'false' }}) {
                openModal();
            }
        })();
    </script>
@endpush
