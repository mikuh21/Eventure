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

    Route::get('events/{event}/submissions', [EventController::class, 'submissions'])
        ->name('events.submissions.index');

    // Participants index without event (shows empty state or allows event selection)
    Route::get('participants', [ParticipantController::class, 'index'])
        ->name('participants.index');

    Route::resource('events.participants', ParticipantController::class)
        ->except(['create', 'store']);

    Route::post('events/{event}/participants/{participant}/resend-digital-id', [ParticipantController::class, 'resendDigitalIdEmail'])
        ->name('events.participants.resend-digital-id');

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
