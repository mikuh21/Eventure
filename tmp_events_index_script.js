
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

            if (!searchInput || tableRows.length === 0) {
                return;
            }

            const normalize = (value) => value.toLowerCase().trim();

            const filterRowsByTitle = () => {
                const query = normalize(searchInput.value);
                const terms = query === '' ? [] : query.split(/\s+/).filter(Boolean);
                let visibleCount = 0;

                tableRows.forEach((row) => {
                    const title = normalize(row.getAttribute('data-event-title') || '');
                    const isMatch = terms.length === 0 || terms.every((term) => title.includes(term));
                    row.style.display = isMatch ? '' : 'none';

                    if (isMatch) {
                        visibleCount += 1;
                    }
                });

                if (liveSearchEmpty) {
                    liveSearchEmpty.style.display = visibleCount === 0 ? '' : 'none';
                }

                if (showingCount) {
                    const total = Number(showingCount.getAttribute('data-total')) || tableRows.length;
                    showingCount.textContent = 'Showing ' + visibleCount + ' of ' + total + ' event(s)';
                }
            };

            searchInput.addEventListener('input', filterRowsByTitle);
            filterRowsByTitle();
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
            var editEventDate = document.getElementById('editEventDate');
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
                editForm.action = '""/' + eventData.eventId;

                // Populate form fields
                editEventId.value = eventData.eventId;
                editType.value = eventData.eventType;
                editEventTitle.value = eventData.eventTitleField || eventData.eventTitle || '';
                editConferenceTitle.value = eventData.conferenceTitle || '';
                editTheme.value = eventData.theme || '';
                editKeywords.value = eventData.keywords || '';
                editDescription.value = eventData.description || '';
                editAttendanceType.value = eventData.attendanceType || 'face_to_face';
                editEventDate.value = eventData.eventDate || '';
                editStartRegistration.value = eventData.startRegistration || '';
                editEndRegistration.value = eventData.endRegistration || '';
                editLocation.value = eventData.location || '';

                // Clear file inputs
                editPoster.value = '';
                editTemplateFile.value = '';

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
                        eventDate: editButton.dataset.eventDate,
                        startRegistration: editButton.dataset.startRegistration,
                        endRegistration: editButton.dataset.endRegistration,
                        location: editButton.dataset.location
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
                            // Success - close modal and reload page
                            closeEditModal();
                            location.reload();
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

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && editModal && editModal.classList.contains('is-visible')) {
                    closeEditModal();
                }
            });
        })();
    