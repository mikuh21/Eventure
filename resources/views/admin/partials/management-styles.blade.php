<style>
    :root {
        --color-ice-white: #E8F4FD;
        --color-sky: #BFDFFF;
        --color-steel-blue: #5BA4CF;
        --color-ocean: #1B6CA8;
        --color-midnight: #0A2342;
        --color-coral: #FF6B35;
    }

    .admin-management-page {
        background: var(--color-ice-white);
        border: 1px solid var(--color-sky);
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(10, 35, 66, 0.08);
    }

    .admin-management-page + .admin-management-page {
        margin-top: 18px;
    }

    .admin-management-title {
        margin: 0;
        color: var(--color-midnight);
        font-size: 1.65rem;
        font-weight: 700;
    }

    .admin-management-subtitle {
        margin: 8px 0 0;
        color: var(--color-ocean);
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .admin-management-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .admin-management-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .admin-management-stat {
        background: #ffffff;
        border: 1px solid var(--color-sky);
        border-radius: 10px;
        padding: 14px 16px;
        box-shadow: 0 2px 8px rgba(10, 35, 66, 0.06);
    }

    .admin-management-stat-label {
        color: var(--color-ocean);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 600;
    }

    .admin-management-stat-value {
        margin-top: 6px;
        color: var(--color-midnight);
        font-size: 30px;
        line-height: 1;
        font-weight: 700;
    }

    .admin-management-panel {
        background: #ffffff;
        border: 1px solid var(--color-sky);
        border-radius: 12px;
        padding: 18px;
    }

    .admin-management-panel + .admin-management-panel {
        margin-top: 16px;
    }

    .admin-management-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 14px;
    }

    .admin-management-panel-title {
        margin: 0;
        color: var(--color-midnight);
        font-size: 1.05rem;
        font-weight: 700;
    }

    .admin-management-panel-note {
        color: var(--color-ocean);
        font-size: 13px;
    }

    .admin-management-table-wrap {
        overflow-x: auto;
        border: 1px solid var(--color-sky);
        border-radius: 10px;
        background: #ffffff;
    }

    .admin-management-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 0;
        background: #ffffff;
    }

    .admin-management-table thead th {
        background: var(--color-midnight);
        color: #ffffff;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.05em;
        font-weight: 600;
        padding: 14px 16px;
    }

    .admin-management-table tbody td {
        border-bottom: 1px solid var(--color-sky);
        padding: 14px 16px;
        color: var(--color-midnight);
        font-size: 13px;
        vertical-align: middle;
    }

    .admin-management-table tbody tr:last-child td {
        border-bottom: none;
    }

    .admin-management-table tbody tr:hover {
        background: var(--color-ice-white);
    }

    .admin-management-metadata {
        color: var(--color-ocean);
        font-size: 13px;
        margin-top: 4px;
        line-height: 1.45;
    }

    .admin-management-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        line-height: 1.3;
        white-space: nowrap;
    }

    .admin-management-badge.role-admin {
        background: var(--color-midnight);
        color: var(--color-sky);
    }

    .admin-management-badge.role-event-staff {
        background: var(--color-sky);
        color: var(--color-ocean);
    }

    .admin-management-badge.role-user {
        background: #eef2ff;
        color: #4338ca;
    }

    .admin-management-badge.status-approved,
    .admin-management-badge.status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .admin-management-badge.status-pending {
        background: #fef9c3;
        color: #854d0e;
    }

    .admin-management-badge.status-disapproved,
    .admin-management-badge.status-hidden {
        background: #fee2e2;
        color: #991b1b;
    }

    .admin-management-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .admin-management-actions.admin-management-actions--nowrap {
        flex-wrap: nowrap;
        white-space: nowrap;
    }

    .admin-management-icon-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid var(--color-sky);
        background: #ffffff;
        color: var(--color-ocean);
        text-decoration: none;
        cursor: pointer;
        transition: background 180ms ease, border-color 180ms ease, color 180ms ease;
    }

    .admin-management-icon-btn:hover {
        background: var(--color-ice-white);
        border-color: var(--color-steel-blue);
        color: var(--color-midnight);
    }

    .admin-management-icon-btn svg {
        width: 15px;
        height: 15px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
    }

    .admin-management-icon-btn.is-danger {
        border-color: #fca5a5;
        background: #fff1f2;
        color: #b91c1c;
    }

    .admin-management-icon-btn.is-danger:hover {
        border-color: #ef4444;
        background: #fee2e2;
        color: #991b1b;
    }

    .admin-approval-form {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        flex-wrap: nowrap;
        width: auto;
        max-width: 100%;
    }

    .admin-approval-form .admin-management-select {
        width: auto;
        min-width: 156px;
        max-width: 170px;
        padding: 8px 10px;
        font-size: 13px;
    }

    .admin-approval-save {
        padding: 8px 12px;
        font-size: 13px;
        line-height: 1.2;
        white-space: nowrap;
        font-family: 'Sora', sans-serif;
    }

    .admin-management-inline-note {
        color: var(--color-ocean);
        font-size: 13px;
        line-height: 1.4;
        white-space: nowrap;
    }

    .admin-content .btn.admin-management-btn-primary {
        background: var(--color-midnight);
        border-color: var(--color-midnight);
        color: #ffffff;
    }

    .admin-content .btn.admin-management-btn-primary:hover {
        background: var(--color-ocean);
        border-color: var(--color-ocean);
        color: #ffffff;
    }

    .admin-content .btn.admin-management-btn-danger {
        background: #dc3545;
        border-color: #dc3545;
        color: #ffffff;
    }

    .admin-content .btn.admin-management-btn-danger:hover {
        background: #c82333;
        border-color: #bd2130;
        color: #ffffff;
    }

    .admin-content .btn.admin-management-cancel-btn {
        text-align: center;
        justify-content: center;
    }

    .admin-management-select,
    .admin-management-input,
    .admin-management-textarea {
        width: 100%;
        border: 1px solid var(--color-sky);
        border-radius: 8px;
        padding: 10px 12px;
        background: #ffffff;
        color: var(--color-midnight);
        font-size: 14px;
        font-family: 'Sora', sans-serif;
        box-sizing: border-box;
    }

    .admin-management-select {
        min-width: 190px;
    }

    .admin-management-select:focus,
    .admin-management-input:focus,
    .admin-management-textarea:focus {
        outline: none;
        border-color: var(--color-steel-blue);
        box-shadow: 0 0 0 3px rgba(91, 164, 207, 0.18);
    }

    .admin-management-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .admin-management-field {
        display: grid;
        gap: 6px;
    }

    .admin-management-field.is-full {
        grid-column: 1 / -1;
    }

    .admin-management-label {
        color: var(--color-midnight);
        font-size: 13px;
        font-weight: 600;
    }

    .admin-management-helper {
        color: var(--color-ocean);
        font-size: 12px;
        line-height: 1.45;
    }

    .admin-management-toggle-row {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        padding-top: 4px;
    }

    .admin-management-checkbox {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--color-midnight);
        font-size: 13px;
        font-weight: 600;
    }

    .admin-management-checkbox input {
        width: auto;
        margin: 0;
    }

    .admin-management-empty {
        text-align: center;
        padding: 36px 16px;
        color: var(--color-ocean);
    }

    .admin-management-empty h3 {
        margin: 0 0 8px;
        color: var(--color-midnight);
        font-size: 18px;
    }

    .admin-management-empty p {
        margin: 0;
        color: var(--color-ocean);
        font-size: 14px;
    }

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

    @media (max-width: 960px) {
        .admin-management-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
            align-items: stretch;
        }

        .admin-management-stat {
            padding: 12px 10px;
            text-align: center;
            min-width: 0;
            box-sizing: border-box;
        }

        .admin-management-stat-label {
            font-size: 0.66rem;
            line-height: 1.25;
        }

        .admin-management-stat-value {
            margin-top: 8px;
            font-size: 1.2rem;
        }
    }

    @media (max-width: 900px) {
        .admin-management-form-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .admin-management-page {
            padding: 16px;
        }

        .admin-management-title {
            font-size: 1.35rem;
        }

        .admin-management-grid {
            gap: 6px;
        }

        .admin-management-stat {
            padding: 10px 8px;
        }

        .admin-management-stat-label {
            font-size: 0.6rem;
        }

        .admin-management-stat-value {
            font-size: 1.02rem;
        }

        .admin-management-table thead th,
        .admin-management-table tbody td {
            padding: 12px 10px;
        }

        .admin-management-actions {
            align-items: stretch;
        }

        .admin-management-actions .btn,
        .admin-management-actions form,
        .admin-management-actions .admin-management-select {
            width: 100%;
        }

        .admin-content .btn.admin-management-cancel-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .admin-approval-form {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
        }

        .admin-approval-form .admin-management-select,
        .admin-approval-save,
        .admin-management-inline-note {
            width: 100%;
            max-width: 100%;
        }

        .admin-management-inline-note {
            white-space: normal;
        }
    }
</style>