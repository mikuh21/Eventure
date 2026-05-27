@extends('layouts.app')

@section('title', 'Event Staff - Eventure')

@push('styles')
    @include('admin.partials.management-styles')
    <style>
        .admin-management-icon-btn.deactivate:hover {
            background: #fef2f2;
            color: #dc3545;
        }

        .admin-management-icon-btn.reactivate:hover {
            background: #ecfdf5;
            color: #15803d;
        }

        .btn-create-form,
        .btn-register-event-staff {
            background: #ffffff;
            color: var(--color-midnight);
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            font-family: 'Sora', sans-serif;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-create-form:hover,
        .btn-register-event-staff:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        .btn-create-form:focus-visible,
        .btn-register-event-staff:focus-visible {
            outline: 2px solid rgba(59, 130, 246, 0.5);
            outline-offset: 2px;
        }

        #registerEventStaffCancel {
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

        #registerEventStaffCancel:hover {
            background: var(--color-ice-white);
            border-color: var(--color-steel-blue);
        }

        #registerEventStaffSubmit {
            font-family: 'Sora', sans-serif;
            border-radius: 8px;
            padding: 9px 18px;
            border: 1px solid var(--color-ocean);
            background: var(--color-ocean);
            color: #ffffff;
            transition: background 160ms ease, border-color 160ms ease;
        }

        #registerEventStaffSubmit:hover {
            background: var(--color-midnight);
            border-color: var(--color-midnight);
        }

        #staffStatusModalConfirm.reactivate-action {
            background: #16a34a;
            border-color: #16a34a;
            color: #ffffff;
        }

        #staffStatusModalConfirm.reactivate-action:hover {
            background: #15803d;
            border-color: #15803d;
        }

        .admin-modal-close {
            position: absolute;
            top: 18px;
            right: 18px;
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

        .admin-modal-close:hover {
            background: var(--color-ice-white);
            border-color: var(--color-steel-blue);
            color: var(--color-midnight);
        }


        .modal-floating-label {
            position: fixed;
            top: 20px;
            right: 20px;
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
            z-index: 1200;
            pointer-events: none;
            animation: toast-in 180ms ease-out;
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
            margin: 0;
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
    </style>
@endpush

@section('content')
    <div class="admin-management-page">
        <div class="admin-management-header">
            <div>
                <h1 class="admin-management-title">Event Staff</h1>
            </div>
            <div>
                <button type="button" class="btn btn-create-form btn-register-event-staff" id="registerEventStaffBtn">
                    Register Event Staff
                </button>
            </div>
        </div>

        <div class="admin-management-grid">
            <div class="admin-management-stat">
                <div class="admin-management-stat-label">Total Staff</div>
                <div class="admin-management-stat-value">{{ $staffStats['total'] }}</div>
            </div>
            <div class="admin-management-stat">
                <div class="admin-management-stat-label">Active</div>
                <div class="admin-management-stat-value">{{ $staffStats['active'] }}</div>
            </div>
            <div class="admin-management-stat">
                <div class="admin-management-stat-label">Deactivated</div>
                <div class="admin-management-stat-value">{{ $staffStats['deactivated'] }}</div>
            </div>
        </div>
    </div>

    <div class="admin-management-page">
        <div class="admin-management-panel-header">
            <h2 class="admin-management-panel-title">Event Staff Management</h2>
        </div>

        <div class="admin-management-table-wrap">
            <table class="admin-management-table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date Registered</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            <div style="font-weight: 700;">{{ $user->name }}</div>
                        </td>
                        <td>
                            <div>{{ $user->email }}</div>
                        </td>
                        <td>
                            <div>{{ $user->created_at?->format('F j, Y') ?? '—' }}</div>
                        </td>
                        <td>
                            <span class="admin-management-badge status-{{ $user->approval_status === 'disapproved' ? 'inactive' : 'active' }}">
                                {{ $user->approval_status === 'disapproved' ? 'Deactivated' : 'Active' }}
                            </span>
                        </td>
                        <td>
                            <div style="display: inline-flex; gap: 8px; align-items: center;">
                                <button type="button"
                                        class="admin-management-icon-btn {{ $user->approval_status === 'disapproved' ? 'reactivate' : 'deactivate' }} staff-status-toggle-btn"
                                        data-action="{{ route('admin.users.toggle-status', $user) }}"
                                        data-name="{{ $user->name }}"
                                        data-status="{{ $user->approval_status === 'disapproved' ? 'reactivate' : 'deactivate' }}"
                                        aria-label="{{ $user->approval_status === 'disapproved' ? 'Reactivate staff member' : 'Deactivate staff member' }}"
                                        title="{{ $user->approval_status === 'disapproved' ? 'Reactivate staff member' : 'Deactivate staff member' }}">
                                    @if ($user->approval_status === 'disapproved')
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5" /></svg>
                                    @else
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="8" /><path d="M8 8l8 8" /></svg>
                                    @endif
                                </button>

                                <button type="button"
                                        class="admin-management-icon-btn is-danger js-user-delete-trigger"
                                        data-form-id="delete-user-form-{{ $user->id }}"
                                        data-user-name="{{ $user->name }}"
                                        aria-label="Delete staff member"
                                        title="Delete staff member">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m-8 0 1 12a1 1 0 0 0 1 .9h6a1 1 0 0 0 1-.9l1-12" />
                                    </svg>
                                </button>
                            </div>

                            <form id="delete-user-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="admin-management-empty">No event staff found.</div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="staffStatusModal" class="delete-confirm-modal" aria-hidden="true" role="dialog" aria-modal="true">
        <div class="delete-confirm-panel">
            <h2 id="staffStatusModalTitle" class="delete-confirm-title">Confirm action</h2>
            <p class="delete-confirm-body">
                <span id="staffStatusModalMessage"></span>
            </p>
            <div class="delete-confirm-actions">
                <button type="button" class="btn-delete-cancel" id="staffStatusModalCancel">Cancel</button>
                <button type="button" class="btn-delete-confirm" id="staffStatusModalConfirm">Yes, Continue</button>
            </div>
        </div>
    </div>

    <div id="deleteStaffModal" class="delete-confirm-modal" aria-hidden="true" role="dialog" aria-modal="true">
        <div class="delete-confirm-panel">
            <h2 id="deleteStaffModalTitle" class="delete-confirm-title">Delete staff member</h2>
            <p class="delete-confirm-body">
                <span id="deleteStaffModalMessage"></span>
            </p>
            <div class="delete-confirm-actions">
                <button type="button" class="btn-delete-cancel" id="deleteStaffModalCancel">Cancel</button>
                <button type="button" class="btn-delete-confirm" id="deleteStaffModalConfirm">Yes, Delete</button>
            </div>
        </div>
    </div>

    <form id="staffStatusToggleForm" method="POST" style="display:none;">
        @csrf
        @method('PATCH')
    </form>

    <div id="registerEventStaffModal" class="delete-confirm-modal" aria-hidden="true" role="dialog" aria-modal="true">
                <div class="delete-confirm-panel" style="max-width: 500px; position: relative;">
                    <button type="button" class="admin-modal-close" id="closeRegisterEventStaffModal" aria-label="Close register event staff modal">&times;</button>
                    <h2 class="delete-confirm-title">Register Event Staff</h2>
                    <form id="registerEventStaffForm" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label for="staffName" style="display: block; margin-bottom: 4px; font-weight: 600; color: var(--color-midnight); font-family: 'Sora', sans-serif;">Full Name</label>
                    <input type="text" id="staffName" name="name" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--color-sky); border-radius: 6px; font-size: 14px; font-family: 'Sora', sans-serif;">
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="staffEmail" style="display: block; margin-bottom: 4px; font-weight: 600; color: var(--color-midnight); font-family: 'Sora', sans-serif;">Email</label>
                    <input type="email" id="staffEmail" name="email" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--color-sky); border-radius: 6px; font-size: 14px; font-family: 'Sora', sans-serif;">
                </div>
                <div id="registrationError" style="display: none; margin-bottom: 16px; padding: 8px 12px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; color: #dc3545; font-size: 14px;"></div>
                <div class="delete-confirm-actions">
                    <button type="button" class="btn-delete-cancel" id="registerEventStaffCancel">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="registerEventStaffSubmit">Register</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const modal = document.getElementById('staffStatusModal');
            const message = document.getElementById('staffStatusModalMessage');
            const cancelBtn = document.getElementById('staffStatusModalCancel');
            const confirmBtn = document.getElementById('staffStatusModalConfirm');
            const form = document.getElementById('staffStatusToggleForm');
            let currentAction = null;

            document.body.addEventListener('click', function (e) {
                const btn = e.target.closest('.staff-status-toggle-btn');
                if (!btn) return;

                e.preventDefault();
                currentAction = btn.dataset.action;
                const action = btn.dataset.status;
                const name = btn.dataset.name;

                message.textContent = action === 'deactivate'
                    ? `Are you sure you want to deactivate ${name}?`
                    : `Are you sure you want to reactivate ${name}?`;

                if (confirmBtn) {
                    confirmBtn.classList.toggle('reactivate-action', action === 'reactivate');
                }

                modal.classList.add('is-visible');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            });

            const closeModal = () => {
                modal.classList.remove('is-visible');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                currentAction = null;
            };

            cancelBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.classList.contains('is-visible')) {
                    closeModal();
                }
            });
            confirmBtn.addEventListener('click', function () {
                if (!currentAction) return;
                form.action = currentAction;
                form.submit();
            });
        })();
    </script>

    <script>
        (function () {
            const registerModal = document.getElementById('registerEventStaffModal');
            const registerForm = document.getElementById('registerEventStaffForm');
            const registerBtn = document.getElementById('registerEventStaffBtn');
            const cancelBtn = document.getElementById('registerEventStaffCancel');
            const submitBtn = document.getElementById('registerEventStaffSubmit');
            const errorDiv = document.getElementById('registrationError');

            registerBtn.addEventListener('click', function () {
                registerForm.reset();
                errorDiv.style.display = 'none';
                registerModal.classList.add('is-visible');
                registerModal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            });

            const closeRegisterModal = () => {
                registerModal.classList.remove('is-visible');
                registerModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            };

            cancelBtn.addEventListener('click', closeRegisterModal);
            document.getElementById('closeRegisterEventStaffModal').addEventListener('click', closeRegisterModal);
            registerModal.addEventListener('click', function (e) {
                if (e.target === registerModal) closeRegisterModal();
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && registerModal.classList.contains('is-visible')) {
                    closeRegisterModal();
                }
            });

            const showSuccessToast = (message) => {
                let toast = document.createElement('div');
                toast.className = 'modal-floating-label modal-floating-success modal-toast';
                toast.textContent = message;
                document.body.appendChild(toast);
                window.setTimeout(() => {
                    toast.classList.add('is-visible');
                }, 10);

                window.setTimeout(() => {
                    toast.classList.remove('is-visible');
                    toast.classList.add('is-hiding');
                    window.setTimeout(() => {
                        if (toast && toast.parentNode) {
                            toast.parentNode.removeChild(toast);
                        }
                    }, 220);
                }, 3200);
            };

            registerForm.addEventListener('submit', function (e) {
                e.preventDefault();
                errorDiv.style.display = 'none';
                submitBtn.disabled = true;
                submitBtn.textContent = 'Registering...';

                const formData = new FormData(registerForm);

                fetch(registerForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(async (response) => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw data;
                    }
                    return data;
                })
                .then(data => {
                    if (data.errors) {
                        errorDiv.textContent = Object.values(data.errors).flat().join(' ');
                        errorDiv.style.display = 'block';
                        return;
                    }

                    showSuccessToast(data.message || 'Event Staff registered successfully. Credentials have been sent to their email.');
                    closeRegisterModal();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1400);
                })
                .catch(error => {
                    const message = error.errors ? Object.values(error.errors).flat().join(' ') : (error.message || 'An error occurred. Please try again.');
                    errorDiv.textContent = message;
                    errorDiv.style.display = 'block';
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Register';
                });
            });

            const deleteModal = document.getElementById('deleteStaffModal');
            const deleteMessage = document.getElementById('deleteStaffModalMessage');
            const deleteCancel = document.getElementById('deleteStaffModalCancel');
            const deleteConfirm = document.getElementById('deleteStaffModalConfirm');
            let currentDeleteFormId = null;

            document.body.addEventListener('click', function (e) {
                const deleteBtn = e.target.closest('.js-user-delete-trigger');
                if (!deleteBtn) return;

                e.preventDefault();
                currentDeleteFormId = deleteBtn.dataset.formId;
                const name = deleteBtn.dataset.userName;
                deleteMessage.textContent = `Are you sure you want to delete ${name}? This action cannot be undone.`;

                deleteModal.classList.add('is-visible');
                deleteModal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            });

            const closeDeleteModal = () => {
                deleteModal.classList.remove('is-visible');
                deleteModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                currentDeleteFormId = null;
            };

            deleteCancel.addEventListener('click', closeDeleteModal);
            deleteModal.addEventListener('click', function (e) {
                if (e.target === deleteModal) closeDeleteModal();
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && deleteModal.classList.contains('is-visible')) {
                    closeDeleteModal();
                }
            });
            deleteConfirm.addEventListener('click', function () {
                if (!currentDeleteFormId) {
                    return;
                }

                const deleteForm = document.getElementById(currentDeleteFormId);
                if (deleteForm) {
                    deleteForm.submit();
                }
            });
        })();
    </script>
@endsection