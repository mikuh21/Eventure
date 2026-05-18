@extends('layouts.app')

@section('title', 'Open Registration Events')

@push('styles')
    @include('admin.partials.management-styles')
    <style>
        /* Modal Styles */
        .admin-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(10, 35, 66, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            animation: modal-fade-in 200ms ease-out;
        }

        .admin-modal {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(10, 35, 66, 0.3);
            max-width: 420px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .admin-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid var(--color-sky);
        }

        .admin-modal-title {
            margin: 0;
            color: var(--color-midnight);
            font-size: 1.25rem;
            font-weight: 700;
        }

        .admin-modal-close {
            background: none;
            border: none;
            font-size: 24px;
            color: var(--color-ocean);
            cursor: pointer;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: background 180ms ease;
        }

        .admin-modal-close:hover {
            background: var(--color-ice-white);
        }

        .admin-modal-body {
            padding: 24px;
        }

        .admin-modal-body p {
            margin: 0 0 12px 0;
            color: var(--color-midnight);
            font-size: 15px;
            line-height: 1.5;
        }

        .admin-modal-warning {
            color: #dc3545 !important;
            font-weight: 600;
            margin: 0 !important;
        }

        .admin-modal-footer {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            padding: 20px 24px;
            border-top: 1px solid var(--color-sky);
        }

        @keyframes modal-fade-in {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
@endpush

@section('content')
    <div class="admin-management-page">
        <div class="admin-management-header">
            <div>
                <h1 class="admin-management-title">Evaluation Forms</h1>
            </div>
        </div>

        <div class="admin-management-grid">
            <div class="admin-management-stat">
                <div class="admin-management-stat-label">Open Events</div>
                <div class="admin-management-stat-value">{{ $events->count() }}</div>
            </div>
            <div class="admin-management-stat">
                <div class="admin-management-stat-label">Participants</div>
                <div class="admin-management-stat-value">{{ $events->sum('participants_count') }}</div>
            </div>
            <div class="admin-management-stat">
                <div class="admin-management-stat-label">Guests</div>
                <div class="admin-management-stat-value">{{ $events->sum('guests_count') }}</div>
            </div>
        </div>
    </div>

    <div class="admin-management-page">
        <div class="admin-management-panel-header">
            <h2 class="admin-management-panel-title">Current Open Registration Events</h2>
        </div>

        @if($events->isEmpty())
            <div class="admin-management-empty">
                <h3>No events currently have open registration.</h3>
                <p>Open registration events will appear here automatically when registration is active.</p>
            </div>
        @else
            <div class="admin-management-table-wrap">
                <table class="admin-management-table">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Type</th>
                            <th>Event Date</th>
                            <th>Participants</th>
                            <th>Guests</th>
                            <th>Registration Ends</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                            <tr>
                                <td>
                                    <strong>{{ $event->title }}</strong>
                                    <div class="admin-management-metadata">{{ $event->location }}</div>
                                </td>
                                <td>{{ $event->type === 'conference' ? 'Conference' : 'School Event' }}</td>
                                <td>{{ $event->dateRangeLabel() }}</td>
                                <td>{{ $event->participants_count }}</td>
                                <td>{{ $event->guests_count }}</td>
                                <td>
                                    @if($event->end_registration)
                                        {{ $event->end_registration->format('F d, Y') }}
                                    @elseif($event->event_date)
                                        {{ $event->event_date->format('F d, Y') }}
                                    @else
                                        Open
                                    @endif
                                </td>
                                <td>
                                    <div class="admin-management-actions admin-management-actions--nowrap" style="display:flex; gap:8px; align-items:center;">
                                        <form action="{{ route('admin.event-evaluation-forms.' . ($event->isEvaluationFormEnabled() ? 'disable' : 'enable'), $event) }}" method="POST" style="margin:0;">
                                            @csrf
                                            <button type="submit" class="btn admin-management-btn-primary admin-management-form-btn" style="padding: 6px 10px; font-size: 13px;" title="{{ $event->isEvaluationFormEnabled() ? 'Disable evaluation form' : 'Enable evaluation form' }}">
                                                {{ $event->isEvaluationFormEnabled() ? 'Disable Form' : 'Enable Form' }}
                                            </button>
                                        </form>

                                        <a class="admin-management-icon-btn" href="{{ route('admin.event-evaluation-forms.index', ['event_id' => $event->id]) }}" aria-label="Edit evaluation form" title="Edit evaluation form">
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20h9"></path><path d="M16.5 3.5a2.12 2.12 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5Z"></path></svg>
                                        </a>

                                        <button type="button" class="admin-management-icon-btn is-danger" aria-label="Delete event" title="Delete event" onclick="showDeleteModal('{{ route('events.destroy', $event) }}', '{{ $event->title }}', 'event')">
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16"></path><path d="M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path><path d="m7 7 1 12a1 1 0 0 0 1 .9h6a1 1 0 0 0 1-.9L17 7"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="admin-modal-overlay" style="display: none;">
    <div class="admin-modal">
        <div class="admin-modal-header">
            <h3 class="admin-modal-title">Confirm Deletion</h3>
            <button type="button" class="admin-modal-close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="admin-modal-body">
            <p id="deleteModalMessage"></p>
            <p class="admin-modal-warning">This action cannot be undone.</p>
        </div>
        <div class="admin-modal-footer">
            <button type="button" class="btn admin-management-btn-secondary btn-cancel" onclick="closeDeleteModal()">Cancel</button>
            <button type="button" class="btn admin-management-btn-danger" id="confirmDeleteBtn">Delete</button>
        </div>
    </div>
</div>

<script>
    // Delete Modal Functions
    function showDeleteModal(actionUrl, itemName, itemType) {
        const modal = document.getElementById('deleteModal');
        const message = document.getElementById('deleteModalMessage');
        const confirmBtn = document.getElementById('confirmDeleteBtn');

        message.textContent = `Are you sure you want to delete the ${itemType} "${itemName}"?`;
        confirmBtn.onclick = function() {
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = actionUrl;
            form.style.display = 'none';

            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken.getAttribute('content');
                form.appendChild(csrfInput);
            }

            // Add method override for DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();

            closeDeleteModal();
        };

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.style.display = 'none';
        document.body.style.overflow = ''; // Restore scrolling
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('deleteModal').style.display === 'flex') {
            closeDeleteModal();
        }
    });
</script>
