<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreviewSignedLoginMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            app()->environment(['local', 'testing'])
            && ! Auth::check()
            && $request->filled('preview_user')
            && $request->filled('preview_expires')
            && $request->filled('preview_signature')
        ) {
            $previewUser = (int) $request->input('preview_user');
            $previewExpires = (int) $request->input('preview_expires');
            $previewSignature = (string) $request->input('preview_signature');
            $expectedSignature = hash_hmac('sha256', $previewUser.'|'.$previewExpires, (string) config('app.key'));

            if ($previewExpires >= now()->timestamp && hash_equals($expectedSignature, $previewSignature)) {
                $user = User::query()->find($previewUser);

                if ($user) {
                    Auth::login($user);

                    if ($request->hasSession()) {
                        $request->session()->regenerate();
                    }
                }
            }
        }

        return $next($request);
    }
}