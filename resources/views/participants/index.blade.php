@extends('layouts.app')

@section('title', 'Participants - Eventure')

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

        .participants-filter-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .participants-filter-wrap label {
            margin: 0;
            color: var(--color-midnight);
            font-family: 'Sora', sans-serif;
            font-size: 13px;
        }

        .participants-filter-select {
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
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235BA4CF' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 14px 14px;
        }

        .participants-filter-select:focus {
            border-color: var(--color-steel-blue);
            outline: none;
            box-shadow: 0 0 0 3px rgba(91, 164, 207, 0.18);
        }

        .participants-filter-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            background: var(--color-ice-white);
            border-left: 3px solid var(--color-steel-blue);
            color: var(--color-ocean);
            padding: 8px 14px;
            border-radius: 0 6px 6px 0;
            font-size: 13px;
            margin-bottom: 16px;
            font-family: 'Sora', sans-serif;
            flex-wrap: wrap;
        }

        .participants-clear-link {
            color: var(--color-coral);
            margin-left: 12px;
            text-decoration: none;
            font-weight: 600;
        }

        .participants-count {
            margin-left: auto;
            color: var(--color-ocean);
            font-size: 13px;
        }

        .participants-empty-select {
            text-align: center;
            padding: 34px 16px;
            color: var(--color-ocean);
            font-family: 'Sora', sans-serif;
        }

        .participants-empty-select svg {
            width: 56px;
            height: 56px;
            color: var(--color-steel-blue);
            margin-bottom: 10px;
        }

        .participants-empty-select h3 {
            margin: 0 0 8px;
            color: var(--color-midnight);
            font-size: 17px;
            font-weight: 600;
        }

        .participants-empty-select p {
            margin: 0;
            color: var(--color-ocean);
            font-size: 13px;
        }

        .participants-page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .participants-page-title {
            margin: 0;
            min-width: 0;
        }

        .participants-page-actions {
            display: flex;
            align-items: flex-end;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
            margin-left: auto;
        }

        .participants-page-actions .btn {
            white-space: nowrap;
        }

        .participants-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
            background: #ffffff;
        }

        .participants-table-wrap {
            overflow-x: auto;
            border: 1px solid var(--color-sky);
            border-radius: 10px;
            background: #ffffff;
        }

        .participants-table thead th {
            background: var(--color-midnight);
            color: #ffffff;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.05em;
            font-weight: 600;
            padding: 14px 16px;
        }

        .participants-table tbody td {
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
            text-align: center;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 500;
            padding: 2px 8px;
            text-transform: capitalize;
            white-space: nowrap;
        }

        .guest-status-badge.status-pending {
            background: #fef9c3;
            color: #854d0e;
        }

        .guest-status-badge.status-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .guest-status-badge.status-denied {
            background: #fee2e2;
            color: #991b1b;
        }

        .participants-table tbody tr:last-child td {
            border-bottom: none;
        }

        .participants-table tbody tr:hover {
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
            font-weight: 600;
            padding: 2px 8px;
            min-width: 34px;
        }

        .badge-attended-yes {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-attended-no {
            background: #fee2e2;
            color: #991b1b;
        }

        .participant-actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            align-items: center;
        }

        .btn-action {
            border-radius: 6px;
            padding: 5px 8px;
            font-size: 11px;
            line-height: 1.2;
            font-weight: 600;
            font-family: 'Sora', sans-serif;
            text-decoration: none;
            white-space: nowrap;
            cursor: pointer;
            transition: all 160ms ease;
            border: 1px solid transparent;
            background: #ffffff;
        }

        .btn-view {
            border-color: var(--color-steel-blue);
            color: var(--color-steel-blue);
        }

        .btn-view:hover {
            background: var(--color-sky);
            color: var(--color-ocean);
        }

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

        .btn-digital-id {
            border: none;
            color: #ffffff;
            background: var(--color-midnight);
            transition: background 150ms ease;
        }

        .btn-digital-id:hover {
            border-color: var(--color-ocean);
            background: var(--color-ocean);
            color: #ffffff;
        }

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
            width: 28px;
            height: 28px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-delete-icon svg {
            width: 14px;
            height: 14px;
        }

        /* ── Delete Confirmation Modal ─────────────────────────────────── */
        .delete-confirm-modal {
            position: fixed;
            inset: 0;
            background: rgba(10, 35, 66, 0.55);
            z-index: 1400;
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

        .modal-toast {
            position: relative;
            margin: 0;
            pointer-events: none;
            opacity: 1;
        }

        .participants-empty-table {
            text-align: center;
            color: var(--color-ocean);
            font-size: 14px;
            padding: 32px;
            font-family: 'Sora', sans-serif;
        }

        .participant-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(10, 35, 66, 0.38);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 70;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 180ms ease, visibility 0s linear 180ms;
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
            transition: opacity 220ms ease, transform 220ms ease;
        }

        .modal-floating-label.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .modal-floating-label.is-hiding {
            opacity: 0;
            transform: translateY(-6px);
        }

        .modal-toast {
            position: relative;
            display: inline-flex;
            align-items: center;
            margin: 0;
            pointer-events: none;
            opacity: 1;
            min-width: 0;
            width: auto;
            max-width: 100%;
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

        .modal-floating-error {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .modal-floating-success {
            background: #ecfdf5;
            border: 1px solid #34d399;
            color: #065f46;
        }

        .participant-modal-overlay.is-visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition: opacity 180ms ease;
        }

        .participant-modal {
            width: min(680px, 100%);
            background: #ffffff;
            border: 1px solid var(--color-sky);
            border-radius: 12px;
            box-shadow: 0 18px 45px rgba(10, 35, 66, 0.22);
            padding: 18px;
            transform: translateY(10px) scale(0.98);
            opacity: 0;
            transition: transform 220ms ease, opacity 220ms ease;
        }

        .participant-modal-overlay.is-visible .participant-modal {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .participant-modal-title {
            margin: 0;
            color: var(--color-midnight);
            font-family: 'Sora', sans-serif;
            font-size: 1.35rem;
            font-weight: 700;
        }

        .participant-modal-event-meta {
            margin-top: 10px;
            padding: 10px 12px;
            border: 1px solid var(--color-sky);
            border-radius: 10px;
            background: #f8fcff;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px 12px;
        }

        .participant-modal-event-item {
            display: grid;
            gap: 3px;
        }

        .participant-modal-event-label {
            margin: 0;
            font-family: 'Sora', sans-serif;
            font-size: 11px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--color-steel-blue);
            font-weight: 600;
        }

        .participant-modal-event-value {
            margin: 0;
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            color: var(--color-midnight);
            font-weight: 600;
            line-height: 1.35;
        }

        .participant-modal-form {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px 16px;
            align-items: start;
            margin-top: 10px;
        }

        .participant-modal-form .field {
            margin: 0;
        }

        .participant-modal-form label {
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .participant-modal-form input {
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
            background-image: none;
        }

        .participant-modal-form select {
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

        .participant-modal-form-actions {
            grid-column: 1 / -1;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .participant-modal-form-actions .btn-cancel {
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

        .participant-modal-form-actions .btn-cancel:hover {
            background: var(--color-ice-white);
            border-color: var(--color-steel-blue);
            color: var(--color-ocean);
        }

        .participant-modal .btn,
        #openRegisterParticipantModal {
            font-family: 'Sora', sans-serif;
        }

        .participant-modal-close {
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

        .participant-modal-close:hover {
            background: var(--color-ice-white);
            border-color: var(--color-steel-blue);
            color: var(--color-midnight);
        }

        .participant-confirmation-content {
            margin-top: 10px;
            display: grid;
            gap: 10px;
            font-family: 'Sora', sans-serif;
        }

        .participant-confirmation-item {
            margin: 0;
            color: var(--color-midnight);
            font-size: 14px;
            line-height: 1.4;
        }

        .participant-confirmation-item strong {
            color: var(--color-ocean);
        }

        .participant-confirmation-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 6px;
        }

        .digital-id-modal {
            position: fixed;
            inset: 0;
            background: rgba(10, 35, 66, 0.55);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 180ms ease, visibility 0s linear 180ms;
        }

        .digital-id-modal.is-visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition: opacity 180ms ease;
        }

        .digital-id-panel {
            background: #ffffff;
            border-radius: 16px;
            width: 100%;
            max-width: 460px;
            padding: 32px;
            position: relative;
            box-shadow: 0 8px 40px rgba(10, 35, 66, 0.18);
            transform: translateY(10px) scale(0.98);
            opacity: 0;
            transition: transform 220ms ease, opacity 220ms ease;
        }

        .digital-id-modal.is-visible .digital-id-panel {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .digital-id-close {
            position: absolute;
            top: 16px;
            right: 20px;
            background: none;
            border: none;
            font-size: 20px;
            color: #5BA4CF;
            cursor: pointer;
        }

        .digital-id-kicker {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #5BA4CF;
            font-weight: 500;
            margin: 0 0 6px;
        }

        .digital-id-name {
            font-size: 20px;
            font-weight: 600;
            color: #0A2342;
            margin: 0;
            font-family: 'Sora', sans-serif;
        }

        .digital-id-event {
            font-size: 13px;
            color: #1B6CA8;
            margin: 4px 0 0;
            font-family: 'Sora', sans-serif;
        }

        .digital-id-qr-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            position: relative;
        }

        .digital-id-qr {
            width: 200px;
            height: 200px;
            border: 2px solid #BFDFFF;
            border-radius: 12px;
            padding: 8px;
            background: #E8F4FD;
            object-fit: contain;
        }

        .digital-id-qr-skeleton {
            position: absolute;
            inset: 0;
            margin: auto;
            width: 200px;
            height: 200px;
            border: 2px solid #BFDFFF;
            border-radius: 12px;
            background: linear-gradient(100deg, #e8f4fd 30%, #d6e9fb 50%, #e8f4fd 70%);
            background-size: 220% 100%;
            animation: did-skeleton 1.1s ease-in-out infinite;
            display: none;
        }

        .digital-id-qr-wrap.is-loading .digital-id-qr-skeleton {
            display: block;
        }

        .digital-id-qr-wrap.is-loading .digital-id-qr {
            opacity: 0;
        }

        @keyframes did-skeleton {
            from {
                background-position: 100% 0;
            }
            to {
                background-position: -100% 0;
            }
        }

        .digital-id-token-row {
            background: #E8F4FD;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .digital-id-token-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #5BA4CF;
            margin: 0 0 3px;
        }

        .digital-id-token-value {
            font-size: 12px;
            color: #0A2342;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;
            word-break: break-all;
            margin: 0;
        }

        .digital-id-copy-btn {
            margin-left: 12px;
            border: 1px solid #BFDFFF;
            background: #ffffff;
            color: #1B6CA8;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 12px;
            cursor: pointer;
            white-space: nowrap;
            font-family: 'Sora', sans-serif;
        }

        .digital-id-download {
            display: block;
            text-align: center;
            background: #0A2342;
            color: #ffffff;
            border-radius: 8px;
            padding: 12px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            font-family: 'Sora', sans-serif;
        }

        .digital-id-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .digital-id-resend {
            border: 1px solid #BFDFFF;
            background: #ffffff;
            color: #1B6CA8;
            border-radius: 8px;
            padding: 12px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Sora', sans-serif;
            cursor: pointer;
        }

        .digital-id-resend[disabled] {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .digital-id-email-status {
            margin: 10px 0 0;
            min-height: 18px;
            font-size: 12px;
            font-family: 'Sora', sans-serif;
            color: #5BA4CF;
        }

        .digital-id-email-meta {
            margin: 4px 0 0;
            min-height: 16px;
            font-size: 11px;
            font-family: 'Sora', sans-serif;
            color: #5BA4CF;
            opacity: 0.9;
        }

        .digital-id-email-status.success {
            color: #047857;
        }

        .digital-id-email-status.error {
            color: #b91c1c;
        }

        .fab-qr-scanner {
            position: fixed;
            right: 20px;
            bottom: 20px;
            z-index: 1200;
            width: 56px;
            height: 56px;
            border: 0;
            border-radius: 999px;
            background: var(--color-ocean);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 24px rgba(10, 35, 66, 0.24);
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            transform: translateZ(0);
            transition: background-color 160ms ease, box-shadow 160ms ease;
        }

        body.has-active-modal .fab-qr-scanner {
            z-index: 60;
            opacity: 0.32;
            pointer-events: none;
            box-shadow: none;
        }

        .fab-qr-scanner:hover,
        .fab-qr-scanner:focus-visible {
            background: var(--color-midnight);
            box-shadow: 0 12px 28px rgba(10, 35, 66, 0.28);
        }

        .fab-qr-scanner:focus-visible {
            outline: 3px solid rgba(91, 164, 207, 0.35);
            outline-offset: 3px;
        }

        .fab-qr-scanner svg {
            width: 24px;
            height: 24px;
            pointer-events: none;
        }

        .qr-scanner-modal {
            position: fixed;
            inset: 0;
            background: rgba(10, 35, 66, 0.55);
            z-index: 1300;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 180ms ease, visibility 0s linear 180ms;
        }

        .qr-scanner-modal.is-visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition: opacity 180ms ease;
        }

        .qr-scanner-panel {
            background: #ffffff;
            border-radius: 16px;
            width: 100%;
            max-width: 380px;
            padding: 28px 18px 18px;
            box-shadow: 0 8px 40px rgba(10, 35, 66, 0.18);
            position: relative;
            text-align: center;
        }

        .qr-scanner-title {
            margin: 0 0 18px;
            font-size: 1.2rem;
            color: var(--color-ocean);
            font-family: 'Sora', sans-serif;
        }

        .qr-scanner-close {
            position: absolute;
            top: 12px;
            right: 16px;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--color-steel-blue);
            cursor: pointer;
        }

        .qr-scan-status {
            margin-top: 16px;
            font-size: 14px;
            color: #065f46;
            min-height: 24px;
            font-family: 'Sora', sans-serif;
        }

        #openRegisterParticipantModal:disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
            background: #ccc !important;
            color: #666 !important;
            border-color: #ccc !important;
        }

        @media (max-width: 900px) {
            .participant-modal-form {
                grid-template-columns: 1fr;
            }

            .fab-qr-scanner {
                right: 14px;
                bottom: 14px;
                width: 50px;
                height: 50px;
            }

            .qr-scanner-panel {
                max-width: 98vw;
                padding: 18px 10px 12px;
            }
        }

        @media (max-width: 768px) {
            #toastContainer {
                position: fixed !important;
                top: 16px !important;
                right: 16px !important;
                left: auto !important;
                width: calc(100% - 32px) !important;
                max-width: 320px !important;
                z-index: 99999 !important;
                transform: none !important;
            }

            .modal-floating-label {
                position: relative !important;
                top: auto !important;
                right: auto !important;
                left: auto !important;
                transform: none !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }

            .modal-toast {
                width: auto !important;
                display: inline-flex !important;
                max-width: 100% !important;
                justify-self: end !important;
                min-width: 0 !important;
            }

            .participants-page-header {
                display: grid;
                grid-template-columns: minmax(0, 1fr) auto;
                align-items: start;
                gap: 10px 12px;
            }

            .participants-page-title {
                align-self: start;
            }

            .participants-page-actions {
                display: grid;
                grid-template-columns: 1fr;
                justify-items: end;
                align-self: start;
                gap: 8px;
                margin-left: 0;
            }

            .participants-page-actions .btn,
            .participants-page-actions #openRegisterParticipantModal {
                width: auto;
                min-width: 0;
            }

            #openRegisterParticipantModal:disabled {
                opacity: 0.5 !important;
                cursor: not-allowed !important;
                background: #ccc !important;
                color: #666 !important;
                border-color: #ccc !important;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $contextEvent = $selectedEvent ?? $event;
        $formAction = $event ? route('events.participants.index', $event) : route('participants.index');
        $clearLink = $event ? route('events.participants.index', $event) : route('participants.index');
    @endphp

    <div class="card">
        <div class="participants-page-header">
            <h1 class="participants-page-title">Participants</h1>
            @if ($selectedEvent)
                <div class="participants-page-actions">
                    <a class="btn" href="{{ route('events.show', $selectedEvent) }}">Back to Event</a>
                    <button class="btn btn-primary" type="button" id="openRegisterParticipantModal" {{ $selectedEvent->hasEnded() || ! $selectedEvent->isRegistrationOpen() || (auth()->user()->hasRole('event_staff') && $selectedEvent->created_by !== auth()->id()) ? 'disabled' : '' }}>Register Participant</button>
                </div>
            @endif
        </div>

        <form class="participants-filter-wrap" action="{{ $formAction }}" method="GET">
            <label for="event_id">Filter by event</label>
            <select id="event_id" name="event_id" class="participants-filter-select" onchange="this.form.submit()">
                <option value="">-- Select an Event --</option>
                @foreach ($events as $item)
                    <option value="{{ $item->id }}" {{ (string) request('event_id') === (string) $item->id ? 'selected' : '' }}>
                        {{ $item->title }} - {{ $item->getStatus() }} ({{ $item->dateRangeLabel() }})
                    </option>
                @endforeach
            </select>
        </form>

        @if ($selectedEvent)
            <div class="participants-filter-info">
                <span>
                    Viewing participants for: <strong>{{ $selectedEvent->title }}</strong>
                    <a class="participants-clear-link" href="{{ $clearLink }}">x Clear</a>
                </span>
                <span class="participants-count">{{ number_format($participants->count()) }} participant(s) registered</span>
            </div>
        @endif

        @if (!request('event_id'))
            <div class="participants-empty-select">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <h3>Select an event to view participants</h3>
                <p>Use the dropdown above to choose an open event.</p>
            </div>
        @else
            <div class="participants-table-wrap">
            <table class="participants-table">
                <thead>
                <tr>
                    <th style="width:20%">Name</th>
                    <th style="width:22%">Email</th>
                    <th style="width:10%">Attended</th>
                    <th style="width:18%">Registered At</th>
                    <th style="width:15%">Status</th>
                    <th style="width:15%">Actions</th>
                </tr>
                </thead>
                <tbody>
                @if ($participants->count() === 0)
                    <tr>
                        <td colspan="6" class="participants-empty-table">No participants registered for this event yet.</td>
                    </tr>
                @else
                    @foreach ($participants as $participant)
                        <tr data-participant-id="{{ $participant->id }}">
                            <td>{{ $participant->name }}</td>
                            <td class="cell-muted">{{ $participant->email }}</td>
                            <td>
                                <span class="badge-pill badge-attended-toggle {{ $participant->attended ? 'badge-attended-yes' : 'badge-attended-no' }}" data-participant-id="{{ $participant->id }}" data-attended="{{ $participant->attended ? 'true' : 'false' }}" title="Click to toggle attendance" style="cursor: pointer;">
                                    {{ $participant->attended ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="cell-muted">{{ \Carbon\Carbon::parse($participant->created_at)->format('M d, Y h:i A') }}</td>
                            <td><span class="guest-status-badge status-{{ $participant->status ?? 'approved' }}">{{ ucfirst($participant->status ?? 'approved') }}</span></td>
                            <td>
                                <div class="participant-actions">
                                    @if (($participant->status ?? 'approved') === 'pending')
                                        @if (auth()->user()->hasRole('admin') || (auth()->user()->hasRole('event_staff') && $selectedEvent->created_by === auth()->id()))
                                            <form class="js-approve-form" action="{{ route('events.participants.approve', [$selectedEvent, $participant]) }}" method="POST" style="display:inline;" data-participant-name="{{ $participant->name }}">
                                                @csrf
                                                <button class="btn-action btn-approve" type="submit">Approve</button>
                                            </form>
                                            <form class="js-deny-form" action="{{ route('events.participants.deny', [$selectedEvent, $participant]) }}" method="POST" style="display:inline;" data-participant-name="{{ $participant->name }}">
                                                @csrf
                                                <button class="btn-action btn-delete" type="submit">Deny</button>
                                            </form>
                                        @endif
                                    @else
                                        <a class="btn-action btn-view" href="{{ route('events.participants.show', [$selectedEvent, $participant]) }}">View</a>
                                        <button
                                            type="button"
                                            class="btn-action btn-digital-id"
                                            data-participant-id="{{ $participant->id }}"
                                            data-name="{{ $participant->name }}"
                                            data-event="{{ $selectedEvent->title ?? '' }}"
                                            data-token="{{ $participant->digital_id_token ?? '' }}"
                                            data-qr="{{ route('participants.digital-id.show', $participant, false) }}"
                                            data-resend-url="{{ route('events.participants.resend-digital-id', [$selectedEvent, $participant]) }}"
                                        >
                                            Digital ID
                                        </button>
                                    @endif
                                    <form id="delete-form-{{ $participant->id }}" action="{{ route('events.participants.destroy', [$selectedEvent, $participant]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">
                                        @if (!auth()->user()->hasRole('event_staff') || $selectedEvent->created_by === auth()->id())
                                            <button
                                                type="button"
                                                class="btn-action btn-delete btn-delete-icon js-delete-trigger"
                                                aria-label="Delete participant"
                                                title="Delete participant"
                                                data-form-id="delete-form-{{ $participant->id }}"
                                                data-participant-name="{{ $participant->name }}"
                                            >
                                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                    <path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m-8 0 1 12a1 1 0 0 0 1 .9h6a1 1 0 0 0 1-.9l1-12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            </div>


        @endif
    </div>

    <!-- Approve Confirmation Modal -->
    <div id="approveConfirmModal" class="delete-confirm-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="approveConfirmTitle">
        <div class="delete-confirm-panel">
            <h2 id="approveConfirmTitle" class="delete-confirm-title">Approve Participant</h2>
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
            <h2 id="denyConfirmTitle" class="delete-confirm-title">Deny Participant</h2>
            <p class="delete-confirm-body">Are you sure you want to deny and remove <span id="denyConfirmName" class="delete-confirm-name"></span>?</p>
            <div class="delete-confirm-actions">
                <button type="button" class="btn-delete-cancel" id="denyConfirmCancel">Cancel</button>
                <button type="button" class="btn-delete-confirm" id="denyConfirmSubmit">Yes, Deny</button>
            </div>
        </div>
    </div>

    @if ($selectedEvent)
        @php
            $modalErrorMessage = $errors->first('name') ?: $errors->first('email') ?: $errors->first('participant_type') ?: $errors->first('institution') ?: $errors->first('registration');
        @endphp

        <div class="participant-modal-overlay" id="registerParticipantModal" aria-hidden="true">
            <div class="participant-modal" role="dialog" aria-modal="true" aria-labelledby="registerParticipantModalTitle">
                <div class="header-row">
                    <h2 id="registerParticipantModalTitle" class="participant-modal-title">Register Participant</h2>
                    <button class="participant-modal-close" type="button" id="closeRegisterParticipantModal" aria-label="Close register participant modal">&times;</button>
                </div>

                <div class="participant-modal-event-meta">
                    <div class="participant-modal-event-item">
                        <p class="participant-modal-event-label">Event</p>
                        <p class="participant-modal-event-value">{{ $selectedEvent->title }}</p>
                    </div>
                    <div class="participant-modal-event-item">
                        <p class="participant-modal-event-label">Date</p>
                        <p class="participant-modal-event-value">{{ $selectedEvent->dateRangeLabel() }}</p>
                    </div>
                    <div class="participant-modal-event-item">
                        <p class="participant-modal-event-label">Location</p>
                        <p class="participant-modal-event-value">{{ $selectedEvent->location ?: 'N/A' }}</p>
                    </div>
                    <div class="participant-modal-event-item">
                        <p class="participant-modal-event-label">Registration</p>
                        <p class="participant-modal-event-value">{{ $selectedEvent->isRegistrationOpen() ? 'Open' : 'Closed' }}</p>
                    </div>
                </div>

                <form class="participant-modal-form" action="{{ route('events.participants.store', $selectedEvent) }}" method="POST">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ route('events.participants.index', $selectedEvent) }}?event_id={{ $selectedEvent->id }}">

                    <div class="field">
                        <label for="modal_name">Name</label>
                        <input id="modal_name" name="name" type="text" value="{{ old('name') }}" required>
                    </div>

                    <div class="field">
                        <label for="modal_email">Email</label>
                        <input id="modal_email" name="email" type="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="field">
                        <label for="modal_participant_type">Participant Type</label>
                        <select id="modal_participant_type" name="participant_type" required>
                            <option value="">Select type</option>
                            <option value="faculty" {{ old('participant_type') === 'faculty' ? 'selected' : '' }}>Faculty</option>
                            <option value="student" {{ old('participant_type') === 'student' ? 'selected' : '' }}>Student</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="modal_institution">School/University</label>
                        <input id="modal_institution" name="institution" type="text" value="{{ old('institution') }}" required>
                    </div>

                    <div class="participant-modal-form-actions">
                        <button class="btn btn-cancel" type="button" id="cancelRegisterParticipantModal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Register</button>
                    </div>
                </form>
            </div>
        </div>

        @if (session('participant_registered'))
            @php
                $registered = session('participant_registered');
            @endphp
            <div class="participant-modal-overlay is-visible" id="registrationSuccessModal" aria-hidden="false">
                

                <div class="participant-modal" role="dialog" aria-modal="true" aria-labelledby="registrationSuccessModalTitle">
                    <div class="header-row">
                        <h2 id="registrationSuccessModalTitle" class="participant-modal-title">Registration Confirmed</h2>
                        <button class="participant-modal-close" type="button" id="closeRegistrationSuccessModal" aria-label="Close registration confirmation modal">&times;</button>
                    </div>

                    <div class="participant-confirmation-content">
                        <p class="participant-confirmation-item"><strong>Name:</strong> {{ $registered['name'] ?? '' }}</p>
                        <p class="participant-confirmation-item"><strong>Email:</strong> {{ $registered['email'] ?? '' }}</p>
                        <p class="participant-confirmation-item"><strong>Participant Type:</strong> {{ ucfirst($registered['participant_type'] ?? '') ?: 'N/A' }}</p>
                        <p class="participant-confirmation-item"><strong>Institution:</strong> {{ $registered['institution'] ?? 'N/A' }}</p>
                        <p class="participant-confirmation-item"><strong>Event:</strong> {{ $registered['event'] ?? '' }}</p>

                        <div class="participant-confirmation-actions">
                            @if (!empty($registered['digital_id_url']) && !($registered['registered_by_admin'] ?? false))
                                <a class="btn btn-primary" href="{{ $registered['digital_id_url'] }}">Open Digital ID</a>
                            @endif
                            <button class="btn" type="button" id="dismissRegistrationSuccessModal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <div id="digitalIdModal" class="digital-id-modal" aria-hidden="true">
        <div class="digital-id-panel" role="dialog" aria-modal="true" aria-labelledby="did-name">
            <button id="closeDigitalId" class="digital-id-close" type="button" aria-label="Close digital ID">&#10005;</button>

            <div style="margin-bottom:24px;">
                <p class="digital-id-kicker">Digital ID</p>
                <h2 id="did-name" class="digital-id-name"></h2>
                <p id="did-event" class="digital-id-event"></p>
            </div>

            <div class="digital-id-qr-wrap">
                <div class="digital-id-qr-skeleton" aria-hidden="true"></div>
                <img id="did-qr" class="digital-id-qr" src="" alt="QR Code">
            </div>

            <div class="digital-id-token-row">
                <div>
                    <p class="digital-id-token-label">Token</p>
                    <p id="did-token" class="digital-id-token-value"></p>
                </div>
                <button id="copyTokenBtn" class="digital-id-copy-btn" type="button">Copy</button>
            </div>

            <div class="digital-id-actions">
                <a id="did-download" href="#" target="_blank" class="digital-id-download">Download QR PNG</a>
                <button id="did-resend-email" type="button" class="digital-id-resend">Resend Email</button>
            </div>
            <p id="did-email-status" class="digital-id-email-status" role="status" aria-live="polite"></p>
            <p id="did-email-meta" class="digital-id-email-meta" aria-live="polite"></p>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteConfirmModal" class="delete-confirm-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="deleteConfirmTitle">
        <div class="delete-confirm-panel">
            <h2 id="deleteConfirmTitle" class="delete-confirm-title">Delete Participant</h2>
            <p class="delete-confirm-body">
                Are you sure you want to delete <span id="deleteConfirmName" class="delete-confirm-name"></span>?
                This action <strong>cannot be undone</strong> and will permanently remove this participant from the event.
            </p>
            <div class="delete-confirm-actions">
                <button type="button" class="btn-delete-cancel" id="deleteConfirmCancel">Cancel</button>
                <button type="button" class="btn-delete-confirm" id="deleteConfirmSubmit">Yes, Delete</button>
            </div>
        </div>
    </div>

    @if ($selectedEvent)
        <button id="openQrScannerFab" class="fab-qr-scanner" type="button" title="Scan QR for Attendance" aria-label="Scan QR for Attendance">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="3" width="7" height="7" rx="2"></rect>
                <rect x="14" y="3" width="7" height="7" rx="2"></rect>
                <rect x="14" y="14" width="7" height="7" rx="2"></rect>
                <rect x="3" y="14" width="7" height="7" rx="2"></rect>
            </svg>
        </button>

        <div id="qrScannerModal" class="qr-scanner-modal" aria-hidden="true">
            <div class="qr-scanner-panel" role="dialog" aria-modal="true" aria-labelledby="qrScannerTitle">
                <button id="closeQrScannerModal" class="qr-scanner-close" type="button" aria-label="Close QR Scanner">&times;</button>
                <h2 id="qrScannerTitle" class="qr-scanner-title">Scan Participant Digital ID</h2>
                <div id="qr-reader" style="width:100%;max-width:340px;margin:auto;"></div>
                <div id="qr-scan-status" class="qr-scan-status" aria-live="polite"></div>
            </div>
        </div>
    @endif

    <div id="toastContainer" style="position:fixed; top:16px; right:16px; z-index:99999; max-width:320px; width:calc(100% - 32px); pointer-events:none; display:grid; gap:10px; justify-items:end;"></div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        (function () {
            var modal = document.getElementById('registerParticipantModal');
            var openButton = document.getElementById('openRegisterParticipantModal');
            var closeButton = document.getElementById('closeRegisterParticipantModal');
            var cancelButton = document.getElementById('cancelRegisterParticipantModal');
            var successModal = document.getElementById('registrationSuccessModal');
            var closeSuccessButton = document.getElementById('closeRegistrationSuccessModal');
            var dismissSuccessButton = document.getElementById('dismissRegistrationSuccessModal');
            var digitalIdModal = document.getElementById('digitalIdModal');
            var digitalCloseButton = document.getElementById('closeDigitalId');
            var copyTokenBtn = document.getElementById('copyTokenBtn');
            var resendEmailBtn = document.getElementById('did-resend-email');
            var emailStatus = document.getElementById('did-email-status');
            var emailMeta = document.getElementById('did-email-meta');
            var didQrWrap = document.querySelector('.digital-id-qr-wrap');
            var csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            var csrfTokenInput = document.querySelector('input[name="_token"]');
            var csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : (csrfTokenInput ? csrfTokenInput.value : '');
            var currentResendUrl = '';
            var qrFab = document.getElementById('openQrScannerFab');
            var qrModal = document.getElementById('qrScannerModal');
            var qrCloseButton = document.getElementById('closeQrScannerModal');
            var qrStatus = document.getElementById('qr-scan-status');
            var qrScanner = null;
            var deleteModal = document.getElementById('deleteConfirmModal');
            var getTrackedModals = function () {
                return [modal, successModal, digitalIdModal, qrModal, deleteModal]
                    .concat(Array.prototype.slice.call(document.querySelectorAll('.delete-confirm-modal')))
                    .filter(Boolean);
            };

            var syncQrFabWithModalState = function () {
                var hasActiveModal = getTrackedModals().some(function (trackedModal) {
                    return trackedModal && trackedModal.classList.contains('is-visible');
                });

                if (document.body) {
                    document.body.classList.toggle('has-active-modal', hasActiveModal);
                }

                if (qrFab) {
                    qrFab.disabled = hasActiveModal;
                    qrFab.setAttribute('aria-disabled', hasActiveModal ? 'true' : 'false');
                }
            };

            var updateParticipantAttendance = function (participant) {
                if (!participant || !participant.id) {
                    return;
                }

                var row = document.querySelector('tr[data-participant-id="' + participant.id + '"]');
                if (!row) {
                    return;
                }

                var attendedBadge = row.querySelector('.badge-attended-toggle');
                if (!attendedBadge) {
                    return;
                }

                var isAttended = participant.attended === true || participant.attended === 'true';
                attendedBadge.textContent = isAttended ? 'Yes' : 'No';
                attendedBadge.dataset.attended = isAttended ? 'true' : 'false';
                attendedBadge.classList.toggle('badge-attended-yes', isAttended);
                attendedBadge.classList.toggle('badge-attended-no', !isAttended);
            };

            var setEmailStatus = function (message, type) {
                if (!emailStatus) {
                    return;
                }

                emailStatus.innerText = message || '';
                emailStatus.classList.remove('success', 'error');

                if (type) {
                    emailStatus.classList.add(type);
                }
            };

            var setEmailMeta = function (message) {
                if (!emailMeta) {
                    return;
                }

                emailMeta.innerText = message || '';
            };

            if (!modal || !openButton) {
                return;
            }

            var startQrScanner = function () {
                if (!qrModal || !window.Html5Qrcode || qrScanner) {
                    return;
                }

                qrScanner = new Html5Qrcode('qr-reader');
                qrScanner.start(
                    { facingMode: 'environment' },
                    { fps: 10, qrbox: 220 },
                    function (decodedText) {
                        if (!qrStatus) {
                            return;
                        }

                        qrStatus.innerText = 'Checking...';
                        qrStatus.style.color = '#065f46';

                        if (qrScanner) {
                            qrScanner.stop().then(function () {
                                qrScanner.clear();
                                qrScanner = null;
                            }).catch(function () {
                                qrScanner = null;
                            });
                        }

                        fetch("{{ route('admin.digital-id.scan') }}", {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ payload: decodedText })
                        })
                            .then(function (response) {
                                return response.json().then(function (data) {
                                    if (!response.ok) {
                                        var message = (data && data.message) ? data.message : 'Invalid QR code.';
                                        throw new Error(message);
                                    }

                                    return data;
                                });
                            })
                            .then(function (data) {
                                qrStatus.innerText = (data && data.message) ? data.message : 'Attendance marked successfully.';
                                qrStatus.style.color = '#065f46';

                                if (data && data.participant) {
                                    updateParticipantAttendance(data.participant);
                                }

                                window.setTimeout(function () {
                                    closeQrModal();
                                }, 900);
                            })
                            .catch(function (error) {
                                qrStatus.innerText = error.message || 'Scan failed. Try again.';
                                qrStatus.style.color = '#b91c1c';

                                window.setTimeout(function () {
                                    startQrScanner();
                                }, 900);
                            });
                    },
                    function () {}
                ).catch(function () {
                    if (qrStatus) {
                        qrStatus.innerText = 'Unable to access the camera in this preview.';
                        qrStatus.style.color = '#b91c1c';
                    }
                    qrScanner = null;
                });
            };

            var stopQrScanner = function () {
                if (!qrScanner) {
                    return;
                }

                qrScanner.stop().then(function () {
                    qrScanner.clear();
                    qrScanner = null;
                }).catch(function () {
                    qrScanner = null;
                });
            };

            var openQrModal = function () {
                if (!qrModal) {
                    return;
                }

                qrModal.classList.add('is-visible');
                qrModal.setAttribute('aria-hidden', 'false');
                syncQrFabWithModalState();

                if (qrStatus) {
                    qrStatus.innerText = '';
                }

                window.setTimeout(startQrScanner, 150);
            };

            var closeQrModal = function () {
                if (!qrModal) {
                    return;
                }

                qrModal.classList.remove('is-visible');
                qrModal.setAttribute('aria-hidden', 'true');
                stopQrScanner();
                syncQrFabWithModalState();
            };

            if (qrFab && qrModal) {
                qrFab.addEventListener('click', openQrModal);

                qrModal.addEventListener('click', function (event) {
                    if (event.target === qrModal) {
                        closeQrModal();
                    }
                });
            }

            if (qrCloseButton) {
                qrCloseButton.addEventListener('click', closeQrModal);
            }

            var openModal = function () {
                modal.classList.add('is-visible');
                modal.setAttribute('aria-hidden', 'false');
                syncQrFabWithModalState();
            };

            var closeModal = function () {
                modal.classList.remove('is-visible');
                modal.setAttribute('aria-hidden', 'true');
                syncQrFabWithModalState();
            };

            openButton.addEventListener('click', openModal);

            if (closeButton) {
                closeButton.addEventListener('click', closeModal);
            }

            if (cancelButton) {
                cancelButton.addEventListener('click', closeModal);
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

            if ({{ ($errors->has('name') || $errors->has('email') || $errors->has('registration') || request()->boolean('open_register_participant')) ? 'true' : 'false' }}) {
                openModal();
            }

            var closeSuccessModal = function () {
                if (!successModal) {
                    return;
                }

                successModal.classList.remove('is-visible');
                successModal.setAttribute('aria-hidden', 'true');
                syncQrFabWithModalState();
            };

            if (closeSuccessButton) {
                closeSuccessButton.addEventListener('click', closeSuccessModal);
            }

            if (dismissSuccessButton) {
                dismissSuccessButton.addEventListener('click', closeSuccessModal);
            }

            if (successModal) {
                successModal.addEventListener('click', function (event) {
                    if (event.target === successModal) {
                        closeSuccessModal();
                    }
                });

                if (successModal.classList.contains('is-visible')) {
                    setTimeout(function () {
                        showToast("Registration successful. Participant's Digital ID has been sent to their email.", 'success', 5000);
                    }, 300);
                }
            }

            @if ($errors->has('name') || $errors->has('email') || $errors->has('participant_type') || $errors->has('institution') || $errors->has('registration'))
                setTimeout(function () {
                    showToast({!! json_encode($errors->first('name') ?: $errors->first('email') ?: $errors->first('participant_type') ?: $errors->first('institution') ?: $errors->first('registration')) !!}, 'error', 5000);
                }, 300);
            @endif

            var toasts = document.querySelectorAll('.modal-toast');
            toasts.forEach(function (toast) {
                window.setTimeout(function () {
                    toast.classList.add('is-hiding');
                }, 9600);

                window.setTimeout(function () {
                    if (toast && toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 10000);
            });

            var closeDigitalModal = function () {
                if (!digitalIdModal) {
                    return;
                }

                digitalIdModal.classList.remove('is-visible');
                digitalIdModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                var qr = document.getElementById('did-qr');
                if (qr) {
                    qr.src = '';
                }

                if (didQrWrap) {
                    didQrWrap.classList.remove('is-loading');
                }

                currentResendUrl = '';
                setEmailStatus('');
                setEmailMeta('');

                if (resendEmailBtn) {
                    resendEmailBtn.disabled = false;
                    resendEmailBtn.innerText = 'Resend Email';
                }

                syncQrFabWithModalState();
            };

            if (digitalCloseButton) {
                digitalCloseButton.addEventListener('click', closeDigitalModal);
            }

            if (digitalIdModal) {
                digitalIdModal.addEventListener('click', function (event) {
                    if (event.target === digitalIdModal) {
                        closeDigitalModal();
                    }
                });
            }

            if (copyTokenBtn) {
                copyTokenBtn.addEventListener('click', function () {
                    var tokenEl = document.getElementById('did-token');
                    if (!tokenEl) {
                        return;
                    }

                    navigator.clipboard.writeText(tokenEl.innerText).then(function () {
                        copyTokenBtn.innerText = 'Copied!';
                        window.setTimeout(function () {
                            copyTokenBtn.innerText = 'Copy';
                        }, 1500);
                    });
                });
            }

            function attachDigitalIdButtonHandler(button) {
                button.addEventListener('click', function () {
                    var participantId = button.dataset.participantId || '';
                    var participantName = button.dataset.name || '';
                    var eventName = button.dataset.event || '';
                    var token = button.dataset.token || '';
                    var baseUrl = button.dataset.qr || '';
                    currentResendUrl = button.dataset.resendUrl || '';

                    var didName = document.getElementById('did-name');
                    var didEvent = document.getElementById('did-event');
                    var didToken = document.getElementById('did-token');
                    var didQr = document.getElementById('did-qr');
                    var didDownload = document.getElementById('did-download');

                    if (didQrWrap) {
                        didQrWrap.classList.add('is-loading');
                    }

                    if (didName) {
                        didName.innerText = participantName;
                    }

                    if (didEvent) {
                        didEvent.innerText = 'Event: ' + eventName;
                    }

                    if (didToken) {
                        didToken.innerText = token || 'Not available';
                    }

                    if (didQr && baseUrl) {
                        didQr.onload = function () {
                            if (didQrWrap) {
                                didQrWrap.classList.remove('is-loading');
                            }
                        };

                        didQr.onerror = function () {
                            if (didQrWrap) {
                                didQrWrap.classList.remove('is-loading');
                            }
                        };

                        var queryChar = baseUrl.includes('?') ? '&' : '?';
                        didQr.src = baseUrl + queryChar + 'format=qr';
                    }

                    if (didDownload && baseUrl) {
                        var queryChar = baseUrl.includes('?') ? '&' : '?';
                        didDownload.href = baseUrl + queryChar + 'download=1';
                    }

                    setEmailStatus('');
                    setEmailMeta('');

                    if (resendEmailBtn) {
                        resendEmailBtn.disabled = !currentResendUrl;
                        resendEmailBtn.innerText = 'Resend Email';
                    }

                    if (digitalIdModal) {
                        digitalIdModal.classList.add('is-visible');
                        digitalIdModal.setAttribute('aria-hidden', 'false');
                        document.body.style.overflow = 'hidden';
                        syncQrFabWithModalState();
                    }
                });
            }

            var digitalButtons = document.querySelectorAll('.btn-digital-id[data-participant-id]');
            digitalButtons.forEach(function (button) {
                attachDigitalIdButtonHandler(button);
            });

            if (resendEmailBtn) {
                resendEmailBtn.addEventListener('click', function () {
                    if (!currentResendUrl || !csrfToken) {
                        setEmailStatus('Unable to resend email right now.', 'error');
                        return;
                    }

                    resendEmailBtn.disabled = true;
                    resendEmailBtn.innerText = 'Sending...';
                    setEmailStatus('Sending Digital ID email...');

                    fetch(currentResendUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(function (response) {
                            return response.json().then(function (data) {
                                if (!response.ok) {
                                    var message = (data && data.message) ? data.message : 'Failed to resend Digital ID email.';
                                    throw new Error(message);
                                }

                                return data;
                            });
                        })
                        .then(function (data) {
                            setEmailStatus((data && data.message) ? data.message : 'Digital ID email resent successfully.', 'success');

                            var now = new Date();
                            var formatted = now.toLocaleString([], {
                                month: 'short',
                                day: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            setEmailMeta('Last sent: ' + formatted);
                        })
                        .catch(function (error) {
                            setEmailStatus(error.message || 'Failed to resend Digital ID email.', 'error');
                        })
                        .finally(function () {
                            resendEmailBtn.disabled = false;
                            resendEmailBtn.innerText = 'Resend Email';
                        });
                });
            }

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeDigitalModal();
                    closeQrModal();
                }
            });

            // ── Delete Confirmation Modal ──────────────────────────────────
            var deleteConfirmName = document.getElementById('deleteConfirmName');
            var deleteConfirmCancel = document.getElementById('deleteConfirmCancel');
            var deleteConfirmSubmit = document.getElementById('deleteConfirmSubmit');
            var pendingDeleteForm = null;

            var openDeleteModal = function (participantName, formId) {
                pendingDeleteForm = document.getElementById(formId);
                if (!deleteModal || !pendingDeleteForm) {
                    return;
                }

                if (deleteConfirmName) {
                    deleteConfirmName.innerText = participantName || 'this participant';
                }

                deleteModal.classList.add('is-visible');
                deleteModal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                syncQrFabWithModalState();
            };

            var closeDeleteModal = function () {
                if (!deleteModal) {
                    return;
                }

                deleteModal.classList.remove('is-visible');
                deleteModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                pendingDeleteForm = null;
                syncQrFabWithModalState();
            };

            document.querySelectorAll('.js-delete-trigger').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    openDeleteModal(btn.dataset.participantName, btn.dataset.formId);
                });
            });

            if (deleteConfirmCancel) {
                deleteConfirmCancel.addEventListener('click', closeDeleteModal);
            }

            if (deleteModal) {
                deleteModal.addEventListener('click', function (event) {
                    if (event.target === deleteModal) {
                        closeDeleteModal();
                    }
                });
            }

            if (deleteConfirmSubmit) {
                deleteConfirmSubmit.addEventListener('click', function () {
                    if (pendingDeleteForm) {
                        pendingDeleteForm.submit();
                    }
                });
            }

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && deleteModal && deleteModal.classList.contains('is-visible')) {
                    closeDeleteModal();
                }
            });

            // ── Approve / Deny Confirmation Modals & AJAX handlers ─────────────────
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

            document.querySelectorAll('form.js-approve-form').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    pendingApproveForm = form;
                    if (approveNameEl) approveNameEl.innerText = form.dataset.participantName || 'this participant';
                    if (approveModal) {
                        approveModal.classList.add('is-visible');
                        approveModal.setAttribute('aria-hidden', 'false');
                        document.body.style.overflow = 'hidden';
                        syncQrFabWithModalState();
                    }
                });
            });

            if (approveCancel) approveCancel.addEventListener('click', function () {
                if (!approveModal) return;
                approveModal.classList.remove('is-visible');
                approveModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                pendingApproveForm = null;
                syncQrFabWithModalState();
            });

            if (approveModal) {
                approveModal.addEventListener('click', function (e) {
                    if (e.target === approveModal) {
                        approveModal.classList.remove('is-visible');
                        approveModal.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                        pendingApproveForm = null;
                        syncQrFabWithModalState();
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
                        showToast(result.data.message || 'Participant approved and digital ID email sent.', 'success');
                        var row = pendingApproveForm.closest('tr');
                        if (row) {
                            var statusBadge = row.querySelector('.guest-status-badge');
                            if (statusBadge) {
                                statusBadge.textContent = 'Approved';
                                statusBadge.className = 'guest-status-badge status-approved';
                            }
                            var actionDiv = row.querySelector('.participant-actions');
                            if (actionDiv) {
                                var approveForms = actionDiv.querySelectorAll('form.js-approve-form, form.js-deny-form');
                                approveForms.forEach(function (form) { form.remove(); });
                                
                                var deleteForm = actionDiv.querySelector('[id^="delete-form-"]');
                                var participantData = result.data.participant || {};
                                
                                var viewLink = document.createElement('a');
                                viewLink.className = 'btn-action btn-view';
                                viewLink.href = pendingApproveForm.action.replace('/approve', '');
                                viewLink.textContent = 'View';
                                actionDiv.insertBefore(viewLink, deleteForm);
                                
                                var digitalIdBtn = document.createElement('button');
                                digitalIdBtn.type = 'button';
                                digitalIdBtn.className = 'btn-action btn-digital-id';
                                digitalIdBtn.textContent = 'Digital ID';
                                digitalIdBtn.setAttribute('data-participant-id', participantData.id || row.dataset.participantId);
                                var nameCell = row.querySelector('td:first-child');
                                digitalIdBtn.setAttribute('data-name', participantData.name || (nameCell ? nameCell.textContent : ''));
                                digitalIdBtn.setAttribute('data-event', '{{ $selectedEvent->title ?? '' }}');
                                digitalIdBtn.setAttribute('data-token', participantData.digital_id_token || '');
                                digitalIdBtn.setAttribute('data-qr', participantData.qr_url || '');
                                digitalIdBtn.setAttribute('data-resend-url', participantData.resend_url || '');
                                actionDiv.insertBefore(digitalIdBtn, deleteForm);
                                
                                attachDigitalIdButtonHandler(digitalIdBtn);
                            }
                        }
                    } else {
                        showToast(result.data.message || 'Failed to approve.', 'error');
                    }
                }).catch(function (err) {
                    showToast('Error approving participant.', 'error');
                }).finally(function () {
                    if (approveModal) {
                        approveModal.classList.remove('is-visible');
                        approveModal.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                    }
                    pendingApproveForm = null;
                    syncQrFabWithModalState();
                });
            });

            document.querySelectorAll('form.js-deny-form').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    pendingDenyForm = form;
                    if (denyNameEl) denyNameEl.innerText = form.dataset.participantName || 'this participant';
                    if (denyModal) {
                        denyModal.classList.add('is-visible');
                        denyModal.setAttribute('aria-hidden', 'false');
                        document.body.style.overflow = 'hidden';
                        syncQrFabWithModalState();
                    }
                });
            });

            if (denyCancel) denyCancel.addEventListener('click', function () {
                if (!denyModal) return;
                denyModal.classList.remove('is-visible');
                denyModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                pendingDenyForm = null;
                syncQrFabWithModalState();
            });

            if (denyModal) {
                denyModal.addEventListener('click', function (e) {
                    if (e.target === denyModal) {
                        denyModal.classList.remove('is-visible');
                        denyModal.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                        pendingDenyForm = null;
                        syncQrFabWithModalState();
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
                        showToast(result.data.message || 'Participant denied.', 'success');
                        var row = pendingDenyForm.closest('tr');
                        if (row) row.parentNode.removeChild(row);
                    } else {
                        showToast(result.data.message || 'Failed to deny participant.', 'error');
                    }
                }).catch(function (err) {
                    showToast('Error denying participant.', 'error');
                }).finally(function () {
                    if (denyModal) {
                        denyModal.classList.remove('is-visible');
                        denyModal.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                    }
                    pendingDenyForm = null;
                    syncQrFabWithModalState();
                });
            });

            syncQrFabWithModalState();

            // Hide Previous and Next links in pagination, keep only numbers
            const hidePageNavigationText = () => {
                const paginationContainer = document.querySelector('.participants-pagination-numbers');
                if (paginationContainer) {
                    const links = paginationContainer.querySelectorAll('a');
                    links.forEach(link => {
                        const text = link.textContent.trim();
                        if (text === 'Previous' || text === 'Next' || text === '«' || text === '»') {
                            link.style.display = 'none';
                        }
                    });
                }
            };

            // Call on page load and after any navigation
            hidePageNavigationText();

            // If pagination links are dynamically updated, observe for changes
            const paginationContainer = document.querySelector('.participants-pagination-numbers');
            if (paginationContainer) {
                const observer = new MutationObserver(hidePageNavigationText);
                observer.observe(paginationContainer, { childList: true, subtree: true });
            }
        })();
    </script>
@endpush
