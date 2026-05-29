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
        $surveyAvailable = ($participant->event?->isSurveyActive() ?? false) && ($participant->event?->isEvaluationFormEnabled() ?? false);
        $eventHasEnded = ($participant->event?->end_date ? now()->gt($participant->event->end_date) : false);
        $questions = $participant->event?->getActiveEvaluationQuestions() ?? collect();
        $sections = $questions->groupBy('section');
        $surveyAction = route('participants.evaluations.store', $participant);
        $certificateAvailable = $participant->hasSubmittedSurvey();
        $certificateType = $participant->event->certificateRouteType();
        $attendanceType = $participant->event->attendance_type ?? 'face_to_face';
        $qrUrl = route('participants.digital-id.show', $participant) . '?format=qr';
        $validThru = optional($participant->event->end_registration)->format('m/d') ?? 'N/A';
        $eventDate = $participant->event?->dateRangeLabel() ?? 'TBA';
        $eventLocation = $participant->event?->location ?: 'TBA';

        $pages = [];
        if ($attendanceType === 'virtual') {
            $pages[] = [
                'certificateType' => 'Participation',
                'description' => 'This certificate is awarded to '.$participant->name.' in recognition of their valuable participation in the '.$participant->event->title.' held on '.$eventDate.' at '.$eventLocation.'. Their involvement, cooperation, and contribution throughout the activity demonstrated enthusiasm, dedication, and support toward the success of the event.',
            ];
        } elseif ($attendanceType === 'both') {
            $pages[] = [
                'certificateType' => 'Attendance',
                'description' => 'This certificate is awarded to '.$participant->name.' in recognition of their active participation and attendance during the '.$participant->event->title.' held on '.$eventDate.' at '.$eventLocation.'. Their presence and engagement contributed to the success of the event and demonstrated their commitment to learning, professional growth, and continuous development.',
            ];
            $pages[] = [
                'certificateType' => 'Participation',
                'description' => 'This certificate is awarded to '.$participant->name.' in recognition of their valuable participation in the '.$participant->event->title.' held on '.$eventDate.' at '.$eventLocation.'. Their involvement, cooperation, and contribution throughout the activity demonstrated enthusiasm, dedication, and support toward the success of the event.',
            ];
        } else {
            $pages[] = [
                'certificateType' => 'Attendance',
                'description' => 'This certificate is awarded to '.$participant->name.' in recognition of their active participation and attendance during the '.$participant->event->title.' held on '.$eventDate.' at '.$eventLocation.'. Their presence and engagement contributed to the success of the event and demonstrated their commitment to learning, professional growth, and continuous development.',
            ];
        }

        return view('participant.mobile', compact('participant', 'digitalId', 'evaluation', 'surveyAvailable', 'eventHasEnded', 'questions', 'sections', 'surveyAction', 'certificateAvailable', 'certificateType', 'attendanceType', 'qrUrl', 'validThru', 'pages', 'eventDate', 'eventLocation'));
    }
}