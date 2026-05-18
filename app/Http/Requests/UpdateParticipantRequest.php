<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $eventId = $this->route('event')?->id;
        $participantId = $this->route('participant')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'participant_type' => ['required', Rule::in(['faculty', 'student'])],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('participants', 'email')
                    ->where(fn ($query) => $query->where('event_id', $eventId))
                    ->ignore($participantId),
            ],
            'institution' => ['required', 'string', 'max:255'],
        ];
    }
}
