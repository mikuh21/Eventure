<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateDocumentFile implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value) {
            return;
        }

        $allowedExtensions = ['pdf', 'doc', 'docx', 'txt', 'rtf', 'odt', 'xls', 'xlsx', 'ppt', 'pptx', 'png', 'jpg', 'jpeg'];
        $allowedMimeTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain',
            'application/rtf',
            'application/vnd.oasis.opendocument.text',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/zip', // Office files may be detected as zip
            'image/png',
            'image/jpeg',
        ];

        $extension = strtolower($value->getClientOriginalExtension());
        $mimeType = $value->getMimeType();

        // Check if extension is allowed
        if (!in_array($extension, $allowedExtensions)) {
            $fail("The $attribute field must have one of the following extensions: " . implode(', ', $allowedExtensions) . '.');
            return;
        }

        // Check if MIME type is allowed (with flexibility for office files detected as zip)
        if (!in_array($mimeType, $allowedMimeTypes)) {
            // For Office files detected as application/zip, verify the extension
            if ($mimeType === 'application/zip' && in_array($extension, ['docx', 'xlsx', 'pptx', 'odt'])) {
                return;
            }
            
            $fail("The $attribute field has an invalid file type.");
            return;
        }
    }
}
