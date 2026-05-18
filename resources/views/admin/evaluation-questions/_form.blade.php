<div class="admin-management-page">
    <div class="admin-management-panel">
        @if (! empty($panelHeading))
            <div class="admin-management-panel-header" style="margin-bottom: 18px;">
                <h1 class="admin-management-title">{{ $panelHeading }}</h1>
            </div>
        @endif

        <div class="admin-management-form-grid">
            <div class="admin-management-field is-full">
                <label class="admin-management-label" for="question">Question</label>
                <input class="admin-management-input" id="question" name="question" type="text" value="{{ old('question', $question->question) }}" required>
            </div>

            <div class="admin-management-field">
                <label class="admin-management-label" for="event_id">Event</label>
                <select class="admin-management-select" id="event_id" name="event_id">
                    <option value="">All events / template</option>
                    @foreach($events as $eventOption)
                        <option value="{{ $eventOption->id }}" {{ old('event_id', $question->event_id) == $eventOption->id ? 'selected' : '' }}>{{ $eventOption->title }} ({{ $eventOption->type === 'conference' ? 'Conference' : 'School Event' }})</option>
                    @endforeach
                </select>
                <div class="admin-management-helper">Assign this question to a specific event form. Leave blank to keep it as a shared template.</div>
            </div>

            <div class="admin-management-field">
                <label class="admin-management-label" for="event_type">Event Type</label>
                <select class="admin-management-select" id="event_type" name="event_type" required>
                    @foreach ($eventTypeOptions as $value => $label)
                        <option value="{{ $value }}" {{ old('event_type', $question->event_type ?? 'all') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="admin-management-helper">Choose which event types this question applies to.</div>
            </div>

            <div class="admin-management-field">
                <label class="admin-management-label" for="type">Response Type</label>
                <select class="admin-management-select" id="type" name="type" required>
                    @foreach ($typeOptions as $value => $label)
                        <option value="{{ $value }}" {{ old('type', $question->type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="admin-management-field">
                <label class="admin-management-label" for="field_key">Map To Summary Field</label>
                <select class="admin-management-select" id="field_key" name="field_key">
                    <option value="">Store only in question answers</option>
                    @foreach ($fieldKeyOptions as $value => $label)
                        <option value="{{ $value }}" {{ old('field_key', $question->field_key) === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="admin-management-helper">Use a mapped field only when the answer should also update the evaluation summary columns.</div>
            </div>

            <div class="admin-management-field">
                <label class="admin-management-label" for="placeholder">Placeholder</label>
                <input class="admin-management-input" id="placeholder" name="placeholder" type="text" value="{{ old('placeholder', $question->placeholder) }}">
            </div>

            <div class="admin-management-field">
                <label class="admin-management-label" for="sort_order">Display Order</label>
                <input class="admin-management-input" id="sort_order" name="sort_order" type="number" min="1" value="{{ old('sort_order', $question->sort_order ?? 1) }}" required>
            </div>

            <div class="admin-management-field is-full">
                <label class="admin-management-label" for="help_text">Help Text</label>
                <textarea class="admin-management-textarea" id="help_text" name="help_text" rows="4">{{ old('help_text', $question->help_text) }}</textarea>
            </div>

            <div class="admin-management-field is-full">
                <div class="admin-management-toggle-row">
                    <label class="admin-management-checkbox">
                        <input type="checkbox" name="is_required" value="1" {{ old('is_required', $question->is_required) ? 'checked' : '' }}>
                        Required
                    </label>
                    <label class="admin-management-checkbox">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $question->is_active ?? true) ? 'checked' : '' }}>
                        Active
                    </label>
                    <label class="admin-management-checkbox">
                        <input type="checkbox" name="is_guest_question" value="1" {{ old('is_guest_question', $question->is_guest_question) ? 'checked' : '' }}>
                        Guest Question (Optional)
                    </label>
                </div>
            </div>
        </div>

        <div class="admin-management-actions" style="margin-top: 18px;">
            <button class="btn admin-management-btn-primary admin-management-form-btn" type="submit">{{ $submitLabel }}</button>
            <a class="btn admin-management-form-btn admin-management-cancel-btn btn-cancel" href="{{ $backUrl ?? route('admin.evaluation-questions.index') }}">Cancel</a>
        </div>
    </div>
</div>