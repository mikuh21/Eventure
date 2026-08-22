<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $eventId = $this->route('event')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'participant_type' => ['required', Rule::in(['faculty', 'student', 'coach', 'organizer'])],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('participants', 'email')->where(fn ($query) => $query->where('event_id', $eventId)),
            ],
            'institution' => ['required', 'string', 'max:255'],
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}
