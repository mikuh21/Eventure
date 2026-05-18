<?php

namespace App\Http\Requests;

use App\Models\Participant;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Participant|null $participant */
        $participant = $this->route('participant');
        $eventType = $participant?->event?->type;
        $mimes = $eventType === 'conference' ? 'pdf,doc,docx' : 'pdf';

        return [
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:'.$mimes, 'max:5120'],
        ];
    }
}
