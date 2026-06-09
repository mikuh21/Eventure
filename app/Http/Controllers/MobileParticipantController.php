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
        $surveyAvailable = ($participant->event?->isSurveyActive() ?? false) && ($participant->event?->isEvaluationFormEnabled() ?? false) && ($participant->attended ?? false);
        $eventHasEnded = $participant->event?->hasEnded() ?? false;
        // Event 36 (NU Lipa Research Congress 2026) uses custom questions
        if ($participant->event?->id === 36) {
            $questions = collect([
                // Participant Information
                (object)['id'=>'e36_1','question'=>'Name','field_key'=>null,'type'=>'text','placeholder'=>'Enter your full name','help_text'=>null,'is_required'=>true,'section'=>'Participant Information','is_matrix'=>false,'matrix_items'=>null,'event_type'=>'conference'],
                (object)['id'=>'e36_2','question'=>'Email','field_key'=>null,'type'=>'text','placeholder'=>'Enter your email','help_text'=>null,'is_required'=>false,'section'=>'Participant Information','is_matrix'=>false,'matrix_items'=>null,'event_type'=>'conference'],
                (object)['id'=>'e36_3','question'=>'College/Department','field_key'=>null,'type'=>'text','placeholder'=>'Enter your college or department','help_text'=>null,'is_required'=>false,'section'=>'Participant Information','is_matrix'=>false,'matrix_items'=>null,'event_type'=>'conference'],
                (object)['id'=>'e36_4','question'=>'Position/Designation','field_key'=>null,'type'=>'text','placeholder'=>'Enter your position','help_text'=>null,'is_required'=>false,'section'=>'Participant Information','is_matrix'=>false,'matrix_items'=>null,'event_type'=>'conference'],
                // Event Details
                (object)['id'=>'e36_5','question'=>'Title','field_key'=>null,'type'=>'text','placeholder'=>null,'help_text'=>null,'is_required'=>true,'section'=>'Event Details','is_matrix'=>false,'matrix_items'=>null,'event_type'=>'conference'],
                (object)['id'=>'e36_6','question'=>'Date','field_key'=>null,'type'=>'date','placeholder'=>null,'help_text'=>null,'is_required'=>true,'section'=>'Event Details','is_matrix'=>false,'matrix_items'=>null,'event_type'=>'conference'],
                (object)['id'=>'e36_7','question'=>'Time','field_key'=>null,'type'=>'time','placeholder'=>null,'help_text'=>null,'is_required'=>false,'section'=>'Event Details','is_matrix'=>false,'matrix_items'=>null,'event_type'=>'conference'],
                (object)['id'=>'e36_8','question'=>'Venue','field_key'=>null,'type'=>'text','placeholder'=>null,'help_text'=>null,'is_required'=>false,'section'=>'Event Details','is_matrix'=>false,'matrix_items'=>null,'event_type'=>'conference'],
                // Session Feedback A
                (object)['id'=>'e36_9','question'=>'A. Program Organization and Management','field_key'=>null,'type'=>'likert','placeholder'=>null,'help_text'=>'Rate the following: 1 (Poor) to 5 (Excellent)','is_required'=>true,'section'=>'Session Feedback A','is_matrix'=>true,'matrix_items'=>['Registration process was efficient and well-organized.','Program schedule was followed effectively.','Event communication and announcements were clear.','Event staff and volunteers were helpful and accommodating.','Overall management of the congress was satisfactory.'],'event_type'=>'conference'],
                // Session Feedback B
                (object)['id'=>'e36_10','question'=>'B. Quality of Presentations','field_key'=>null,'type'=>'likert','placeholder'=>null,'help_text'=>'Rate the following: 1 (Poor) to 5 (Excellent)','is_required'=>true,'section'=>'Session Feedback B','is_matrix'=>true,'matrix_items'=>['Research presentations were relevant and informative.','Presenters demonstrated mastery of their research topics.','Presentations contributed to knowledge and innovation.','Research topics addressed current issues and societal needs.','The presentation sessions encouraged scholarly discussion.'],'event_type'=>'conference'],
                // Session Feedback C
                (object)['id'=>'e36_11','question'=>'C. Keynote and Panel Discussion Sessions','field_key'=>null,'type'=>'likert','placeholder'=>null,'help_text'=>'Rate the following: 1 (Poor) to 5 (Excellent)','is_required'=>true,'section'=>'Session Feedback C','is_matrix'=>true,'matrix_items'=>['The keynote address was inspiring and insightful.','Panel discussion topics were relevant to research development.','Speakers effectively shared their expertise and experiences.','The sessions enhanced my understanding of research and innovation.','The sessions motivated me to engage in research activities.'],'event_type'=>'conference'],
                // Session Feedback D
                (object)['id'=>'e36_12','question'=>'D. Venue, Facilities, and Logistics','field_key'=>null,'type'=>'likert','placeholder'=>null,'help_text'=>'Rate the following: 1 (Poor) to 5 (Excellent)','is_required'=>true,'section'=>'Session Feedback D','is_matrix'=>true,'matrix_items'=>['Venue was conducive to learning and engagement.','Audio-visual equipment functioned properly.','Session rooms were comfortable and accessible.','Event materials were adequate and useful.'],'event_type'=>'conference'],
                // Session Feedback E
                (object)['id'=>'e36_13','question'=>'E. Achievement of Congress Objectives','field_key'=>null,'type'=>'likert','placeholder'=>null,'help_text'=>'Rate the following: 1 (Poor) to 5 (Excellent)','is_required'=>true,'section'=>'Session Feedback E','is_matrix'=>true,'matrix_items'=>['The congress promoted research culture among students and faculty.','The congress highlighted the relevance of research to the SDGs.','The congress fostered collaboration and networking opportunities.','The congress encouraged innovation and evidence-based solutions.','Overall, the congress achieved its intended objectives.'],'event_type'=>'conference'],
                // Session Feedback F
                (object)['id'=>'e36_14','question'=>'F. Overall Satisfaction','field_key'=>null,'type'=>'likert','placeholder'=>null,'help_text'=>'Rate the following: 1 (Poor) to 5 (Excellent)','is_required'=>true,'section'=>'Session Feedback F','is_matrix'=>true,'matrix_items'=>['I am satisfied with my overall experience in the NU Lipa Research Congress.','I would participate in future NU Lipa Research Congress events.','I would recommend this congress to my colleagues and peers.'],'event_type'=>'conference'],
                // Open-ended
                (object)['id'=>'e36_15','question'=>'What did you like most about the Research Congress?','field_key'=>null,'type'=>'textarea','placeholder'=>'Enter your answer here...','help_text'=>null,'is_required'=>false,'section'=>'Open-ended Feedback','is_matrix'=>false,'matrix_items'=>null,'event_type'=>'conference'],
                (object)['id'=>'e36_16','question'=>'What aspects of the Research Congress need improvement?','field_key'=>null,'type'=>'textarea','placeholder'=>'Enter your answer here...','help_text'=>null,'is_required'=>false,'section'=>'Open-ended Feedback','is_matrix'=>false,'matrix_items'=>null,'event_type'=>'conference'],
                (object)['id'=>'e36_17','question'=>'What topics, speakers, or activities would you like to see in future research congresses?','field_key'=>null,'type'=>'textarea','placeholder'=>'Enter your answer here...','help_text'=>null,'is_required'=>false,'section'=>'Open-ended Feedback','is_matrix'=>false,'matrix_items'=>null,'event_type'=>'conference'],
                (object)['id'=>'e36_18','question'=>'Additional comments and suggestions:','field_key'=>'feedback','type'=>'textarea','placeholder'=>'Enter your comments here...','help_text'=>null,'is_required'=>false,'section'=>'Open-ended Feedback','is_matrix'=>false,'matrix_items'=>null,'event_type'=>'conference'],
            ])->map(function($q) {
                $type = $q->type;
                return new class($q, $type) {
                    public $id, $question, $field_key, $type, $placeholder, $help_text, $is_required, $section, $is_matrix, $matrix_items, $event_type;
                    public function __construct($q, $type) {
                        foreach ((array)$q as $k => $v) $this->$k = $v;
                        $this->type = $type;
                    }
                    public function renderingType(): string { return $this->type; }
                    public function isProgramQuestion(): bool { return false; }
                };
            });
        } else {
            $questions = $participant->event?->getActiveEvaluationQuestions() ?? collect();
        }
        $sections = $questions->groupBy('section');
        $surveyAction = route('participants.evaluations.store', $participant);
        $certificateAvailable = $participant->hasSubmittedSurvey();
        $certificateType = $participant->event->certificateRouteType();
        $attendanceType = $participant->event->attendance_type ?? 'face_to_face';
        $qrUrl = route('participants.digital-id.show', $participant, false) . '?format=qr';
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