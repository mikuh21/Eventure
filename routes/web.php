<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\EvaluationQuestionController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\GuestEvaluationController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MobileParticipantController;
use App\Http\Controllers\ParticipantDigitalIdController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('landing');
Route::get('events/{event}/template/download', [EventController::class, 'downloadTemplate'])->name('events.download-template-public');
Route::post('events/{event}/template/download/verify', [EventController::class, 'verifyAndDownloadTemplate'])->name('events.template.verify-download');

Route::get('participant/events', [EventController::class, 'index'])
    ->name('participants.public.events');

Route::post('register/participant', [ParticipantController::class, 'publicStore'])
    ->name('public.participant.store');

Route::post('register/guest', [GuestController::class, 'publicStore'])
    ->name('public.guest.store');

Route::middleware('guest')->group(function (): void {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('forgot-password', [AuthenticatedSessionController::class, 'forgotPasswordForm'])->name('password.request');
    Route::post('forgot-password', [AuthenticatedSessionController::class, 'sendForgotPassword'])->name('password.email');

    if (app()->environment(['local', 'testing'])) {
        Route::get('login/test-as/{role}', [AuthenticatedSessionController::class, 'testLoginAs'])
            ->name('login.test-as.quick');
        Route::post('login/test-as', [AuthenticatedSessionController::class, 'testLoginAs'])->name('login.test-as');
    }
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('events/{event}/participants/create', [ParticipantController::class, 'create'])
    ->name('events.participants.create');

Route::post('events/{event}/participants', [ParticipantController::class, 'store'])
    ->name('events.participants.store');

Route::get('participants/{participant}/confirmation', [ParticipantController::class, 'confirmation'])
    ->name('participants.confirmation.show');

Route::get('participants/{participant}/digital-id', [ParticipantDigitalIdController::class, 'show'])
    ->name('participants.digital-id.show');

Route::get('participants/{participant}/digital-id/download', [ParticipantDigitalIdController::class, 'download'])
    ->name('participants.digital-id.download');

Route::get('certificate/{token}/{type}', [ParticipantDigitalIdController::class, 'certificate'])
    ->name('participants.certificate.show');

Route::get('participants/{participant}/evaluations/create', [EvaluationController::class, 'create'])
    ->name('participants.evaluations.create');

Route::post('participants/{participant}/evaluations', [EvaluationController::class, 'store'])
    ->name('participants.evaluations.store');

Route::get('/p/{token}', [MobileParticipantController::class, 'show'])
    ->name('participant.mobile');

Route::middleware(['auth', 'admin'])->group(function (): void {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('admin/analytics/events', [AdminController::class, 'eventAnalytics'])
        ->name('admin.analytics.events');

    Route::resource('events', EventController::class);

    Route::get('events/{event}/template/download', [EventController::class, 'downloadTemplate'])
        ->name('events.download-template');

    Route::get('events/{event}/submissions', [EventController::class, 'submissions'])
        ->name('events.submissions.index');

    // Participants index without event (shows empty state or allows event selection)
    Route::get('participants', [ParticipantController::class, 'index'])
        ->name('participants.index');

    Route::resource('events.participants', ParticipantController::class)
        ->except(['create', 'store']);

    Route::post('events/{event}/participants/{participant}/resend-digital-id', [ParticipantController::class, 'resendDigitalIdEmail'])
        ->name('events.participants.resend-digital-id');

    // Approve / Deny endpoints (auth required) for event staff or admins
    Route::post('events/{event}/participants/{participant}/approve', [ParticipantController::class, 'approve'])
        ->middleware('auth')
        ->name('events.participants.approve');

    Route::post('events/{event}/participants/{participant}/deny', [ParticipantController::class, 'deny'])
        ->middleware('auth')
        ->name('events.participants.deny');

    Route::post('events/{event}/guests/{guest}/approve', [GuestController::class, 'approve'])
        ->middleware('auth')
        ->name('events.guests.approve');

    Route::post('events/{event}/guests/{guest}/deny', [GuestController::class, 'deny'])
        ->middleware('auth')
        ->name('events.guests.deny');

    Route::get('/guests/{guest}/download-paper', [GuestController::class, 'downloadPaper'])
        ->name('guests.download-paper')
        ->middleware(['auth']);

    Route::resource('guests', GuestController::class);

    Route::resource('guests.evaluations', GuestEvaluationController::class);

    Route::resource('participants.submissions', SubmissionController::class);

    Route::resource('participants.evaluations', EvaluationController::class)
        ->except(['create', 'store']);

    Route::get('admin/digital-id/verify', [ParticipantDigitalIdController::class, 'verifyForm'])
        ->name('admin.digital-id.verify.form');

    Route::match(['get', 'post'], 'admin/digital-id/verify/check', [ParticipantDigitalIdController::class, 'verify'])
        ->name('admin.digital-id.verify.check');

    Route::post('admin/digital-id/scan', [ParticipantDigitalIdController::class, 'scan'])
        ->name('admin.digital-id.scan');

    Route::middleware('super-admin')->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('users', [UserManagementController::class, 'index'])->name('users.index');
        Route::post('users', [UserManagementController::class, 'store'])->name('users.store');
        Route::patch('users/{user}/status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        Route::resource('evaluation-questions', EvaluationQuestionController::class)->except(['show']);
        Route::patch('evaluation-questions/{evaluationQuestion}/toggle-status', [EvaluationQuestionController::class, 'toggleStatus'])->name('evaluation-questions.toggle-status');
        Route::post('evaluation-questions/default-template', [EvaluationQuestionController::class, 'loadDefaultQuestions'])->name('evaluation-questions.load-defaults');
        
        // Evaluation form management
        Route::get('evaluation-forms', [\App\Http\Controllers\Admin\EventEvaluationFormController::class, 'index'])->name('event-evaluation-forms.index');
        Route::post('events/{event}/evaluation-forms/enable', [\App\Http\Controllers\Admin\EventEvaluationFormController::class, 'enable'])->name('event-evaluation-forms.enable');
        Route::post('events/{event}/evaluation-forms/default-template', [\App\Http\Controllers\Admin\EventEvaluationFormController::class, 'loadDefaultForm'])->name('event-evaluation-forms.load-default');
        Route::post('events/{event}/evaluation-forms/disable', [\App\Http\Controllers\Admin\EventEvaluationFormController::class, 'disable'])->name('event-evaluation-forms.disable');
        Route::delete('events/{event}/evaluation-forms/clear', [\App\Http\Controllers\Admin\EventEvaluationFormController::class, 'clear'])->name('event-evaluation-forms.clear');
    });
});
