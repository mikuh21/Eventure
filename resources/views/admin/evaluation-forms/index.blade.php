@extends('layouts.app')

@section('title', 'Evaluation Form - Eventure')

@push('styles')
    @include('admin.partials.management-styles')
    <style>
        .evaluation-form-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: #ffffff;
        }

        .evaluation-form-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 16px;
        }

        .evaluation-form-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 4px 0;
        }

        .evaluation-form-subtitle {
            font-size: 14px;
            color: #666;
            margin: 0;
        }

        .evaluation-form-meta {
            display: flex;
            gap: 16px;
            margin: 12px 0;
            font-size: 14px;
            color: #666;
        }

        .evaluation-form-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .evaluation-form-questions {
            margin: 16px 0;
            padding: 12px;
            background: #f5f5f5;
            border-radius: 4px;
        }

        .evaluation-form-questions-title {
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .evaluation-form-questions-list {
            font-size: 13px;
            color: #666;
        }

        .evaluation-form-actions {
            display: flex;
            gap: 16px;
            margin-top: 16px;
            justify-content: space-between;
            align-items: flex-end;
        }

        .evaluation-form-action-icons {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .admin-management-icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border: none;
            background: transparent;
            color: var(--color-ocean);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            padding: 0;
            flex-shrink: 0;
        }

        .admin-management-icon-btn:hover {
            background: var(--color-ice-white);
            color: var(--color-midnight);
        }

        .admin-management-icon-btn:disabled,
        .admin-management-icon-btn[aria-disabled="true"] {
            opacity: 0.4;
            cursor: not-allowed;
            color: #9ca3af;
            background: transparent;
        }

        .admin-management-icon-btn:disabled:hover,
        .admin-management-icon-btn[aria-disabled="true"]:hover {
            background: transparent;
        }

        .admin-management-icon-btn svg {
            width: 18px;
            height: 18px;
        }

        .admin-management-form-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: #ccc !important;
            color: #666 !important;
        }

        .admin-management-form-btn,
        .open-form-btn {
            font-family: 'Sora', sans-serif;
        }

        .open-form-btn {
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

        .open-form-btn:hover {
            background: #e8f4fd;
            border-color: #5ba4cf;
        }

        .evaluation-form-status-badge {
            display: inline-flex;
            width: auto;
            white-space: nowrap;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .evaluation-form-status-open {
            background: #d4edda;
            color: #155724;
        }

        .evaluation-form-status-closed {
            background: #f8d7da;
            color: #721c24;
        }

        .evaluation-form-grid {
            display: grid;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .admin-management-header {
                flex-wrap: nowrap;
                align-items: flex-start;
                justify-content: space-between;
            }

            .evaluation-form-card {
                position: relative;
            }

            .evaluation-form-header {
                flex-direction: row;
                align-items: flex-start;
                justify-content: space-between;
                gap: 12px;
            }

            .evaluation-form-meta {
                order: 2;
                flex-wrap: wrap;
                gap: 8px;
            }

            .evaluation-form-actions {
                order: 1;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
                position: relative;
                padding-right: 0;
                min-height: 76px;
            }

            .evaluation-form-actions span {
                display: block;
                margin: 0;
                font-size: 13px;
                color: #666;
            }

            .evaluation-form-action-icons {
                position: absolute;
                right: 16px;
                bottom: 16px;
                display: flex;
                gap: 8px;
                justify-content: flex-end;
                align-items: center;
            }

            .evaluation-form-questions {
                order: 3;
            }

            .evaluation-form-actions .admin-management-form-btn,
            .evaluation-form-actions form,
            .evaluation-form-actions button {
                width: auto;
                max-width: 100%;
            }
        }

        /* Toast notification styles */
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
            z-index: 10001;
            opacity: 0;
            transform: translateY(-6px);
            transition: opacity 220ms ease, transform 220ms ease;
        }

        .modal-floating-label.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .modal-floating-label {
                position: fixed;
                top: 76px;
                left: 50%;
                right: auto;
                transform: translateX(-50%);
                width: auto;
                min-width: min(280px, calc(100% - 32px));
                max-width: min(420px, calc(100% - 32px));
                padding: 12px 18px;
                line-height: 1.5;
                z-index: 1505;
            }
        }

        .modal-floating-label.is-hiding {
            opacity: 0;
            transform: translateY(-6px);
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

        .modal-floating-info {
            background: #eff6ff;
            border: 1px solid #93c5fd;
            color: #1e40af;
        }

        .modal-floating-warning {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            color: #92400e;
        }

        /* ── Delete Confirmation Modal ─────────────────────────────── */
        .delete-confirm-modal {
            position: fixed;
            inset: 0;
            background: rgba(10, 35, 66, 0.55);
            z-index: 10000 !important;
            display: flex !important;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 180ms ease, visibility 0s linear 180ms;
        }

        .delete-confirm-modal.active,
        .delete-confirm-modal.is-visible {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
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

        /* ── Overview Cards ─────────────────────────────── */
        .ef-overview-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin: 16px 0;
        }

        .ef-overview-card {
            background: #ffffff;
            border: 1px solid var(--color-sky);
            border-radius: 10px;
            padding: 14px 16px;
            box-shadow: 0 2px 8px rgba(10, 35, 66, 0.06);
        }

        .ef-overview-label {
            color: var(--color-ocean);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 600;
        }

        .ef-overview-value {
            margin-top: 6px;
            color: var(--color-midnight);
            font-size: 30px;
            line-height: 1;
            font-weight: 700;
        }

        /* ── Filters Row ─────────────────────────────── */
        .ef-filters-row {
            display: flex;
            gap: 12px;
            align-items: center;
            margin: 16px 0 20px;
            flex-wrap: wrap;
        }

        .ef-search-field {
            position: relative;
            width: 250px;
        }

        .ef-search-field .ef-filter-input {
            width: 100%;
            padding-left: 34px;
        }

        .ef-search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 14px;
            height: 14px;
            color: var(--color-steel-blue);
            pointer-events: none;
        }

        .ef-filter-input,
        .ef-filter-select {
            border: 1px solid var(--color-sky);
            border-radius: 8px;
            padding: 6px 12px;
            background: #ffffff;
            color: var(--color-midnight);
            font-size: 13px;
            font-family: 'Sora', sans-serif;
            box-sizing: border-box;
        }

        .ef-filter-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding-right: 34px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235BA4CF' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 14px 14px;
        }

        .ef-filter-input:focus,
        .ef-filter-select:focus {
            outline: none;
            border-color: var(--color-steel-blue);
            box-shadow: 0 0 0 3px rgba(91, 164, 207, 0.18);
        }

        .ef-showing-text {
            margin-left: auto;
            color: var(--color-ocean);
            font-size: 13px;
            font-family: 'Sora', sans-serif;
        }

        .ef-filters-row .btn {
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            padding: 6px 12px;
            line-height: 1.2;
        }

        @media (max-width: 960px) {
            .ef-overview-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 8px;
            }

            .ef-overview-card {
                padding: 12px 10px;
                text-align: center;
                min-width: 0;
                box-sizing: border-box;
            }

            .ef-overview-label {
                font-size: 0.66rem;
                line-height: 1.25;
            }

            .ef-overview-value {
                margin-top: 8px;
                font-size: 1.2rem;
            }

            .ef-search-field {
                width: 100%;
                flex: 1 1 100%;
            }
        }

        @media (max-width: 560px) {
            .ef-overview-grid {
                gap: 6px;
            }

            .ef-overview-card {
                padding: 10px 8px;
            }

            .ef-overview-label {
                font-size: 0.6rem;
            }

            .ef-overview-value {
                font-size: 1.02rem;
            }
        }

        /* ── Form Builder Styles ──────────────────────────────────── */
        .form-builder-wrapper {
            background: #ffffff;
            border-radius: 10px;
            padding: 24px;
            margin-top: 20px;
            box-shadow: 0 2px 8px rgba(10, 35, 66, 0.06);
        }

        .form-builder-container {
            min-height: 200px;
        }

        .form-section {
            margin-bottom: 32px;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .form-section-header {
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--color-sky);
        }

        .form-section-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--color-midnight);
            margin: 0;
        }

        .form-section-questions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .question-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: #ffffff;
            border: 1px solid var(--color-sky);
            border-radius: 8px;
            cursor: move;
            transition: all 0.2s ease;
            position: relative;
        }

        .question-card:hover {
            border-color: var(--color-steel-blue);
            box-shadow: 0 4px 12px rgba(91, 164, 207, 0.15);
        }

        .question-card.dragging {
            opacity: 0.5;
            background: var(--color-ice-white);
        }

        .question-card-handle {
            color: var(--color-ocean);
            font-size: 18px;
            user-select: none;
            cursor: grab;
            flex-shrink: 0;
        }

        .question-card-handle:active {
            cursor: grabbing;
        }

        .question-card-content {
            flex: 1;
            min-width: 0;
        }

        .question-card-title {
            font-weight: 600;
            color: var(--color-midnight);
            margin-bottom: 6px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .question-card-meta {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .question-type-badge,
        .question-status-badge,
        .question-matrix-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .question-type-badge {
            background: #e3f2fd;
            color: #1976d2;
        }

        .question-status-badge {
            background: #f0f0f0;
            color: #666;
        }

        .question-status-badge.active {
            background: #d4edda;
            color: #155724;
        }

        .question-matrix-badge {
            background: #fff3e0;
            color: #e65100;
        }

        .question-card-delete {
            width: 32px;
            height: 32px;
            border: none;
            background: transparent;
            color: #dc3545;
            font-size: 24px;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.2s ease;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .question-card-delete:hover {
            background: #ffe5e5;
        }

        .form-builder-empty {
            text-align: center;
            padding: 40px 20px;
            color: #999;
            font-size: 14px;
        }

        /* ── Modal Styles ──────────────────────────────────────── */
        .modal-overlay {
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

        .modal-overlay.active,
        .modal-overlay.is-visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition: opacity 180ms ease;
        }

        .modal-content {
            background: #ffffff;
            border-radius: 16px;
            width: 100%;
            max-width: 520px;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 8px 40px rgba(10, 35, 66, 0.22);
            transform: translateY(10px) scale(0.98);
            opacity: 0;
            transition: transform 220ms ease, opacity 220ms ease;
        }

        #formBuilderModal .modal-content {
            max-width: 900px;
        }

        #eventQuestionsModal .modal-content {
            max-width: 900px;
            max-height: 88vh;
        }

        .modal-overlay.active .modal-content,
        .modal-overlay.is-visible .modal-content {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px;
            border-bottom: 1px solid var(--color-sky);
        }

        .modal-title {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: var(--color-midnight);
        }

        .modal-close {
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
            padding: 0;
        }

        .modal-close:hover {
            background: var(--color-ice-white);
            border-color: var(--color-steel-blue);
            color: var(--color-midnight);
        }

        .modal-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-height: 60vh;
            overflow-y: auto;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 20px 24px;
            border-top: 1px solid var(--color-sky);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-weight: 600;
            color: var(--color-midnight);
            font-size: 14px;
        }

        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="time"],
        .form-group textarea,
        .form-group select {
            border: 1px solid var(--color-sky);
            border-radius: 8px;
            padding: 10px 12px;
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            color: var(--color-midnight);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            box-sizing: border-box;
        }

        .form-group select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding-right: 34px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235BA4CF' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 14px 14px;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="date"]:focus,
        .form-group input[type="time"]:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--color-steel-blue);
            box-shadow: 0 0 0 3px rgba(91, 164, 207, 0.18);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-weight: 500;
            color: var(--color-midnight);
            margin: 0;
        }

        .checkbox-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .modal-close-btn {
            border: 1px solid var(--color-sky);
            background: #ffffff;
            color: var(--color-ocean);
        }

        .modal-close-btn:hover {
            background: var(--color-ice-white);
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .modal-body {
                max-height: 70vh;
            }
        }



        /* ── Edit Modal Section Navigation ────────────────────────── */
        .event-questions-section-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--color-sky);
            font-family: 'Sora', sans-serif;
            flex-wrap: wrap;
        }

        .section-tab {
            padding: 8px 16px;
            border: none;
            background: transparent;
            color: var(--color-ocean);
            font-family: 'Sora', sans-serif;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .section-tab:hover {
            color: var(--color-midnight);
        }

        .section-tab.active {
            color: var(--color-midnight);
            border-bottom-color: var(--color-midnight);
            font-weight: 600;
        }

        .event-questions-section-group {
            margin-bottom: 24px;
        }

        .event-questions-section-heading {
            display: block;
            margin-bottom: 12px;
        }

        .event-questions-section-rows {
            display: flex;
            flex-direction: column;
            gap: 0;
            margin-bottom: 12px;
        }

        .event-questions-section-title {
            font-family: 'Sora', sans-serif;
            font-size: 15px;
            font-weight: 600;
            color: var(--color-midnight);
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--color-sky);
        }

        .event-questions-section-subtitle {
            font-size: 11px;
            font-weight: 500;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
            border-bottom: none !important;
            padding-bottom: 0 !important;
        }

        .question-edit-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--color-sky);
            font-family: 'Sora', sans-serif;
        }

        .drag-handle {
            cursor: grab;
            color: #9ca3af;
            font-size: 14px;
            user-select: none;
            flex-shrink: 0;
        }

        .question-order {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            background: var(--color-sky);
            color: var(--color-midnight);
            border-radius: 50%;
            font-size: 12px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .question-text-input {
            flex: 1;
            min-width: 0;
            border: 1px solid var(--color-sky);
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 14px;
            color: var(--color-midnight);
            transition: border-color 0.2s ease;
            width: 100%;
        }

        .question-text-input:focus {
            outline: none;
            border-color: var(--color-steel-blue);
            box-shadow: 0 0 0 2px rgba(91, 164, 207, 0.18);
        }

        .question-row-delete {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #dc3545;
            background: transparent;
            border-radius: 8px;
            transition: background-color 0.2s ease;
            flex-shrink: 0;
        }

        .question-row-delete:hover {
            background: rgba(220, 53, 69, 0.1);
        }

        .question-row-delete svg {
            width: 16px;
            height: 16px;
            stroke-width: 2;
        }

        .required-toggle {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            user-select: none;
            flex-shrink: 0;
        }

        .required-toggle-input {
            display: none;
        }

        .required-toggle-pill {
            width: 36px;
            height: 20px;
            background: #e5e7eb;
            border-radius: 10px;
            position: relative;
            transition: background-color 0.2s ease;
        }

        .required-toggle-input:checked + .required-toggle-pill {
            background: var(--color-ocean);
        }

        .required-toggle-pill::before {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 16px;
            height: 16px;
            background: white;
            border-radius: 50%;
            transition: transform 0.2s ease;
        }

        .required-toggle-input:checked + .required-toggle-pill::before {
            transform: translateX(16px);
        }

        .required-toggle-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--color-midnight);
        }

        .question-edit-row.dragging {
            opacity: 0.5;
            transform: rotate(2deg);
        }

        /* Add Question Button Styles */
        .event-questions-section-actions {
            margin-top: 8px;
            padding-left: 32px; /* Align with question content (drag handle + order badge + gap) */
        }

        .add-section-question-btn {
            font-size: 12px !important;
            padding: 5px 12px !important;
            border-radius: 6px !important;
            font-family: 'Sora', sans-serif !important;
            border: 1px solid var(--color-sky) !important;
            background: transparent !important;
            color: var(--color-ocean) !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            font-weight: 500 !important;
        }

        .add-section-question-btn:hover {
            background: var(--color-ice-white) !important;
            border-color: var(--color-steel-blue) !important;
        }

        /* Mobile responsiveness for question rows */
        @media (max-width: 768px) {
            .question-edit-row {
                gap: 8px;
                padding: 10px 0;
                flex-wrap: wrap;
            }

            .drag-handle {
                font-size: 12px;
            }

            .question-order {
                width: 20px;
                height: 20px;
                font-size: 11px;
            }

            .question-text-input {
                padding: 6px 8px;
                font-size: 13px;
                min-width: 120px;
            }

            .required-toggle {
                gap: 4px;
            }

            .required-toggle-pill {
                width: 32px;
                height: 18px;
            }

            .required-toggle-pill::before {
                width: 14px;
                height: 14px;
                top: 2px;
                left: 2px;
            }

            .required-toggle-input:checked + .required-toggle-pill::before {
                transform: translateX(14px);
            }

            .required-toggle-label {
                font-size: 12px;
            }

            .event-questions-section-tabs {
                justify-content: center;
            }

            .event-questions-section-tabs .section-tab {
                flex: 1 1 100%;
                max-width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .question-edit-row {
                gap: 6px;
                padding: 8px 0;
            }

            .question-text-input {
                padding: 5px 6px;
                font-size: 12px;
                min-width: 100px;
            }

            .required-toggle-label {
                display: none; /* Hide label on very small screens */
            }
        }

        .question-edit-row label {
            font-family: 'Sora', sans-serif;
        }

        .question-edit-row input,
        .question-edit-row textarea,
        .question-edit-row select {
            font-family: 'Sora', sans-serif;
        }

        .event-questions-modal-labels {
            font-family: 'Sora', sans-serif;
        }

        .form-builder-empty {
            font-family: 'Sora', sans-serif;
        }

        #eventQuestionsModal .modal-body {
            font-family: 'Sora', sans-serif;
        }

        #eventQuestionsModal .modal-header,
        #eventQuestionsModal .modal-footer {
            font-family: 'Sora', sans-serif;
        }

        #eventQuestionsModal .modal-title {
            font-family: 'Sora', sans-serif;
        }

        #eventQuestionsModal .btn {
            font-family: 'Sora', sans-serif;
        }

        .question-input-type-label {
            font-size: 11px;
            font-weight: 500;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
            display: block;
            width: 100%;
            padding-left: 48px;
            margin-bottom: -8px;
        }

        /* ── Form Builder Modal Styles ──────────────────────────────────── */
        #formBuilderModal .modal-content {
            max-width: 920px;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
        }

        #formBuilderModal .modal-body {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .form-builder-modal-wrapper {
            padding: 20px 24px 24px 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            flex: 1;
            min-height: 0;
            overflow: hidden;
        }

        .form-builder-modal-header-text {
            font-size: 13px;
            color: #666;
            margin: 0;
            padding: 0;
            font-family: 'Sora', sans-serif;
        }

        .form-builder-modal-container {
            min-height: 300px;
            max-height: 50vh;
            overflow-y: auto;
            border-radius: 8px;
            border: 1px solid var(--color-sky);
            padding: 16px;
            background: #f9f9f9;
        }

        #eventQuestionsList.form-builder-modal-container {
            flex: 1;
            min-height: 0;
        }

        .event-questions-section-subtitle {
            font-size: 12px;
            color: #666;
            font-weight: 500;
            margin-bottom: 12px;
            font-style: italic;
            padding: 0 0 0 32px;
        }

        .form-section-in-modal {
            margin-bottom: 24px;
        }

        .form-section-in-modal:last-child {
            margin-bottom: 0;
        }

        .form-section-header-modal {
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--color-sky);
        }

        .form-section-title-modal {
            font-size: 15px;
            font-weight: 700;
            color: var(--color-midnight);
            margin: 0;
        }

        .form-section-questions-modal {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .question-row-modal {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #ffffff;
            border: 1px solid var(--color-sky);
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .question-row-modal:hover {
            border-color: var(--color-steel-blue);
            box-shadow: 0 2px 8px rgba(91, 164, 207, 0.1);
        }

        .question-row-modal.dragging {
            opacity: 0.5;
            background: var(--color-ice-white);
        }

        .question-row-modal-handle {
            color: #9ca3af;
            font-size: 14px;
            user-select: none;
            cursor: grab;
            flex-shrink: 0;
        }

        .question-row-modal-handle:active {
            cursor: grabbing;
        }

        .question-row-modal-content {
            flex: 1;
            min-width: 0;
        }

        .question-row-modal-title {
            font-weight: 600;
            color: var(--color-midnight);
            margin: 0 0 4px 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 14px;
        }

        .question-row-modal-meta {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin: 0;
        }

        .question-row-modal-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .question-row-modal-badge-type {
            background: #e3f2fd;
            color: #1976d2;
        }

        .question-row-modal-badge-status {
            background: #f0f0f0;
            color: #666;
        }

        .question-row-modal-badge-status.active {
            background: #d4edda;
            color: #155724;
        }

        .question-row-modal-badge-matrix {
            background: #fff3e0;
            color: #e65100;
        }

        .modal-footer-form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 20px 24px;
            border-top: 1px solid var(--color-sky);
        }

        .modal-footer-form-actions .btn {
            font-family: 'Sora', sans-serif;
        }

        @media (max-width: 768px) {
            #formBuilderModal .modal-content {
                max-height: 90vh;
                max-width: 100%;
            }

            .form-builder-modal-container {
                max-height: 45vh;
            }

            .question-row-modal {
                flex-wrap: wrap;
                gap: 8px;
            }

            .question-row-modal-title {
                width: 100%;
                order: 2;
            }

            .question-row-modal-handle {
                order: 1;
            }
        }
    </style>
@endpush

@section('content')
    <div class="admin-management-page">
        <div class="admin-management-header" style="display:flex;justify-content:space-between;align-items:center;">
            <div>
                <h1 class="admin-management-title">Evaluation Form Management</h1>
            </div>
        </div>

        {{-- Overview Cards --}}
        <div class="ef-overview-grid">
            <div class="ef-overview-card">
                <div class="ef-overview-label">Total Events</div>
                <div class="ef-overview-value">{{ $overview['total_events'] }}</div>
            </div>
            <div class="ef-overview-card">
                <div class="ef-overview-label">Forms Open</div>
                <div class="ef-overview-value">{{ $overview['forms_open'] }}</div>
            </div>
            <div class="ef-overview-card">
                <div class="ef-overview-label">Forms Closed</div>
                <div class="ef-overview-value">{{ $overview['forms_closed'] }}</div>
            </div>
        </div>

        {{-- Filters --}}
        <form class="ef-filters-row" action="{{ route('admin.event-evaluation-forms.index') }}" method="GET">
            <div class="ef-search-field">
                <svg class="ef-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input
                    class="ef-filter-input"
                    type="text"
                    name="search"
                    placeholder="Search events..."
                    value="{{ request('search') }}"
                >
            </div>

            <select class="ef-filter-select" name="status">
                <option value="">All Statuses</option>
                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>

            <select class="ef-filter-select" name="type">
                <option value="">All Types</option>
                <option value="student_event" {{ request('type') === 'student_event' ? 'selected' : '' }}>School Event</option>
                <option value="conference" {{ request('type') === 'conference' ? 'selected' : '' }}>Conference</option>
            </select>

            <button class="btn btn-primary" type="button" id="efFiltersApplyBtn">Apply</button>

            <span class="ef-showing-text" id="efShowingCount" data-total="{{ $events->count() }}">
                Showing {{ $events->count() }} of {{ $overview['total_events'] }} event(s)
            </span>
        </form>
    </div>

    <div class="admin-management-page">
        <div class="evaluation-form-grid">
            @forelse($events as $event)
                <div class="evaluation-form-card" data-event-title="{{ strtolower($event->title) }}" data-event-type="{{ $event->type }}" data-event-status="{{ $event->isEvaluationFormEnabled() ? 'open' : 'closed' }}">
                    <div class="evaluation-form-header">
                        <div>
                            <h3 class="evaluation-form-title">{{ $event->title }}</h3>
                            <p class="evaluation-form-subtitle">
                                @if($event->type === 'conference')
                                    Conference
                                @else
                                    School Event
                                @endif
                                | {{ $event->attendanceTypeLabel() }}
                            </p>
                        </div>
                        <span class="evaluation-form-status-badge {{ $event->isEvaluationFormEnabled() ? 'evaluation-form-status-open' : 'evaluation-form-status-closed' }}">
                            {{ $event->isEvaluationFormEnabled() ? 'Open' : 'Closed' }}
                        </span>
                    </div>

                    <div class="evaluation-form-meta">
                        <div class="evaluation-form-meta-item">
                            <strong>Event Date:</strong>
                            {{ $event->dateRangeLabel() }}
                        </div>
                        <div class="evaluation-form-meta-item">
                            <strong>Participants:</strong>
                            {{ $event->participants()->count() }}
                        </div>
                        <div class="evaluation-form-meta-item">
                            <strong>Attended:</strong>
                            {{ $event->participants()->where('attended', true)->count() }}
                        </div>
                        <div class="evaluation-form-meta-item">
                            <strong>Registration:</strong>
                            {{ $event->isRegistrationOpen() ? 'Open' : 'Closed' }}
                        </div>
                        @if($event->guests()->count() > 0)
                            <div class="evaluation-form-meta-item">
                                <strong>Guests:</strong>
                                {{ $event->guests()->count() }}
                            </div>
                        @endif
                    </div>

                    <div class="evaluation-form-questions">
                        <div class="evaluation-form-questions-title">Form Questions</div>
                        <div class="evaluation-form-questions-list">
                            @php
                                $participantQuestions = $event->evaluationQuestions->where('is_guest_question', false)->count();
                                $activeParticipantQuestions = $event->evaluationQuestions->where('is_guest_question', false)->where('is_active', true)->count();
                                $guestQuestions = $event->evaluationQuestions->where('is_guest_question', true)->where('is_active', true)->count();
                            @endphp
                            Participant Questions: <strong>{{ $activeParticipantQuestions }}/{{ $participantQuestions }}</strong> active
                            @if($event->guests()->count() > 0 && $guestQuestions > 0)
                                | Guest Questions: <strong>{{ $guestQuestions }}</strong>
                            @endif
                        </div>
                    </div>

                    <div class="evaluation-form-actions">
                        <div style="display: flex; flex-direction: column; gap: 8px; align-items: flex-start;">
                            @if($event->evaluationQuestions->isEmpty())
                                <form method="POST" action="{{ route('admin.event-evaluation-forms.load-default', $event) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn admin-management-btn-secondary admin-management-form-btn">
                                        Load Default Form
                                    </button>
                                </form>
                                <span style="font-size: 12px; color: #999;">No form configured yet.</span>
                            @else
                                @if($event->isEvaluationFormEnabled())
                                    <form method="POST" action="{{ route('admin.event-evaluation-forms.disable', $event) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn admin-management-form-btn" style="background: #dc3545; color: white;">
                                            Close Form
                                        </button>
                                    </form>
                                    <span style="font-size: 12px; color: #999;">
                                        Opened at {{ $event->evaluation_form_enabled_at?->format('M d, Y g:i A') }}
                                    </span>
                                @else
                                    @if($event->getAttendedParticipants()->isEmpty())
                                        <button class="btn admin-management-form-btn open-form-btn" data-has-participants="false" data-event-title="{{ $event->title }}" {{ $event->hasEnded() ? 'disabled' : '' }}>
                                            Open Form
                                        </button>
                                        <span style="font-size: 12px; color: #999;">
                                            @if($event->hasEnded())
                                                Event has ended
                                            @else
                                                No attended participants
                                            @endif
                                        </span>
                                    @else
                                        @if($event->hasEnded())
                                            <button class="btn admin-management-form-btn open-form-btn" disabled>
                                                Open Form
                                            </button>
                                            <span style="font-size: 12px; color: #999;">Event has ended</span>
                                        @else
                                            <form method="POST" action="{{ route('admin.event-evaluation-forms.enable', $event) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn admin-management-btn-primary admin-management-form-btn open-form-btn" data-has-participants="true">
                                                    Open Form
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        </div>

                        <!-- View and Delete action icons -->
                        <div class="evaluation-form-action-icons">
                            <button type="button" class="admin-management-icon-btn js-open-form-builder-modal"
                                    aria-label="View/Edit evaluation form" title="View/Edit evaluation form"
                                    data-event-id="{{ $event->id }}"
                                    data-event-title="{{ $event->title }}"
                                    data-event-type="{{ $event->type }}"
                                    data-event-questions='@json($event->evaluationQuestions->sortBy("sort_order")->values(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)'
                                    {{ $event->hasEnded() ? 'disabled aria-disabled="true" title="Event has ended"' : '' }}>
                                <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </button>
                            @if(!$event->evaluationQuestions->isEmpty())
                                <button
                                    type="button"
                                    class="admin-management-icon-btn js-eval-delete-trigger"
                                    aria-label="Clear evaluation form"
                                    title="Clear evaluation form"
                                    style="color: #dc3545;"
                                    data-action-url="{{ route('admin.event-evaluation-forms.clear', $event) }}"
                                    data-event-title="{{ $event->title }}"
                                >
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="admin-management-empty">
                    <h3>No events found</h3>
                    <p>Create an event first to manage evaluation forms.</p>
                </div>
            @endforelse
        </div>
    </div>



    <!-- Form Builder Modal (View/Edit Form Questions) -->
    <div id="formBuilderModal" class="modal-overlay" aria-hidden="true" role="dialog" aria-modal="true">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h2 id="formBuilderModalTitle" class="modal-title">View Evaluation Form</h2>
                    <p class="form-builder-modal-header-text">Drag to reorder questions</p>
                </div>
                <button type="button" class="modal-close form-builder-modal-close" aria-label="Close modal">×</button>
            </div>
            
            <div class="form-builder-modal-wrapper">
                <div id="formBuilderModalContainer" class="form-builder-modal-container">
                    <!-- Form sections and questions will be inserted here -->
                </div>
            </div>

            <div class="modal-footer-form-actions">
                <div></div>
                <div style="display: flex; gap: 12px; align-items: center;">
                    <button type="button" class="btn btn-secondary form-builder-modal-close btn-cancel">Close</button>
                    <button type="button" class="btn btn-primary" id="formBuilderModalEditBtn" style="display: none;">Edit Form</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Questions Modal (edit questions inline) -->
    <div id="eventQuestionsModal" class="modal-overlay" aria-hidden="true" role="dialog" aria-modal="true">
        <div class="modal-content" style="max-width: 900px;">
            <div class="modal-header">
                <div>
                    <h2 id="eventQuestionsModalTitle" class="modal-title">Edit Evaluation Form</h2>
                    <p class="form-builder-modal-header-text">Drag to reorder questions</p>
                </div>
                <button type="button" class="modal-close event-questions-modal-close" aria-label="Close modal">×</button>
            </div>
            
            <div class="form-builder-modal-wrapper">
                <div id="eventQuestionsSectionTabs" class="event-questions-section-tabs"></div>
                <div id="eventQuestionsList" class="form-builder-modal-container"></div>
            </div>

            <div class="modal-footer-form-actions">
                <div></div>
                <div style="display: flex; gap: 12px; align-items: center;">
                    <button type="button" class="btn btn-secondary event-questions-modal-close btn-cancel">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveEventQuestionsBtn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast container -->
    <div id="toastContainer"></div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="modal-overlay delete-confirm-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="deleteConfirmTitle">
        <div class="delete-confirm-panel">
            <h2 id="deleteConfirmTitle" class="delete-confirm-title">Delete Evaluation Form</h2>
            <p class="delete-confirm-body">
                Are you sure you want to delete the evaluation form for <span id="deleteConfirmName" class="delete-confirm-name"></span>?
                This action <strong>cannot be undone</strong>.
            </p>
            <div class="delete-confirm-actions">
                <button type="button" class="btn-delete-cancel btn-cancel" id="deleteConfirmCancel">Cancel</button>
                <button type="button" class="btn-delete-confirm" id="deleteConfirmSubmit">Yes, Delete</button>
            </div>
        </div>
    </div>

    <script>
        // Toast notification system
        function showToast(message, type = 'info', duration = 4000) {
            const toastContainer = document.getElementById('toastContainer');
            const activeOverlay = document.querySelector('.modal-overlay.active, .modal-overlay.is-visible');
            const container = activeOverlay || toastContainer || document.body;
            const toast = document.createElement('div');
            toast.className = 'modal-floating-label modal-floating-' + type + ' modal-toast';
            toast.textContent = message;
            container.appendChild(toast);
            window.setTimeout(function () {
                toast.classList.add('is-visible');
            }, 10);

            window.setTimeout(function () {
                toast.classList.remove('is-visible');
                toast.classList.add('is-hiding');
                window.setTimeout(function () {
                    if (toast && toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 220);
            }, duration);
        }

        const normalizeEventType = (type) => {
            if (!type) {
                return 'all';
            }
            if (type === 'standard') {
                return 'school_event';
            }
            return type;
        };

        function showModal(modal) {
            if (!modal) return;
            modal.classList.add('active', 'is-visible');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function hideModal(modal) {
            if (!modal) return;
            modal.classList.remove('is-visible', 'active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        // Spin animation style
        const spinStyle = document.createElement('style');
        spinStyle.textContent = `@keyframes spin { to { transform: rotate(360deg); } }`;
        document.head.appendChild(spinStyle);

        // Open form buttons
        document.addEventListener('DOMContentLoaded', function () {
            const openButtons = document.querySelectorAll('.open-form-btn');
            openButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    const hasParticipants = this.getAttribute('data-has-participants') === 'true';
                    const eventTitle = this.getAttribute('data-event-title') || 'this event';
                    if (!hasParticipants) {
                        e.preventDefault();
                        showToast('Cannot open evaluation form: No attended participants found for ' + eventTitle, 'warning', 5000);
                        return;
                    }

                    // Allow natural form submission - do not prevent default
                    showToast('Opening evaluation form and sending emails to attended participants...', 'info', 3000);
                });
            });
        });

        // ── Delete Confirmation Modal ──────────────────────────────────────
        (function () {
            var deleteModal = document.getElementById('deleteModal');
            var deleteConfirmName = document.getElementById('deleteConfirmName');
            var deleteConfirmCancel = document.getElementById('deleteConfirmCancel');
            var deleteConfirmSubmit = document.getElementById('deleteConfirmSubmit');
            var pendingActionUrl = null;

            if (!deleteModal) {
                console.error('Evaluation form delete modal not found: #deleteModal');
                return;
            }

            var openDeleteModal = function (actionUrl, eventTitle) {
                if (!actionUrl) {
                    console.error('Delete trigger missing data-action-url for event:', eventTitle);
                }
                pendingActionUrl = actionUrl || '';
                if (deleteConfirmName) deleteConfirmName.innerText = eventTitle || 'this event';
                showModal(deleteModal);
            };

            var closeDeleteModal = function () {
                hideModal(deleteModal);
                pendingActionUrl = null;
            };

            const deleteButtons = document.querySelectorAll('.js-eval-delete-trigger');
            deleteButtons.forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    openDeleteModal(this.dataset.actionUrl, this.dataset.eventTitle);
                });
            });

            if (deleteConfirmCancel) deleteConfirmCancel.addEventListener('click', closeDeleteModal);

            deleteModal.addEventListener('click', function (e) {
                if (e.target === deleteModal) closeDeleteModal();
            });

            if (deleteConfirmSubmit) {
                deleteConfirmSubmit.addEventListener('click', function () {
                    if (!pendingActionUrl) return;
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = pendingActionUrl;
                    form.style.display = 'none';
                    const csrf = document.querySelector('meta[name="csrf-token"]');
                    if (csrf) {
                        const t = document.createElement('input');
                        t.type = 'hidden';
                        t.name = '_token';
                        t.value = csrf.getAttribute('content');
                        form.appendChild(t);
                    }
                    const m = document.createElement('input');
                    m.type = 'hidden';
                    m.name = '_method';
                    m.value = 'DELETE';
                    form.appendChild(m);
                    document.body.appendChild(form);
                    form.submit();
                    closeDeleteModal();
                });
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && deleteModal.classList.contains('is-visible')) {
                    closeDeleteModal();
                }
            });
        })();

        // ── Live Search Filter ──────────────────────────────────────────────
        (function () {
            const searchInput = document.querySelector('input[name="search"]');
            const cards = Array.from(document.querySelectorAll('.evaluation-form-card[data-event-title]'));
            const showingCount = document.getElementById('efShowingCount');
            const statusSelect = document.querySelector('select[name="status"]');
            const typeSelect = document.querySelector('select[name="type"]');
            const applyBtn = document.getElementById('efFiltersApplyBtn');

            if (!searchInput || cards.length === 0) return;

            // appliedFilters only change when Apply is clicked
            let appliedFilters = {
                status: statusSelect ? statusSelect.value : '',
                type: typeSelect ? typeSelect.value : ''
            };

            const normalize = (value) => value.toLowerCase().trim();

            const mapType = (val) => val === 'student_event' ? 'school_event' : val;

            const filterCards = () => {
                const query = normalize(searchInput.value);
                const terms = query === '' ? [] : query.split(/\s+/).filter(Boolean);
                let visibleCount = 0;

                cards.forEach((card) => {
                    const title = normalize(card.getAttribute('data-event-title') || '');
                    const cardType = card.getAttribute('data-event-type') || '';
                    const cardStatus = (card.getAttribute('data-event-status') || '').toLowerCase();

                    // include visible type label (subtitle) in search e.g. 'School Event' or 'Conference'
                    let typeText = '';
                    const subtitle = card.querySelector('.evaluation-form-subtitle');
                    if (subtitle && subtitle.innerText) {
                        // subtitle contains 'Conference | ...' or 'School Event | ...' so extract first part
                        typeText = normalize(subtitle.innerText.split('|')[0] || subtitle.innerText);
                    } else {
                        typeText = normalize(cardType === 'conference' ? 'conference' : 'school event');
                    }

                    const searchable = (title + ' ' + typeText).trim();
                    const matchesSearch = terms.length === 0 || terms.every((term) => searchable.includes(term));
                    const typeFilter = mapType(appliedFilters.type);
                    const matchesType = !typeFilter || typeFilter === cardType;
                    const matchesStatus = !appliedFilters.status || appliedFilters.status === cardStatus;

                    const isVisible = matchesSearch && matchesType && matchesStatus;
                    card.style.display = isVisible ? '' : 'none';
                    if (isVisible) visibleCount++;
                });

                if (showingCount) {
                    const total = Number(showingCount.getAttribute('data-total')) || cards.length;
                    showingCount.textContent = 'Showing ' + visibleCount + ' of ' + total + ' event(s)';
                }
            };

            searchInput.addEventListener('input', filterCards);

            if (applyBtn) {
                applyBtn.addEventListener('click', function () {
                    appliedFilters.status = statusSelect ? statusSelect.value : '';
                    appliedFilters.type = typeSelect ? typeSelect.value : '';
                    filterCards();
                    try {
                        const params = new URLSearchParams(window.location.search);
                        if (appliedFilters.type) params.set('type', appliedFilters.type); else params.delete('type');
                        if (appliedFilters.status) params.set('status', appliedFilters.status); else params.delete('status');
                        const newUrl = window.location.pathname + '?' + params.toString();
                        window.history.replaceState({}, '', newUrl);
                    } catch (err) {}
                });
            }

            filterCards();
        })();

        // ── Form Builder Modal: Render (View Form Questions) ──────────────
        (function () {
            const modal = document.getElementById('formBuilderModal');
            const container = document.getElementById('formBuilderModalContainer');
            const editQuestionsBtn = document.getElementById('formBuilderModalEditBtn');
            const closeBtn = document.querySelector('.form-builder-close-btn');

            if (!modal || !container) return;

            const renderBuilder = (questions, eventType, eventTitle) => {
                container.innerHTML = '';
                if (!questions || questions.length === 0) {
                    container.innerHTML = '<p>No questions configured</p>';
                    editQuestionsBtn.style.display = 'none';
                    return;
                }

                editQuestionsBtn.style.display = 'block';
                
                const grouped = {};
                questions.forEach(q => {
                    const section = q.section || 'General';
                    if (!grouped[section]) grouped[section] = [];
                    grouped[section].push(q);
                });

                Object.keys(grouped).forEach(section => {
                    const sectionDiv = document.createElement('div');
                    sectionDiv.className = 'form-builder-section';
                    const header = document.createElement('h4');
                    header.className = 'form-builder-section-header';
                    header.textContent = section;
                    sectionDiv.appendChild(header);

                    const list = document.createElement('ul');
                    list.className = 'form-builder-question-list';

                    grouped[section].forEach(q => {
                        const item = document.createElement('li');
                        const span = document.createElement('span');
                        span.textContent = q.question + ' (' + (q.type || 'text') + ')';
                        item.appendChild(span);
                        list.appendChild(item);
                    });

                    sectionDiv.appendChild(list);
                    container.appendChild(sectionDiv);
                });

                // Store questions for edit button
                modal.dataset.questions = JSON.stringify(questions);
                modal.dataset.eventTitle = eventTitle;
            };

            window.openFormBuilderModal = function (questions, eventType, eventTitle) {
                eventType = normalizeEventType(eventType);
                modal.dataset.eventType = eventType;
                renderBuilder(questions, eventType, eventTitle);
                showModal(modal);
            };

            // Click handler for view button
            const viewButtons = document.querySelectorAll('.js-open-form-builder-modal');
            viewButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const eventId = btn.dataset.eventId;
                    const eventTitle = btn.dataset.eventTitle;
                    const eventType = normalizeEventType(btn.dataset.eventType);
                    const questionsJson = btn.dataset.eventQuestions;
                    
                    try {
                        const questions = questionsJson ? JSON.parse(questionsJson) : [];
                        // Open the event questions modal directly for editing
                        const eventQuestionsModal = document.getElementById('eventQuestionsModal');
                        if (eventQuestionsModal && window.openEventQuestionsModal) {
                            window.openEventQuestionsModal(eventId, eventType, eventTitle, questions, btn);
                        }
                    } catch (err) {
                        console.error('Error parsing questions:', err);
                        showToast('Failed to load form', 'error');
                    }
                });
            });

            if (closeBtn) closeBtn.addEventListener('click', () => { hideModal(modal); });
            modal.addEventListener('click', (e) => {
                if (e.target === modal) hideModal(modal);
            });

            if (editQuestionsBtn) {
                editQuestionsBtn.addEventListener('click', () => {
                    const formBuilderModal = document.getElementById('formBuilderModal');
                    const eventQuestionsModal = document.getElementById('eventQuestionsModal');
                    const eventId = formBuilderModal.dataset.eventId;
                    const eventType = normalizeEventType(formBuilderModal.dataset.eventType);
                    const eventTitle = formBuilderModal.dataset.eventTitle || 'Event';
                    
                    if (!eventId || !eventQuestionsModal) return;
                    
                    let questions = [];
                    try {
                        questions = JSON.parse(formBuilderModal.dataset.questions || '[]');
                    } catch (err) {
                        console.error('Error loading questions:', err);
                    }
                    
                    // Close form builder modal and open event questions modal
                    hideModal(formBuilderModal);
                    
                    // Trigger the event questions modal opening
                    window.openEventQuestionsModal(eventId, eventType, eventTitle, questions);
                });
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal.classList.contains('is-visible')) {
                    hideModal(modal);
                }
            });
        })();

        // ── Event Questions Modal: Edit Questions with Drag-Drop ─────────
        (function () {
            const modal = document.getElementById('eventQuestionsModal');
            const modalTitle = document.getElementById('eventQuestionsModalTitle');
            const tabsContainer = document.getElementById('eventQuestionsSectionTabs');
            const list = document.getElementById('eventQuestionsList');
            const closeBtns = document.querySelectorAll('.event-questions-modal-close');
            const saveBtn = document.getElementById('saveEventQuestionsBtn');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            if (!modal) return;

            const createQuestionRow = (question, section, order, eventType, isNew = false) => {
                const row = document.createElement('div');
                row.className = 'question-edit-row';
                row.draggable = true;
                row.dataset.section = section;
                row.dataset.type = question.type || 'text';
                row.dataset.eventType = normalizeEventType(eventType || 'all');

                if (question.matrix_parent_id) {
                    row.dataset.matrixParentId = question.matrix_parent_id;
                }
                if (question.matrix_question_text) {
                    row.dataset.matrixQuestionText = question.matrix_question_text;
                }
                if (question.matrix_item_index !== undefined) {
                    row.dataset.matrixItemIndex = question.matrix_item_index;
                }
                if (question.is_matrix_item) {
                    row.dataset.isMatrixItem = 'true';
                }

                const qid = question.id ? String(question.id) : 'new-' + Math.random().toString(36).slice(2, 10);
                row.dataset.qid = qid;
                if (isNew || !question.id) {
                    row.dataset.new = 'true';
                }

                const dragHandle = document.createElement('span');
                dragHandle.className = 'drag-handle';
                dragHandle.textContent = '⠿';

                const orderBadge = document.createElement('span');
                orderBadge.className = 'question-order';
                orderBadge.textContent = order;

                const inputQ = document.createElement('input');
                inputQ.type = 'text';
                inputQ.value = question.question || '';
                inputQ.name = 'question_text_' + qid;
                inputQ.placeholder = 'Question text';
                inputQ.className = 'question-text-input';

                const requiredToggle = document.createElement('label');
                requiredToggle.className = 'required-toggle';

                const requiredInput = document.createElement('input');
                requiredInput.type = 'checkbox';
                requiredInput.name = 'is_required_' + qid;
                requiredInput.checked = !!question.is_required;
                requiredInput.className = 'required-toggle-input';

                const requiredPill = document.createElement('span');
                requiredPill.className = 'required-toggle-pill';

                const requiredText = document.createElement('span');
                requiredText.className = 'required-toggle-label';
                requiredText.textContent = 'Required';

                requiredToggle.appendChild(requiredInput);
                requiredToggle.appendChild(requiredPill);
                requiredToggle.appendChild(requiredText);

                const hiddenSort = document.createElement('input');
                hiddenSort.type = 'hidden';
                hiddenSort.className = 'sort-order-input';
                hiddenSort.name = 'sort_order_' + qid;
                hiddenSort.value = order;

                const hiddenSection = document.createElement('input');
                hiddenSection.type = 'hidden';
                hiddenSection.name = 'section_' + qid;
                hiddenSection.value = section;

                const hiddenActive = document.createElement('input');
                hiddenActive.type = 'hidden';
                hiddenActive.name = 'is_active_' + qid;
                hiddenActive.value = '1';

                row.appendChild(dragHandle);
                row.appendChild(orderBadge);
                row.appendChild(inputQ);
                row.appendChild(requiredToggle);
                row.appendChild(hiddenSort);
                row.appendChild(hiddenSection);
                row.appendChild(hiddenActive);

                // Delete button for each question row
                const deleteBtn = document.createElement('button');
                deleteBtn.type = 'button';
                // Use the same admin icon style as the top-level action buttons so it matches the pen icon
                deleteBtn.className = 'admin-management-icon-btn question-row-delete';
                deleteBtn.setAttribute('aria-label', 'Delete question');
                deleteBtn.title = 'Delete question';
                deleteBtn.style.color = '#dc3545';
                deleteBtn.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>';

                deleteBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const currentQid = row.dataset.qid;
                    const currentNewState = row.dataset.new === 'true';

                    if (!currentNewState && currentQid && !currentQid.startsWith('new-')) {
                        const url = '{{ url('admin/evaluation-questions') }}' + '/' + currentQid;
                        fetch(url, {
                            method: 'DELETE',
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        }).then(resp => {
                            if (!resp.ok && resp.status !== 204) throw new Error('Delete failed');
                            row.remove();
                            updateSectionOrder(row.parentNode);
                            showModalNotification(modal, 'Question deleted', 'success');
                        }).catch(err => {
                            console.error(err);
                            showModalNotification(modal, 'Failed to delete question', 'error');
                        });
                    } else {
                        row.remove();
                        updateSectionOrder(row.parentNode);
                    }
                });

                row.appendChild(deleteBtn);

                return row;
            };

            // Show a modal-scoped notification inside the currently open modal
            const showModalNotification = (modalEl, message, type = 'error', duration = 4000) => {
                if (!modalEl) return;
                let container = modalEl.querySelector('.modal-inline-notice');
                if (!container) {
                    container = document.createElement('div');
                    container.className = 'modal-inline-notice';
                    container.style.padding = '12px 20px';
                    container.style.margin = '0 24px 12px 24px';
                    container.style.borderRadius = '8px';
                    container.style.fontWeight = '600';
                    container.style.fontFamily = "'Sora', sans-serif";
                    modalEl.querySelector('.modal-header')?.after(container);
                }
                container.textContent = message;
                if (type === 'success') {
                    container.style.background = '#ecfdf5';
                    container.style.color = '#065f46';
                    container.style.border = '1px solid #a7f3d0';
                } else {
                    container.style.background = '#fef2f2';
                    container.style.color = '#991b1b';
                    container.style.border = '1px solid #fca5a5';
                }
                container.style.display = 'block';
                if (duration > 0) {
                    window.setTimeout(() => { container.style.display = 'none'; }, duration);
                }
            };

            const updateSectionOrder = (rowsContainer) => {
                Array.from(rowsContainer.querySelectorAll('.question-edit-row')).forEach((row, index) => {
                    const order = index + 1;
                    row.querySelector('.question-order').textContent = order;
                    row.querySelector('.sort-order-input').value = order;
                });
            };

            const attachDragHandlers = (rowsContainer) => {
                let dragged = null;

                rowsContainer.addEventListener('dragstart', (e) => {
                    const row = e.target.closest('.question-edit-row');
                    if (!row) return;
                    dragged = row;
                    row.classList.add('dragging');
                    e.dataTransfer.effectAllowed = 'move';
                });

                rowsContainer.addEventListener('dragend', (e) => {
                    if (!dragged) return;
                    dragged.classList.remove('dragging');
                    dragged = null;
                });

                rowsContainer.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    const target = e.target.closest('.question-edit-row');
                    if (!target || !dragged || target === dragged || target.parentNode !== rowsContainer) return;

                    const rect = target.getBoundingClientRect();
                    const shouldInsertBefore = e.clientY < rect.top + rect.height / 2;
                    if (shouldInsertBefore) {
                        rowsContainer.insertBefore(dragged, target);
                    } else {
                        rowsContainer.insertBefore(dragged, target.nextSibling);
                    }
                    updateSectionOrder(rowsContainer);
                });

                rowsContainer.addEventListener('drop', (e) => {
                    e.preventDefault();
                    if (!dragged) return;
                    updateSectionOrder(rowsContainer);
                });
            };

            const createSectionGroup = (section, questions, eventType, visible) => {
                const group = document.createElement('div');
                group.className = 'event-questions-section-group';
                group.setAttribute('data-section-group', section);
                group.style.display = visible ? 'block' : 'none';

                const heading = document.createElement('div');
                heading.className = 'event-questions-section-heading';
                const sectionTitle = document.createElement('div');
                sectionTitle.className = 'event-questions-section-title';
                // Map backend section names to user-facing labels
                const mapSectionName = (s) => {
                    if (s === 'Session Feedback') return '1-5 Likert Rating';
                    if (s === 'Open-ended Feedback') return 'Long Text Input';
                    return s;
                };
                sectionTitle.textContent = mapSectionName(section);
                heading.appendChild(sectionTitle);
                group.appendChild(heading);

                const rowsContainer = document.createElement('div');
                rowsContainer.className = 'event-questions-section-rows';

                // Handle both single questions and matrix questions uniformly
                const matrixQuestion = questions.find(q => q.is_matrix && q.matrix_items);
                if (matrixQuestion && (section === 'Session Feedback' || section === '1-5 Likert Rating')) {
                    // Create individual rows for each matrix item
                    matrixQuestion.matrix_items.forEach((item, idx) => {
                        const subQuestion = {
                            ...matrixQuestion,
                            question: item,
                            is_matrix: false,
                            matrix_items: null,
                            id: matrixQuestion.id + '_sub_' + idx,
                            field_key: null,
                            matrix_parent_id: matrixQuestion.id,
                            matrix_question_text: matrixQuestion.question,
                            matrix_item_index: idx,
                            is_matrix_item: true
                        };
                        rowsContainer.appendChild(createQuestionRow(subQuestion, section, idx + 1, eventType));
                    });
                } else {
                    questions.forEach((q, idx) => {
                        rowsContainer.appendChild(createQuestionRow(q, section, idx + 1, eventType));
                    });
                }

                group.appendChild(rowsContainer);

                const actions = document.createElement('div');
                actions.className = 'event-questions-section-actions';
                const addButton = document.createElement('button');
                addButton.type = 'button';
                addButton.className = 'btn btn-secondary add-section-question-btn';
                addButton.textContent = '+ Add Question';
                addButton.addEventListener('click', () => {
                    const nextOrder = rowsContainer.querySelectorAll('.question-edit-row').length + 1;
                    const newRow = createQuestionRow({
    question: '',
    type: section === 'Session Feedback' ? 'likert' : 'text',
    is_required: false,
    is_matrix: false,
    matrix_items: null
}, section, nextOrder, eventType, true);
                    rowsContainer.appendChild(newRow);
                    updateSectionOrder(rowsContainer);
                    newRow.querySelector('.question-text-input')?.focus();
                });
                actions.appendChild(addButton);
                group.appendChild(actions);

                attachDragHandlers(rowsContainer);
                return group;
            };

            const editButtons = document.querySelectorAll('.js-open-event-questions-modal, .js-eval-edit-trigger');
            let currentEventEditButton = null;

            const renderEventQuestionsModal = (questions, eventType, eventTitle) => {
                eventType = normalizeEventType(eventType);
                modal.dataset.eventType = eventType;
                modalTitle.textContent = 'Edit Evaluation Form — ' + eventTitle;
                tabsContainer.innerHTML = '';
                list.innerHTML = '';

                if (questions.length === 0) {
                    const empty = document.createElement('div');
                    empty.className = 'form-builder-empty';
                    empty.textContent = 'No questions found for this event.';
                    list.appendChild(empty);
                } else {
                    const grouped = {};
                    questions.forEach(q => {
                        const section = q.section || 'General';
                        if (!grouped[section]) grouped[section] = [];
                        grouped[section].push(q);
                    });

                    // Ensure both school_event and conference have all four sections
                    let sections;
                    sections = ['Participant Information', 'Event Details', 'Session Feedback', 'Open-ended Feedback'];
                    sections.forEach(section => {
                        if (!grouped[section]) grouped[section] = [];
                    });

                    sections.forEach((section, idx) => {
                        const tab = document.createElement('button');
                        tab.type = 'button';
                        tab.className = 'section-tab' + (idx === 0 ? ' active' : '');
                        // Display original section names in tabs
                        const displayName = section === 'Session Feedback' ? 'Session Feedback' : (section === 'Open-ended Feedback' ? 'Open-ended Feedback' : section);
                        tab.textContent = displayName;
                        tab.style.fontFamily = "'Sora', sans-serif";
                        tab.dataset.section = section;
                        tab.addEventListener('click', (e) => {
                            e.preventDefault();
                            document.querySelectorAll('.section-tab').forEach(t => t.classList.remove('active'));
                            document.querySelectorAll('.event-questions-section-group').forEach(g => g.style.display = 'none');
                            tab.classList.add('active');
                            document.querySelector('[data-section-group="' + section + '"]').style.display = 'block';
                        });

                        tabsContainer.appendChild(tab);
                    });

                    sections.forEach((section, idx) => {
                        const group = createSectionGroup(section, grouped[section] || [], eventType, idx === 0);
                        // Hide the section heading for Participant Information and Event Details
                        if (section === 'Participant Information' || section === 'Event Details') {
                            const heading = group.querySelector('.event-questions-section-heading');
                            if (heading) heading.style.display = 'none';
                        }
                        list.appendChild(group);
                    });
                }

                showModal(modal);
            };

            window.openEventQuestionsModal = function(eventId, eventType, eventTitle, questions, sourceButton = null) {
                modal.dataset.eventId = eventId;
                currentEventEditButton = sourceButton;
                renderEventQuestionsModal(questions, eventType, eventTitle);
            };

            editButtons.forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const eventId = this.dataset.eventId;
                    const eventTitle = this.dataset.eventTitle || 'Event';
                    let questions = [];
                    try { questions = JSON.parse(this.getAttribute('data-event-questions') || '[]'); } catch (err) { questions = []; }

                    modal.dataset.eventId = eventId;
                    currentEventEditButton = this;
                    const eventTypeFromData = normalizeEventType(questions[0]?.event_type || questions[0]?.eventType || 'all');
                    renderEventQuestionsModal(questions, eventTypeFromData, eventTitle);
                });
            });

            function closeEventModal() {
                hideModal(modal);
            }

            closeBtns.forEach(b => b.addEventListener('click', closeEventModal));

            modal.addEventListener('click', (e) => { if (e.target === modal) closeEventModal(); });

            if (saveBtn) {
                saveBtn.addEventListener('click', async () => {
                        const originalSaveText = saveBtn.textContent;
                        saveBtn.disabled = true;
                        saveBtn.textContent = 'Saving...';

                        try {
                            const eventType = normalizeEventType(modal.dataset.eventType || 'all');
                            const serverEventType = 'all';
                            const rows = Array.from(list.querySelectorAll('.question-edit-row'));
                            if (rows.length === 0) {
                                saveBtn.disabled = false;
                                saveBtn.textContent = originalSaveText;
                                closeEventModal();
                                return;
                            }

                            for (const row of rows) {
                                const question = row.querySelector('.question-text-input').value.trim();
                                if (!question) {
                                    saveBtn.disabled = false;
                                    saveBtn.textContent = originalSaveText;
                                    showToast('All questions must have text', 'error');
                                    return;
                                }
                            }

                            const matrixRows = rows.filter(row => row.dataset.section === 'Session Feedback' && row.dataset.isMatrixItem === 'true');
                            const nonMatrixRows = rows.filter(row => row.dataset.isMatrixItem !== 'true');

                    if ((eventType === 'school_event' || eventType === 'conference') && matrixRows.length > 0) {
                        const matrixItems = matrixRows
                            .sort((a, b) => parseInt(a.querySelector('.sort-order-input').value, 10) - parseInt(b.querySelector('.sort-order-input').value, 10))
                            .map(row => row.querySelector('.question-text-input').value.trim())
                            .filter(text => text.length > 0);

                        const matrixParentId = matrixRows[0].dataset.matrixParentId;
                        const matrixQuestionText = matrixRows[0].dataset.matrixQuestionText || 'Session Feedback';

                        if (matrixParentId) {
                            const fd = new FormData();
                            fd.append('_token', csrfToken);
                            fd.append('_method', 'PATCH');
                            fd.append('question', matrixQuestionText);
                            fd.append('type', 'likert');
                            fd.append('event_type', serverEventType);
                            fd.append('event_id', modal.dataset.eventId || '');
                            fd.append('sort_order', '1');
                            fd.append('section', 'Session Feedback');
                            fd.append('is_required', '1');
                            fd.append('is_active', '1');
                            fd.append('matrix_items', JSON.stringify(matrixItems));

                            const url = '{{ url('admin/evaluation-questions') }}' + '/' + matrixParentId;
                            const resp = await fetch(url, {
                                method: 'POST',
                                body: fd,
                                credentials: 'same-origin',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });

                            if (!resp.ok) {
                                let msg = 'Failed to save questions';
                                try { const data = await resp.json(); msg = data.message || data.error || msg; } catch (_) {}
                                throw new Error(msg);
                            }
                        }
                    }

                    const promises = nonMatrixRows.map(row => {
                        const id = row.dataset.qid;
                        const isNew = row.dataset.new === 'true';
                        const question = row.querySelector('.question-text-input').value.trim();
                        const sort_order = row.querySelector('.sort-order-input').value;
                        const section = row.dataset.section;
                        const is_required = row.querySelector('input[name="is_required_' + id + '"]').checked ? 1 : 0;
                        const is_active = 1;
                        const rawType = (row.dataset.type || 'text').toLowerCase();
                        const allowedTypes = ['likert', 'rating', 'text', 'textarea'];
                        const normalizedType = allowedTypes.includes(rawType) ? rawType : 'text';

                        const fd = new FormData();
                        fd.append('_token', csrfToken);
                        if (!isNew) {
                            fd.append('_method', 'PATCH');
                        }
                        fd.append('question', question);
                        fd.append('type', normalizedType);
                        fd.append('event_type', row.dataset.eventType || serverEventType);
                        fd.append('sort_order', sort_order);
                        fd.append('section', section);
                        fd.append('is_required', is_required);
                        fd.append('is_active', is_active);
                        fd.append('event_id', modal.dataset.eventId || '');

                        const url = isNew
                            ? '{{ url('admin/evaluation-questions') }}'
                            : '{{ url('admin/evaluation-questions') }}' + '/' + id;

                        return fetch(url, {
                            method: 'POST',
                            body: fd,
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                            .then(async (resp) => {
                                if (!resp.ok) {
                                    let msg = 'Failed to save questions';
                                    try { const data = await resp.json(); msg = data.message || data.error || msg; } catch (_) {}
                                    throw new Error(msg);
                                }
                                const data = await resp.json();
                                if (data.question) {
                                    row.dataset.qid = String(data.question.id);
                                    row.dataset.new = 'false';
                                }
                                return resp;
                            });
                    });

                    await Promise.all(promises);

                    const updatedQuestions = [];
                    const matrixGroups = {};

                    rows.forEach((row) => {
                        if (row.dataset.isMatrixItem === 'true') {
                            const parentId = row.dataset.matrixParentId;
                            const parentKey = parentId || 'matrix';
                            const questionText = row.dataset.matrixQuestionText || 'Session Feedback';
                            if (!matrixGroups[parentKey]) {
                                matrixGroups[parentKey] = {
                                    id: parentId,
                                    question: questionText,
                                    field_key: null,
                                    type: 'likert',
                                    placeholder: null,
                                    help_text: null,
                                    is_required: true,
                                    is_active: true,
                                    sort_order: 1,
                                    event_id: modal.dataset.eventId || '',
                                    event_type: serverEventType,
                                    is_guest_question: false,
                                    is_matrix: true,
                                    matrix_items: []
                                };
                            }
                            matrixGroups[parentKey].matrix_items.push(row.querySelector('.question-text-input').value.trim());
                            return;
                        }

                        updatedQuestions.push({
                            id: row.dataset.qid?.startsWith('new-') ? null : Number(row.dataset.qid),
                            question: row.querySelector('.question-text-input').value.trim(),
                            type: row.dataset.type || 'text',
                            section: row.dataset.section,
                            sort_order: Number(row.querySelector('.sort-order-input').value),
                            is_required: row.querySelector('input[name="is_required_' + row.dataset.qid + '"]').checked,
                            is_active: true,
                            event_id: modal.dataset.eventId || '',
                            event_type: row.dataset.eventType || serverEventType,
                        });
                    });

                    Object.values(matrixGroups).forEach((group) => {
                        updatedQuestions.push(group);
                    });

                    if (currentEventEditButton) {
                        currentEventEditButton.dataset.eventQuestions = JSON.stringify(updatedQuestions);
                    }

                    showToast('Questions saved', 'success');
                    setTimeout(() => { closeEventModal(); }, 1500);
                } catch (err) {
                    console.error('Save error:', err);
                    showToast('Error: ' + err.message, 'error');
                } finally {
                    saveBtn.disabled = false;
                    saveBtn.textContent = originalSaveText;
                }
            });
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal.classList.contains('is-visible')) {
                    closeEventModal();
                }
            });
        })();


    </script>
@endsection