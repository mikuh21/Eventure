<?php

namespace App\Http\Controllers;

use App\Models\Participant;

class MobileParticipantController extends Controller
{
    public function show(string $token)
    {
        $participant = Participant::query()
            ->with([
                'event',
                'evaluations' => fn ($query) => $query->latest()->limit(1),
            ])
            ->where('digital_id_token', $token)
            ->firstOrFail();

        $digitalId = (object) [
            'token' => $participant->digital_id_token,
        ];

        $evaluation = $participant->evaluations->first();
        $surveyAvailable = $participant->event?->isSurveyActive() ?? false;
        $questions = $participant->event?->getActiveEvaluationQuestions() ?? collect();
        $sections = $questions->groupBy('section');
        $surveyAction = route('participants.evaluations.store', $participant);
        $certificateAvailable = $participant->hasSubmittedSurvey();
        $certificateType = $participant->event->certificateRouteType();
        $attendanceType = $participant->event->attendance_type ?? 'face_to_face';
        $qrUrl = route('participants.digital-id.show', $participant) . '?format=qr';
        $validThru = optional($participant->event->end_registration)->format('m/d') ?? 'N/A';

        return view('participant.mobile', compact('participant', 'digitalId', 'evaluation', 'surveyAvailable', 'questions', 'sections', 'surveyAction', 'certificateAvailable', 'certificateType', 'attendanceType', 'qrUrl', 'validThru'));
    }
}