<?php

namespace App\Http\Requests;

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
            'type' => ['required', Rule::in(['standard', 'conference'])],
            'attendance_type' => ['required', Rule::in(['face_to_face', 'virtual', 'both'])],
            'event_title' => ['required_if:type,standard', 'nullable', 'string', 'max:255'],
            'conference_title' => ['required_if:type,conference', 'nullable', 'string', 'max:255'],
            'theme' => ['required_if:type,conference', 'nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'event_date' => ['nullable', 'date'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'start_registration' => ['required', 'date'],
            'end_registration' => ['required', 'date', 'after:start_registration'],
            'location' => ['required', 'string', 'max:255'],
            'poster' => ['nullable', 'image', 'max:5120'],
            'template_file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'department' => ['nullable', 'string', 'max:255'],
            'program' => ['nullable', 'string', 'max:255'],
            'keywords' => ['nullable'],
        ];
    }
}
