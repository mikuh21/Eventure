<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EventStaffCredentialsMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->where('role', User::ROLE_EVENT_STAFF)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users.index', [
            'users' => $users,
            'staffStats' => [
                'total' => $users->count(),
                'active' => $users->where('approval_status', '!=', User::APPROVAL_DISAPPROVED)->count(),
                'deactivated' => $users->where('approval_status', User::APPROVAL_DISAPPROVED)->count(),
            ],
        ]);
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        abort_unless($user->isEventStaff(), 422, 'Only Event Staff accounts can be managed here.');

        $user->update([
            'approval_status' => $user->approval_status === User::APPROVAL_DISAPPROVED
                ? User::APPROVAL_APPROVED
                : User::APPROVAL_DISAPPROVED,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Event Staff status updated successfully.');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
            ]);

            // Generate password
            $nameParts = explode(' ', trim($request->name));
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? $firstName; // Use first name if no last name

            $firstInitial = strtoupper(substr($firstName, 0, 1));
            $lastInitial = strtoupper(substr($lastName, 0, 1));
            $randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $specialChars = '!@#$%^&*';
            $randomSpecial = $specialChars[rand(0, strlen($specialChars) - 1)];
            $password = $firstInitial . $lastInitial . $randomNumber . $randomSpecial;

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => User::ROLE_EVENT_STAFF,
                'approval_status' => User::APPROVAL_APPROVED,
                'password' => Hash::make($password),
            ]);

            // Send credentials email
            Mail::to($user->email)->send(new EventStaffCredentialsMail($user, $password));

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Event Staff registered successfully. Credentials sent to email.']);
            }

            return redirect()
                ->route('admin.users.index')
                ->with('status', 'Event Staff registered successfully. Credentials sent to email.');
        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }
            throw $e;
        }
    }
}