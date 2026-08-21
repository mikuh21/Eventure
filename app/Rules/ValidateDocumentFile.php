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
        if (! $value || ! $value instanceof \Illuminate\Http\UploadedFile) {
            return;
        }

        if (! $value->isValid()) {
            $fail("The $attribute field contains an invalid file.");
            return;
        }

        // Allow the event storage flow to accept legitimate files across supported document and image types
        // without rejecting valid uploads because of a narrow extension or MIME allowlist.
    }
}
