@extends('layouts.app')

@section('title', 'Evaluation Form - ' . $event->title)

@push('styles')
    <style>
        * {
            box-sizing: border-box;
        }

        .evaluation-form-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
        }

        .evaluation-form-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .evaluation-form-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 8px 0;
        }

        .evaluation-form-subtitle {
            font-size: 16px;
            color: #6b7280;
            margin: 0;
        }

        .evaluation-form-progress {
            margin: 20px 0;
            background: #e5e7eb;
            height: 4px;
            border-radius: 2px;
            overflow: hidden;
        }

        .evaluation-form-progress-bar {
            height: 100%;
            background: #3b82f6;
            transition: width 0.3s ease;
        }

        .evaluation-form-section {
            margin-bottom: 36px;
            padding: 24px;
            background: #fafafa;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
        }

        .evaluation-form-section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 16px 0;
        }

        .evaluation-form-section-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin: 0 0 20px 0;
        }

        .evaluation-form-section-title.minimal-design {
            font-size: 12px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 12px 0;
        }

        .evaluation-form-section-title.minimal-design + .evaluation-form-section-subtitle {
            display: none;
        }

        .evaluation-form-section.hidden-header .evaluation-form-section-title,
        .evaluation-form-section.hidden-header .evaluation-form-section-subtitle {
            display: none;
        }

        .evaluation-form-section.hidden-header {
            padding: 0;
            background: transparent;
            border-left: none;
            margin-bottom: 24px;
        }

        .evaluation-form-field {
            margin-bottom: 24px;
        }

        .evaluation-form-label {
            display: block;
            font-size: 15px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .evaluation-form-label-required::after {
            content: '*';
            color: #ef4444;
            margin-left: 4px;
        }

        .evaluation-form-help-text {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
            font-style: italic;
        }

        .evaluation-form-likert {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(60px, 1fr));
            gap: 12px;
            margin-top: 12px;
        }

        .evaluation-form-likert-item {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .evaluation-form-likert-label {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border: 2px solid #d1d5db;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            color: #6b7280;
            transition: all 0.2s ease;
            background: white;
        }

        .evaluation-form-likert-input {
            display: none;
        }

        .evaluation-form-likert-input:checked + .evaluation-form-likert-label {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .evaluation-form-likert-label:hover {
            border-color: #3b82f6;
            background: #f0f9ff;
        }

        .evaluation-form-likert-input:checked + .evaluation-form-likert-label:hover {
            background: #3b82f6;
        }

        .evaluation-form-rating {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-top: 12px;
        }

        .evaluation-form-star {
            width: 40px;
            height: 40px;
            cursor: pointer;
            font-size: 32px;
            color: #d1d5db;
            transition: all 0.2s ease;
        }

        .evaluation-form-star:hover,
        .evaluation-form-star.active {
            color: #fbbf24;
        }

        .evaluation-form-textarea,
        .evaluation-form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 15px;
            font-family: inherit;
            transition: all 0.2s ease;
        }

        .evaluation-form-textarea:focus,
        .evaluation-form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .evaluation-form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .evaluation-form-actions {
            display: flex;
            gap: 12px;
            margin-top: 40px;
            justify-content: center;
            flex-direction: row-reverse;
        }

        .evaluation-form-btn {
            padding: 12px 32px;
            font-size: 15px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .evaluation-form-btn-primary {
            background: #3b82f6;
            color: white;
        }

        .evaluation-form-btn-primary:hover {
            background: #2563eb;
        }

        .evaluation-form-btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .evaluation-form-btn-secondary:hover {
            background: #d1d5db;
        }

        .evaluation-form-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .evaluation-form-error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 6px;
        }

        /* Submission Confirmation Modal */
        .submission-modal {
            position: fixed;
            inset: 0;
            background: rgba(10, 35, 66, 0.55);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 20px;
        }

        .submission-modal.active {
            display: flex;
        }

        .submission-modal-content {
            background: white;
            border-radius: 12px;
            padding: 40px 32px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease;
            /* Ensure modal content never exceeds viewport and becomes scrollable when needed */
            max-height: calc(100vh - 80px);
            overflow: auto;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .submission-check-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: checkBounce 0.6s ease;
        }

        .submission-check-icon::after {
            content: '✓';
            font-size: 48px;
            color: white;
            font-weight: bold;
        }

        @keyframes checkBounce {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        .submission-modal-title {
            font-size: 22px;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 12px 0;
        }

        .submission-modal-message {
            font-size: 15px;
            color: #6b7280;
            margin: 0 0 24px 0;
            line-height: 1.5;
        }

        .submission-modal-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .submission-modal-btn {
            padding: 10px 24px;
            font-size: 15px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .submission-modal-btn-primary {
            background: #3b82f6;
            color: white;
        }

        .submission-modal-btn-primary:hover {
            background: #2563eb;
        }

        @media (max-width: 768px) {
            .evaluation-form-container {
                padding: 16px;
            }

            .evaluation-form-title {
                font-size: 22px;
            }

            .evaluation-form-section {
                padding: 16px;
            }

            .evaluation-form-likert {
                grid-template-columns: repeat(2, 1fr);
            }

            .evaluation-form-likert-label {
                width: 100%;
                height: 45px;
            }

            .evaluation-form-actions {
                flex-direction: column;
            }

            .evaluation-form-btn {
                width: 100%;
            }

            .submission-modal-content {
                padding: 32px 24px;
                max-height: calc(100vh - 48px);
            }
        }
    </style>
@endpush

@section('content')
    <div class="evaluation-form-container">
        <div class="evaluation-form-header">
            <h1 class="evaluation-form-title">{{ $event->title }}</h1>
            <p class="evaluation-form-subtitle">Event Evaluation Form</p>
            <div class="evaluation-form-progress">
                <div class="evaluation-form-progress-bar" style="width: 0%;" id="progressBar"></div>
            </div>
        </div>

        <form action="{{ route('participants.evaluations.store', $participant) }}" method="POST" id="evaluationForm">
            @csrf

            @if ($questions->isNotEmpty())
                @php
                    $grouped = $questions->groupBy('section');
                @endphp

                @foreach ($grouped as $section => $sectionQuestions)
                    @php
                        $isHiddenSection = in_array($section, ['Participant Information', 'Event Details']);
                        $displayLabel = $section === 'Session Feedback' ? '1-5 Likert Rating' : ($section === 'Open-ended Feedback' ? 'Long Text Input' : $section);
                        $isMinimal = in_array($section, ['Session Feedback', 'Open-ended Feedback']);
                    @endphp
                    <div class="evaluation-form-section {{ $isHiddenSection ? 'hidden-header' : '' }}">
                        @if (!$isHiddenSection)
                            <h2 class="evaluation-form-section-title {{ $isMinimal ? 'minimal-design' : '' }}">{{ $displayLabel }}</h2>
                            @if (!$isMinimal)
                                <p class="evaluation-form-section-subtitle">Please help us improve by answering a few questions</p>
                            @endif
                        @endif

                        @foreach ($sectionQuestions as $question)
                            @php($fieldName = 'answers.'.$question->id)
                            <div class="evaluation-form-field">
                            @if (! $question->isProgramQuestion())
                                <label class="evaluation-form-label {{ $question->is_required ? 'evaluation-form-label-required' : '' }}">
                                    {{ $question->question }}
                                </label>
                            @endif

                            @if ($question->help_text)
                                <div class="evaluation-form-help-text">{{ $question->help_text }}</div>
                            @endif

                            @if ($question->renderingType() === 'likert')
                                <div class="evaluation-form-likert">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div class="evaluation-form-likert-item">
                                            <input
                                                type="radio"
                                                id="question_{{ $question->id }}_{{ $i }}"
                                                name="{{ $fieldName }}"
                                                value="{{ $i }}"
                                                {{ $question->is_required ? 'required' : '' }}
                                                class="evaluation-form-likert-input"
                                            >
                                            <label for="question_{{ $question->id }}_{{ $i }}" class="evaluation-form-likert-label">
                                                {{ $i }}
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                            @elseif ($question->renderingType() === 'rating')
                                <div class="evaluation-form-rating" id="rating_{{ $question->id }}">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="evaluation-form-star" data-value="{{ $i }}" onclick="setRating({{ $question->id }}, {{ $i }})">★</span>
                                    @endfor
                                    <input type="hidden" id="rating_input_{{ $question->id }}" name="{{ $fieldName }}" value="0" {{ $question->is_required ? 'required' : '' }}>
                                </div>
                            @elseif ($question->renderingType() === 'textarea')
                                <textarea
                                    name="{{ $fieldName }}"
                                    class="evaluation-form-textarea"
                                    placeholder="{{ $question->placeholder }}"
                                    {{ $question->is_required ? 'required' : '' }}
                                    @if ($question->isProgramQuestion()) aria-label="{{ $question->question }}" @endif
                                >{{ old($fieldName) }}</textarea>
                            @else
                                <input
                                    type="text"
                                    name="{{ $fieldName }}"
                                    class="evaluation-form-input"
                                    placeholder="{{ $question->placeholder }}"
                                    {{ $question->is_required ? 'required' : '' }}
                                    value="{{ old($fieldName) }}"
                                    @if ($question->isProgramQuestion()) aria-label="{{ $question->question }}" @endif
                                >
                            @endif

                            @error($fieldName)
                                <div class="evaluation-form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                </div>
                @endforeach
            @else
                <div class="evaluation-form-section">
                    <p style="text-align: center; color: #6b7280;">No questions available for this evaluation.</p>
                </div>
            @endif

            <div class="evaluation-form-actions">
                <button type="button" class="evaluation-form-btn evaluation-form-btn-primary" id="submitBtn" {{ $questions->isEmpty() ? 'disabled' : '' }} onclick="showConfirmation(event)">
                    Submit Evaluation
                </button>
                <a href="{{ route('participants.digital-id.show', $participant) }}" class="evaluation-form-btn evaluation-form-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Submission Confirmation Modal -->
    <div class="submission-modal" id="submissionModal">
        <div class="submission-modal-content">
            <div class="submission-check-icon"></div>
            <h2 class="submission-modal-title">Thank You!</h2>
            <p class="submission-modal-message">Your feedback has been submitted successfully. We appreciate your time and input.</p>
            <div class="submission-modal-actions">
                <button type="button" class="submission-modal-btn submission-modal-btn-primary" onclick="closeModalAndRedirect()">Continue</button>
            </div>
        </div>
    </div>

    <script>
        function showConfirmation(e) {
            e.preventDefault();
            
            // Show modal
            const modal = document.getElementById('submissionModal');
            modal.classList.add('active');
            
            // Submit form after modal is shown
            setTimeout(function() {
                document.getElementById('evaluationForm').submit();
            }, 1500);
        }

        function closeModalAndRedirect() {
            window.location.href = '{{ route("participants.digital-id.show", $participant) }}';
        }

        function setRating(questionId, value) {
            document.getElementById('rating_input_' + questionId).value = value;
            const stars = document.querySelectorAll('#rating_' + questionId + ' .evaluation-form-star');
            stars.forEach((star, index) => {
                if (index < value) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
            updateProgress();
        }

        // Update progress bar
        const form = document.getElementById('evaluationForm');
        const progressBar = document.getElementById('progressBar');

        function updateProgress() {
            const inputs = form.querySelectorAll('[required], input[type="radio"], input[type="text"], textarea');
            const filled = Array.from(inputs).filter(input => {
                if (input.type === 'radio') {
                    const name = input.name;
                    return document.querySelector(`input[name="${name}"]:checked`);
                } else if (input.type === 'text' || input.tagName === 'TEXTAREA') {
                    return input.value.trim() !== '';
                } else if (input.type === 'hidden') {
                    return input.value !== '0';
                }
                return false;
            }).length;

            if (inputs.length > 0) {
                const percentage = (filled / inputs.length) * 100;
                progressBar.style.width = percentage + '%';
            }
        }

        form.addEventListener('change', updateProgress);
        form.addEventListener('input', updateProgress);
    </script>
@endsection
