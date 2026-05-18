<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ParticipantDigitalIdController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [AdminController::class, 'dashboard'])
    ->name('api.dashboard');

Route::get('admin/analytics/events', [AdminController::class, 'eventAnalytics'])
    ->name('api.admin.analytics.events');

Route::apiResource('events', EventController::class)
    ->names('api.events');

Route::get('events/{event}/template/download', [EventController::class, 'downloadTemplate'])
    ->name('api.events.template.download');

Route::get('events/{event}/submissions', [EventController::class, 'submissions'])
    ->name('api.events.submissions.index');

Route::apiResource('events.participants', ParticipantController::class)
    ->names('api.events.participants');

Route::get('participants/{participant}/confirmation', [ParticipantController::class, 'confirmation'])
    ->name('api.participants.confirmation.show');

Route::apiResource('participants.submissions', SubmissionController::class)
    ->names('api.participants.submissions');

Route::apiResource('participants.evaluations', EvaluationController::class)
    ->names('api.participants.evaluations');

Route::get('events/{event}/average-rating', [EvaluationController::class, 'eventAverage'])
    ->name('api.events.average-rating');

Route::get('participants/{participant}/digital-id', [ParticipantDigitalIdController::class, 'show'])
    ->name('api.participants.digital-id.show');

Route::get('participants/{participant}/digital-id/download', [ParticipantDigitalIdController::class, 'download'])
    ->name('api.participants.digital-id.download');

Route::match(['get', 'post'], 'admin/digital-id/verify', [ParticipantDigitalIdController::class, 'verify'])
    ->name('api.admin.digital-id.verify');

Route::post('admin/digital-id/scan', [ParticipantDigitalIdController::class, 'scan'])
    ->name('api.admin.digital-id.scan');
