<?php

namespace App\Support;

use Illuminate\Http\Request;

class PreviewAuth
{
    public static function currentQuery(Request $request): array
    {
        if (! $request->filled('preview_user') || ! $request->filled('preview_expires') || ! $request->filled('preview_signature')) {
            return [];
        }

        return [
            'preview_user' => $request->input('preview_user'),
            'preview_expires' => $request->input('preview_expires'),
            'preview_signature' => $request->input('preview_signature'),
        ];
    }

    public static function appendToUrl(string $url, array $query = []): string
    {
        if ($query === []) {
            return $url;
        }

        $separator = str_contains($url, '?') ? '&' : '?';

        return $url.$separator.http_build_query($query);
    }
}