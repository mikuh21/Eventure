@extends('layouts.app')

@section('title', 'Edit Evaluation')

@section('content')
    <div class="card">
        <div class="header-row">
            <h1>Edit Evaluation</h1>
            <a class="btn" href="{{ route('participants.evaluations.show', [$participant, $evaluation]) }}">Back</a>
        </div>

        <form action="{{ route('participants.evaluations.update', [$participant, $evaluation]) }}" method="POST">
            @csrf
            @method('PUT')

            @forelse ($questions as $question)
                @php($fieldName = 'answers.'.$question->id)
                <div class="field">
                    <label for="question_{{ $question->id }}">{{ $question->question }}{{ $question->is_required ? ' *' : '' }}</label>
                    @if ($question->help_text)
                        <p style="margin: 0 0 8px; color: #6b7280; font-size: 13px;">{{ $question->help_text }}</p>
                    @endif
                    @php($value = old($fieldName, data_get($evaluation->answers, (string) $question->id)))
                    @if ($question->type === \App\Models\EvaluationQuestion::TYPE_RATING)
                        <input id="question_{{ $question->id }}" name="{{ $fieldName }}" type="number" min="1" max="5" value="{{ $value ?? $evaluation->rating }}" {{ $question->is_required ? 'required' : '' }}>
                    @elseif ($question->type === \App\Models\EvaluationQuestion::TYPE_TEXTAREA)
                        <textarea id="question_{{ $question->id }}" name="{{ $fieldName }}" rows="4" placeholder="{{ $question->placeholder }}">{{ $value ?? ($question->field_key === 'feedback' ? $evaluation->feedback : '') }}</textarea>
                    @else
                        <input id="question_{{ $question->id }}" name="{{ $fieldName }}" type="text" value="{{ $value ?? ($question->field_key === 'feedback' ? $evaluation->feedback : '') }}" placeholder="{{ $question->placeholder }}" {{ $question->is_required ? 'required' : '' }}>
                    @endif
                </div>
            @empty
                <div class="field">
                    <label for="rating">Rating (1-5)</label>
                    <input id="rating" name="rating" type="number" min="1" max="5" value="{{ old('rating', $evaluation->rating) }}" required>
                </div>

                <div class="field">
                    <label for="feedback">Feedback</label>
                    <textarea id="feedback" name="feedback" rows="4">{{ old('feedback', $evaluation->feedback) }}</textarea>
                </div>
            @endforelse

            <button class="btn btn-primary" type="submit">Update Evaluation</button>
        </form>
    </div>
@endsection
