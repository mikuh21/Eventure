
        // Toast notification system
        function showToast(message, type = 'info', duration = 4000) {
            const toastContainer = document.getElementById('toastContainer');
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

        // Open form buttons
        document.addEventListener('DOMContentLoaded', function () {
            const openButtons = document.querySelectorAll('.open-form-btn');
            openButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    const hasParticipants = this.getAttribute('data-has-participants') === 'true';
                    const eventTitle = this.getAttribute('data-event-title') || 'this event';
                    if (!hasParticipants) {
                        showToast('Cannot open evaluation form: No attended participants found for ' + eventTitle, 'warning', 5000);
                        e.preventDefault();
                        return;
                    }
                    showToast('Opening evaluation form and sending emails to attended participants...', 'info', 3000);
                    this.innerHTML = '<span style="display: inline-block; width: 12px; height: 12px; border: 2px solid #ffffff; border-radius: 50%; border-top-color: transparent; animation: spin 1s linear infinite; margin-right: 8px;"></span>Opening...';
                    this.disabled = true;
                    setTimeout(() => {
                        this.disabled = false;
                        this.innerHTML = 'Open Form';
                    }, 5000);
                });
            });
        });

        const spinStyle = document.createElement('style');
        spinStyle.textContent = `// spin { to { transform: rotate(360deg); } }`;
        document.head.appendChild(spinStyle);

        // ── Live Search Filter ──────────────────────────────────────────────
        (function () {
            const searchInput = document.querySelector('input[name="search"]');
            const cards = Array.from(document.querySelectorAll('.evaluation-form-card[data-event-title]'));
            const showingCount = document.getElementById('efShowingCount');

            if (!searchInput || cards.length === 0) return;

            const normalize = (value) => value.toLowerCase().trim();

            const filterCards = () => {
                const query = normalize(searchInput.value);
                const terms = query === '' ? [] : query.split(/\s+/).filter(Boolean);
                let visibleCount = 0;

                cards.forEach((card) => {
                    const title = normalize(card.getAttribute('data-event-title') || '');
                    const isMatch = terms.length === 0 || terms.every((term) => title.includes(term));
                    card.style.display = isMatch ? '' : 'none';
                    if (isMatch) visibleCount++;
                });

                if (showingCount) {
                    const total = Number(showingCount.getAttribute('data-total')) || cards.length;
                    showingCount.textContent = 'Showing ' + visibleCount + ' of ' + total + ' event(s)';
                }
            };

            searchInput.addEventListener('input', filterCards);
            filterCards();
        })();

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
            if (!deleteConfirmName) console.error('Delete confirmation name element not found: #deleteConfirmName');
            if (!deleteConfirmCancel) console.error('Delete confirmation cancel button not found: #deleteConfirmCancel');
            if (!deleteConfirmSubmit) console.error('Delete confirmation submit button not found: #deleteConfirmSubmit');

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
            if (deleteButtons.length === 0) {
                console.warn('No delete triggers found for evaluation forms');
            }
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

            // ── Event Questions Modal (edit questions inline) ─────────────────
            (function () {
                const modal = document.getElementById('eventQuestionsModal');
                const modalTitle = document.getElementById('eventQuestionsModalTitle');
                const tabsContainer = document.getElementById('eventQuestionsSectionTabs');
                const list = document.getElementById('eventQuestionsList');
                const closeBtns = document.querySelectorAll('.event-questions-modal-close');
                const saveBtn = document.getElementById('saveEventQuestionsBtn');
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                if (!modal) {
                    console.error('Edit evaluation form modal not found: #eventQuestionsModal');
                    return;
                }
                if (!modalTitle) console.error('Edit modal title element not found: #eventQuestionsModalTitle');
                if (!tabsContainer) console.error('Edit modal tabs container not found: #eventQuestionsSectionTabs');
                if (!list) console.error('Edit modal questions list not found: #eventQuestionsList');
                if (!closeBtns || closeBtns.length === 0) console.error('Edit modal close buttons not found: .event-questions-modal-close');
                if (!saveBtn) console.error('Edit modal save button not found: #saveEventQuestionsBtn');

                const createQuestionRow = (question, section, order, eventType, isNew = false) => {
                    const row = document.createElement('div');
                    row.className = 'question-edit-row';
                    row.draggable = true;
                    row.dataset.section = section;
                    row.dataset.type = question.type || 'text';
                    row.dataset.eventType = eventType || 'all';

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

                    return row;
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
                    sectionTitle.textContent = section;
                    heading.appendChild(sectionTitle);
                    group.appendChild(heading);

                    const rowsContainer = document.createElement('div');
                    rowsContainer.className = 'event-questions-section-rows';

                    // Special handling for Session Feedback in school_event
                    if (section === 'Session Feedback' && eventType === 'school_event') {
                        const matrixQuestion = questions.find(q => q.is_matrix && q.matrix_items);
                        if (matrixQuestion) {
                            // Create individual rows for each matrix item
                            matrixQuestion.matrix_items.forEach((item, idx) => {
                                const subQuestion = {
                                    ...matrixQuestion,
                                    question: item,
                                    is_matrix: false,
                                    matrix_items: null,
                                    id: `${matrixQuestion.id}_sub_${idx}`,
                                    field_key: null
                                };
                                rowsContainer.appendChild(createQuestionRow(subQuestion, section, idx + 1, eventType));
                            });
                        } else {
                            // Fallback to regular questions if no matrix found
                            questions.forEach((q, idx) => {
                                rowsContainer.appendChild(createQuestionRow(q, section, idx + 1, eventType));
                            });
                        }
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
                        if (section === 'Session Feedback' && eventType === 'school_event') {
                            // For Session Feedback, add a new sub-question row
                            const nextOrder = rowsContainer.querySelectorAll('.question-edit-row').length + 1;
                            const newRow = createQuestionRow({
                                question: '',
                                type: 'text',
                                is_required: false,
                                is_matrix: false,
                                matrix_items: null
                            }, section, nextOrder, eventType, true);
                            rowsContainer.appendChild(newRow);
                            updateSectionOrder(rowsContainer);
                            newRow.querySelector('.question-text-input')?.focus();
                        } else {
                            // Regular question addition for other sections
                            const nextOrder = rowsContainer.querySelectorAll('.question-edit-row').length + 1;
                            const newRow = createQuestionRow({ question: '', type: 'text', is_required: false }, section, nextOrder, eventType, true);
                            rowsContainer.appendChild(newRow);
                            updateSectionOrder(rowsContainer);
                            newRow.querySelector('.question-text-input')?.focus();
                        }
                    });
                    actions.appendChild(addButton);
                    group.appendChild(actions);

                    attachDragHandlers(rowsContainer);
                    return group;
                };

                const editButtons = document.querySelectorAll('.js-open-event-questions-modal, .js-eval-edit-trigger');
                if (editButtons.length === 0) {
                    console.warn('No edit triggers found for evaluation forms');
                }
                editButtons.forEach(function (btn) {
                    btn.addEventListener('click', function (e) {
                        e.preventDefault();
                        const eventId = this.dataset.eventId;
                        const eventTitle = this.dataset.eventTitle || 'Event';
                        let questions = [];
                        try { questions = JSON.parse(this.getAttribute('data-event-questions') || '[]'); } catch (err) { questions = []; }

                        modal.dataset.eventId = eventId;
                        modal.dataset.eventType = questions[0]?.event_type || questions[0]?.eventType || 'all';
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

                            const eventType = questions[0]?.event_type || questions[0]?.eventType || 'all';

                            // Ensure school_event always has all four sections
                            let sections;
                            if (eventType === 'school_event') {
                                sections = ['Participant Information', 'Event Details', 'Session Feedback', 'Open-ended Feedback'];
                                // Ensure each section exists in grouped, even if empty
                                sections.forEach(section => {
                                    if (!grouped[section]) grouped[section] = [];
                                });
                            } else {
                                sections = Object.keys(grouped);
                            }

                            sections.forEach((section, idx) => {
                                const tab = document.createElement('button');
                                tab.type = 'button';
                                tab.className = 'section-tab' + (idx === 0 ? ' active' : '');
                                tab.textContent = section;
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
                                list.appendChild(group);
                            });
                        }

                        showModal(modal);
                    });
                });

                function closeEventModal() {
                    hideModal(modal);
                }

                closeBtns.forEach(b => b.addEventListener('click', closeEventModal));

                modal.addEventListener('click', (e) => { if (e.target === modal) closeEventModal(); });

                if (saveBtn) {
                    saveBtn.addEventListener('click', async () => {
                        try {
                            const eventType = modal.dataset.eventType || 'all';
                            const rows = Array.from(list.querySelectorAll('.question-edit-row'));
                            if (rows.length === 0) { closeEventModal(); return; }

                            // Special handling for Session Feedback in school_event
                            if (eventType === 'school_event') {
                                const sessionFeedbackRows = rows.filter(row => row.dataset.section === 'Session Feedback');
                                if (sessionFeedbackRows.length > 0) {
                                // Collect all sub-question texts for matrix items
                                const matrixItems = sessionFeedbackRows
                                    .sort((a, b) => parseInt(a.querySelector('.sort-order-input').value) - parseInt(b.querySelector('.sort-order-input').value))
                                    .map(row => row.querySelector('.question-text-input').value.trim())
                                    .filter(text => text.length > 0);

                                // Find or create the matrix question
                                const existingMatrixQuestion = Array.from(list.querySelectorAll('.question-edit-row'))
                                    .find(row => row.dataset.section === 'Session Feedback' && !row.dataset.new && row.dataset.type === 'likert');

                                if (existingMatrixQuestion) {
                                    // Update existing matrix question
                                    const matrixId = existingMatrixQuestion.dataset.qid;
                                    const fd = new FormData();
                                    fd.append('_token', csrfToken);
                                    fd.append('_method', 'PATCH');
                                    fd.append('question', 'Activity Content and Delivery');
                                    fd.append('type', 'likert');
                                    fd.append('event_type', 'school_event');
                                    fd.append('sort_order', '1');
                                    fd.append('section', 'Session Feedback');
                                    fd.append('is_required', '1');
                                    fd.append('is_active', '1');
                                    fd.append('matrix_items', JSON.stringify(matrixItems));

                                    await fetch('"/admin/evaluation-questions"' + '/' + matrixId, {
                                        method: 'POST',
                                        body: fd,
                                        credentials: 'same-origin'
                                    }).then(resp => { if (!resp.ok) throw new Error('Save failed'); });
                                } else {
                                    // Create new matrix question
                                    const fd = new FormData();
                                    fd.append('_token', csrfToken);
                                    fd.append('question', 'Activity Content and Delivery');
                                    fd.append('type', 'likert');
                                    fd.append('event_type', 'school_event');
                                    fd.append('sort_order', '1');
                                    fd.append('section', 'Session Feedback');
                                    fd.append('is_required', '1');
                                    fd.append('is_active', '1');
                                    fd.append('event_id', modal.dataset.eventId || '');
                                    fd.append('matrix_items', JSON.stringify(matrixItems));

                                    await fetch('"/admin/evaluation-questions"', {
                                        method: 'POST',
                                        body: fd,
                                        credentials: 'same-origin'
                                    }).then(resp => { if (!resp.ok) throw new Error('Save failed'); });
                                }

                                // Handle Overall Rating question
                                const overallRatingRow = rows.find(row => row.dataset.section === 'Session Feedback' && row.querySelector('.question-text-input').value.includes('Overall Rating'));
                                if (overallRatingRow) {
                                    const id = overallRatingRow.dataset.qid;
                                    const isNew = overallRatingRow.dataset.new === 'true';
                                    const question = overallRatingRow.querySelector('.question-text-input').value.trim();
                                    const sort_order = '2';
                                    const section = 'Session Feedback';
                                    const is_required = overallRatingRow.querySelector('input[name="is_required_' + id + '"]').checked ? 1 : 0;

                                    const fd = new FormData();
                                    fd.append('_token', csrfToken);
                                    if (!isNew) {
                                        fd.append('_method', 'PATCH');
                                    }
                                    fd.append('question', question);
                                    fd.append('type', 'rating');
                                    fd.append('event_type', 'school_event');
                                    fd.append('sort_order', sort_order);
                                    fd.append('section', section);
                                    fd.append('is_required', is_required);
                                    fd.append('is_active', '1');

                                    if (isNew) {
                                        fd.append('event_id', modal.dataset.eventId || '');
                                    }

                                    const url = isNew
                                        ? '"/admin/evaluation-questions"'
                                        : '"/admin/evaluation-questions"' + '/' + id;

                                    await fetch(url, { method: 'POST', body: fd, credentials: 'same-origin' })
                                        .then(resp => { if (!resp.ok) throw new Error('Save failed'); });
                                }

                                // Handle other sections normally
                                const otherRows = rows.filter(row => row.dataset.section !== 'Session Feedback');
                                const promises = otherRows.map(row => {
                                    const id = row.dataset.qid;
                                    const isNew = row.dataset.new === 'true';
                                    const question = row.querySelector('.question-text-input').value.trim();
                                    const sort_order = row.querySelector('.sort-order-input').value;
                                    const section = row.dataset.section || row.querySelector('input[name="section_' + id + '"]').value;
                                    const is_required = row.querySelector('input[name="is_required_' + id + '"]').checked ? 1 : 0;
                                    const is_active = 1;

                                    const fd = new FormData();
                                    fd.append('_token', csrfToken);
                                    if (!isNew) {
                                        fd.append('_method', 'PATCH');
                                    }
                                    fd.append('question', question);
                                    fd.append('type', row.dataset.type || 'text');
                                    fd.append('event_type', eventType);
                                    fd.append('sort_order', sort_order);
                                    fd.append('section', section);
                                    fd.append('is_required', is_required);
                                    fd.append('is_active', is_active);

                                    if (isNew) {
                                        fd.append('event_id', modal.dataset.eventId || '');
                                    }

                                    const url = isNew
                                        ? '"/admin/evaluation-questions"'
                                        : '"/admin/evaluation-questions"' + '/' + id;

                                    return fetch(url, { method: 'POST', body: fd, credentials: 'same-origin' })
                                        .then(resp => { if (!resp.ok) throw new Error('Save failed'); return resp; });
                                });

                                await Promise.all(promises);
                            } else {
                                // No Session Feedback rows, handle normally
                                const promises = rows.map(row => {
                                    const id = row.dataset.qid;
                                    const isNew = row.dataset.new === 'true';
                                    const question = row.querySelector('.question-text-input').value.trim();
                                    const sort_order = row.querySelector('.sort-order-input').value;
                                    const section = row.dataset.section || row.querySelector('input[name="section_' + id + '"]').value;
                                    const is_required = row.querySelector('input[name="is_required_' + id + '"]').checked ? 1 : 0;
                                    const is_active = 1;

                                    const fd = new FormData();
                                    fd.append('_token', csrfToken);
                                    if (!isNew) {
                                        fd.append('_method', 'PATCH');
                                    }
                                    fd.append('question', question);
                                    fd.append('type', row.dataset.type || 'text');
                                    fd.append('event_type', eventType);
                                    fd.append('sort_order', sort_order);
                                    fd.append('section', section);
                                    fd.append('is_required', is_required);
                                    fd.append('is_active', is_active);

                                    if (isNew) {
                                        fd.append('event_id', modal.dataset.eventId || '');
                                    }

                                    const url = isNew
                                        ? '"/admin/evaluation-questions"'
                                        : '"/admin/evaluation-questions"' + '/' + id;

                                    return fetch(url, { method: 'POST', body: fd, credentials: 'same-origin' })
                                        .then(resp => { if (!resp.ok) throw new Error('Save failed'); return resp; });
                                });

                                await Promise.all(promises);
                            }
                        } else {
                            // Handle non-school_event normally
                            const promises = rows.map(row => {
                                const id = row.dataset.qid;
                                const isNew = row.dataset.new === 'true';
                                const question = row.querySelector('.question-text-input').value.trim();
                                const sort_order = row.querySelector('.sort-order-input').value;
                                const section = row.dataset.section || row.querySelector('input[name="section_' + id + '"]').value;
                                const is_required = row.querySelector('input[name="is_required_' + id + '"]').checked ? 1 : 0;
                                const is_active = 1;

                                const fd = new FormData();
                                fd.append('_token', csrfToken);
                                if (!isNew) {
                                    fd.append('_method', 'PATCH');
                                }
                                fd.append('question', question);
                                fd.append('type', row.dataset.type || 'text');
                                fd.append('event_type', eventType);
                                fd.append('sort_order', sort_order);
                                fd.append('section', section);
                                fd.append('is_required', is_required);
                                fd.append('is_active', is_active);

                                if (isNew) {
                                    fd.append('event_id', modal.dataset.eventId || '');
                                }

                                const url = isNew
                                    ? '"/admin/evaluation-questions"'
                                    : '"/admin/evaluation-questions"' + '/' + id;

                                return fetch(url, { method: 'POST', body: fd, credentials: 'same-origin' })
                                    .then(resp => { if (!resp.ok) throw new Error('Save failed'); return resp; });
                            });

                            await Promise.all(promises);
                        }
                            showToast('Questions saved', 'success');
                            closeEventModal();
                        } catch (err) {
                            console.error(err);
                            showToast('Failed to save questions', 'error');
                        }
                    });
                }
            })();
        });

        // ── Form Builder: Modal & Question Management ──────────────────────
        (function () {
            const modal = document.getElementById('questionModal');
            const form = document.getElementById('questionForm');
            const addBtn = document.getElementById('addQuestionBtn');
            const saveBtn = document.getElementById('saveQuestionBtn');
            const formBuilder = document.getElementById('formBuilder');
            const typeSelect = document.getElementById('questionType');
            const matrixGroup = document.getElementById('matrixItemsGroup');
            const modalCloseBtn = document.querySelector('.modal-close');
            const modalCloseBtnFooter = document.querySelector('.modal-close-btn');
            const eventId = formBuilder?.dataset.eventId;

            if (!modal || !eventId) return;

            // Modal open/close
            const openModal = (title, questionId = null) => {
                document.getElementById('modalTitle').textContent = title;
                if (questionId === null) {
                    form.reset();
                    document.getElementById('questionId').value = '';
                } else {
                    // Load question data (this is a simplified version)
                    document.getElementById('questionId').value = questionId;
                }
                modal.classList.add('is-visible');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            };

            const closeModal = () => {
                modal.classList.remove('is-visible');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                form.reset();
            };

            // Type select shows/hides matrix items
            typeSelect.addEventListener('change', (e) => {
                matrixGroup.style.display = (e.target.value === 'likert' || e.target.value === 'radio') ? 'flex' : 'none';
            });

            // Modal controls
            if (addBtn) addBtn.addEventListener('click', () => openModal('Add Question', null));
            if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeModal);
            if (modalCloseBtnFooter) modalCloseBtnFooter.addEventListener('click', closeModal);

            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });

            // Save question
            if (saveBtn) {
                saveBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const formData = new FormData(form);
                    const data = Object.fromEntries(formData);
                    
                    // Parse matrix items
                    if (data.matrix_items_text) {
                        data.matrix_items = data.matrix_items_text.split('\n').map(item => item.trim()).filter(Boolean);
                    }
                    delete data.matrix_items_text;
                    
                    data.is_matrix = data.type === 'likert' || data.type === 'radio' ? '1' : '0';
                    data.is_required = data.is_required ? '1' : '0';
                    data.is_active = data.is_active ? '1' : '0';

                    // In a real implementation, send AJAX request here
                    console.log('Question data:', data);
                    showToast('Question saved (placeholder)', 'success');
                    closeModal();
                });
            }

            // Delete question
            formBuilder.addEventListener('click', (e) => {
                if (e.target.closest('.question-card-delete')) {
                    const card = e.target.closest('.question-card');
                    const questionId = card.dataset.questionId;
                    if (confirm('Delete this question?')) {
                        console.log('Delete question:', questionId);
                        card.remove();
                        showToast('Question deleted (placeholder)', 'success');
                    }
                }
            });

            // Edit question
            formBuilder.addEventListener('click', (e) => {
                const card = e.target.closest('.question-card');
                if (card && !e.target.closest('.question-card-delete')) {
                    const questionId = card.dataset.questionId;
                    openModal('Edit Question', questionId);
                }
            });
        })();

        // ── Form Builder: Drag & Drop ────────────────────────────────────
        (function () {
            const formBuilder = document.getElementById('formBuilder');
            if (!formBuilder) return;

            let draggedElement = null;

            formBuilder.addEventListener('dragstart', (e) => {
                if (e.target.closest('.question-card')) {
                    draggedElement = e.target.closest('.question-card');
                    draggedElement.classList.add('dragging');
                    e.dataTransfer.effectAllowed = 'move';
                }
            });

            formBuilder.addEventListener('dragend', (e) => {
                if (draggedElement) {
                    draggedElement.classList.remove('dragging');
                    draggedElement = null;
                }
            });

            formBuilder.addEventListener('dragover', (e) => {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';

                const target = e.target.closest('.question-card');
                if (target && draggedElement && target !== draggedElement) {
                    const rect = target.getBoundingClientRect();
                    const midpoint = rect.y + rect.height / 2;

                    if (e.clientY < midpoint) {
                        target.parentNode.insertBefore(draggedElement, target);
                    } else {
                        target.parentNode.insertBefore(draggedElement, target.nextSibling);
                    }
                }
            });

            formBuilder.addEventListener('drop', (e) => {
                e.preventDefault();
                // In a real implementation, save the new order
                console.log('Order changed');
            });
        })();


        // ── Create Form Modal handlers ───────────────────────────────────
        (function () {
            const createBtn = document.getElementById('createFormBtn');
            const modal = document.getElementById('createFormModal');
            const closeBtns = document.querySelectorAll('.create-form-modal-close');
            const submitBtn = document.getElementById('createFormSubmitBtn');
            const form = document.getElementById('createFormForm');

            if (!createBtn || !modal) return;

            createBtn.addEventListener('click', () => {
                showModal(modal);
            });

            closeBtns.forEach(b => b.addEventListener('click', () => { hideModal(modal); }));
            modal.addEventListener('click', (e) => { if (e.target === modal) { hideModal(modal); } });

            if (submitBtn) {
                submitBtn.addEventListener('click', () => {
                    const sel = document.getElementById('createEventSelect');
                    const id = sel?.value;
                    if (!id) { showToast('Please select an event', 'warning'); return; }

                    // Set form action to existing load-default route and submit
                    form.action = '/admin/events/' + id + '/evaluation-forms/default-template';
                    form.submit();
                });
            }
        })();
    