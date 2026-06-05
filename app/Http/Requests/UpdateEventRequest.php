<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['sometimes', 'required', Rule::in(['school_event', 'conference'])],
            'attendance_type' => ['sometimes', 'required', Rule::in(['face_to_face', 'virtual', 'both'])],
            'event_title' => ['nullable', 'string', 'max:255'],
            'conference_title' => ['nullable', 'string', 'max:255'],
            'theme' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['sometimes', 'required', 'date'],
            'end_date' => ['sometimes', 'required', 'date', 'after_or_equal:start_date'],
            'auto_activate_evaluation' => ['nullable', 'boolean'],
            'start_registration' => ['sometimes', 'required', 'date'],
            'end_registration' => ['sometimes', 'required', 'date', 'after:start_registration'],
            'location' => ['sometimes', 'required', 'string', 'max:255'],
            'meet_link' => ['nullable', 'url', 'max:500'],
            'template_url' => ['nullable', 'url', 'max:2048'],
            'poster' => ['nullable', 'image', 'max:5120'],
            'template_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,txt,rtf,odt,xls,xlsx,ppt,pptx', 'max:5120'],
            'department' => ['nullable', 'string', 'max:255'],
            'program' => ['nullable', 'string', 'max:255'],
            'keywords' => ['nullable'],
        ];
    }
}
