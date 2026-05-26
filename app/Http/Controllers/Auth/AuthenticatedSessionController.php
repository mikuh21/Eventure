<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EventStaffPasswordResetMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function forgotPasswordForm(): View
    {
        return view('auth.login')->with('forgotPasswordModal', true);
    }

    public function sendForgotPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::query()
            ->where('email', $request->input('email'))
            ->where('role', User::ROLE_EVENT_STAFF)
            ->first();

        if (! $user) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Please enter the email associated with your Event Staff account.'])
                ->with('forgotPasswordModal', true);
        }

        $password = Str::random(12);
        $user->password = $password;
        $user->save();

        Mail::to($user->email)->send(new EventStaffPasswordResetMail($user, $password));

        return back()
            ->with('status', 'A new password has been sent to your Event Staff email.')
            ->with('forgotPasswordModal', true);
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = (bool) $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ]);
        }

        $request->session()->regenerate();

        $currentUser = Auth::user();

        // Only allow login for Admin and Event Staff
        if ($currentUser instanceof User && $currentUser->canAccessBackoffice()) {
            return redirect()->intended(route('admin.dashboard', [], false));
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $message = $currentUser instanceof User && $currentUser->isEventStaff() && ! $currentUser->isApproved()
            ? 'Your Event Staff account is waiting for Admin approval.'
            : 'Login is only allowed for Admin and approved Event Staff.';

        return back()->withErrors([
            'email' => $message,
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to(route('landing', [], false));
    }

    public function testLoginAs(Request $request): RedirectResponse
    {
        abort_unless(app()->environment(['local', 'testing']), 403, 'Test login is only available in local/testing environments.');

        $role = (string) ($request->route('role') ?? $request->input('role', ''));

        validator(
            ['role' => $role],
            ['role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_EVENT_STAFF, User::ROLE_USER])]],
        )->validate();

        $user = User::query()->where('role', $role)->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'No '.$role.' account found. Seed users first.',
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->isAdmin() || $user->isEventStaff()) {
            $previewExpires = now()->addMinutes(30)->timestamp;
            $previewSignature = hash_hmac('sha256', $user->id.'|'.$previewExpires, (string) config('app.key'));

            return redirect()->to(route('admin.dashboard', [
                'preview_user' => $user->id,
                'preview_expires' => $previewExpires,
                'preview_signature' => $previewSignature,
            ], false));
        }

        return redirect()->to(route('participants.public.events', [], false));
    }
}
