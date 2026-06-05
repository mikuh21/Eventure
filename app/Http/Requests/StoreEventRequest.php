<?php

namespace App\Http\Requests;

use App\Rules\ValidateDocumentFile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['school_event', 'conference'])],
            'attendance_type' => ['required', Rule::in(['face_to_face', 'virtual', 'both'])],
            'event_title' => ['required_if:type,school_event', 'nullable', 'string', 'max:255'],
            'conference_title' => ['required_if:type,conference', 'nullable', 'string', 'max:255'],
            'theme' => ['required_if:type,conference', 'nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'auto_activate_evaluation' => ['nullable', 'boolean'],
            'start_registration' => ['required', 'date'],
            'end_registration' => ['required', 'date', 'after:start_registration'],
            'location' => ['required', 'string', 'max:255'],
            'meet_link' => ['nullable', 'url', 'max:500'],
            'template_url' => ['nullable', 'url', 'max:2048'],
            'poster' => ['nullable', 'image', 'max:5120'],
            'template_file' => ['nullable', 'file', new ValidateDocumentFile(), 'max:5120'],
            'department' => ['nullable', 'string', 'max:255'],
            'program' => ['nullable', 'string', 'max:255'],
            'keywords' => ['nullable'],
        ];
    }
}
