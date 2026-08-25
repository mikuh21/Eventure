<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Evaluation;
use App\Models\Participant;
use App\Models\User;
use App\Services\SurveyActivationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request, SurveyActivationService $surveyActivationService)
    {
        try {
            $surveyActivationService->activateDueEvents();
        } catch (\Exception $e) {
            \Log::error('Survey activation failed: ' . $e->getMessage());
            // Dashboard still loads even if email sending fails
        }

        $wantsJson = $request->expectsJson() || $request->is('api/*');
        $today = now()->startOfDay();
        $startDate = now()->subDays(29)->startOfDay();

        // Build base query for events
        // ADMIN: see all events (created by any user)
        // EVENT STAFF: see only events they created
        $eventsQuery = Event::query();
        if (auth()->user()->role !== User::ROLE_ADMIN) {
            // Filter to own events for non-admins (event staff)
            $eventsQuery->where('created_by', auth()->id());
        }

        $stats = [
            'total_events' => (clone $eventsQuery)->count(),
            'total_participants' => Participant::query()
                ->whereHas('event', fn ($q) => auth()->user()->role !== User::ROLE_ADMIN
                    ? $q->where('created_by', auth()->id()) 
                    : $q
                )
                ->count(),
            'total_evaluations' => Evaluation::query()
                ->whereHas('participant.event', fn ($q) => auth()->user()->role !== User::ROLE_ADMIN
                    ? $q->where('created_by', auth()->id()) 
                    : $q
                )
                ->count(),
            'upcoming_events' => (clone $eventsQuery)->whereDate('start_date', '>', $today)->count(),
        ];

        // Get upcoming/ongoing events (NOT completed) ordered by start_date ascending
        // Upcoming/Ongoing = events that haven't ended yet (end_date >= today)
        // Limit to 6 to ensure completed events also display
        $upcomingRecentEvents = (clone $eventsQuery)
            ->withCount('participants')
            ->whereDate('end_date', '>=', $today)
            ->orderBy('start_date')
            ->limit(6)
            ->get();

        // Get completed events (ordered by end_date descending - most recent first)
        // Completed = events that have already ended (end_date < today)
        // Always show 4 completed events to ensure all status types are visible in dashboard
        $completedRecentEvents = (clone $eventsQuery)
            ->withCount('participants')
            ->whereDate('end_date', '<', $today)
            ->orderByDesc('end_date')
            ->limit(4)
            ->get();

        // Combine upcoming and completed events (up to 10 total)
        // This ensures admins see a mix of all statuses: upcoming, ongoing, and completed
        // Upcoming = start_date > today
        // Ongoing = start_date <= today AND end_date >= today
        // Completed = end_date < today
        $recentEvents = $upcomingRecentEvents->concat($completedRecentEvents);

        $upcomingEvents = (clone $eventsQuery)
            ->withCount('participants')
            ->whereDate('start_date', '>', $today)
            ->orderBy('start_date')
            ->limit(6)
            ->get();

        $registrationCounts = Participant::query()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->whereDate('created_at', '>=', $startDate);
        
        if (auth()->user()->hasRole('event_staff')) {
            $registrationCounts->whereHas('event', fn ($q) => $q->where('created_by', auth()->id()));
        }
        
        $registrationCounts = $registrationCounts
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $chart = [];
        for ($i = 29; $i >= 0; $i--) {
            $day = now()->subDays($i)->toDateString();
            $chart[] = [
                'day' => $day,
                'label' => now()->subDays($i)->format('M d'),
                'total' => (int) ($registrationCounts[$day] ?? 0),
            ];
        }

        if ($wantsJson) {
            return response()->json([
                'stats' => $stats,
                'recent_events' => $recentEvents,
                'upcoming_events' => $upcomingEvents,
                'chart' => $chart,
            ]);
        }

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentEvents' => $recentEvents,
            'upcomingEvents' => $upcomingEvents,
            'registrationChart' => $chart,
        ]);
    }

    public function eventAnalytics(Request $request, SurveyActivationService $surveyActivationService)
    {
        try {
            $surveyActivationService->activateDueEvents();
        } catch (\Exception $e) {
            \Log::error('Survey activation failed: ' . $e->getMessage());
            // Page still loads even if email sending fails
        }

        $wantsJson = $request->expectsJson() || $request->is('api/*');
        $period = $request->string('period', 'overall')->toString();
        $month  = $request->integer('month') ?: now()->month;
        $year   = $request->integer('year')  ?: now()->year;

        // Base queries – scoped to the selected period
        // ADMIN: see analytics for all events
        // EVENT STAFF: see analytics only for events they created
        $baseParticipants = Participant::query();
        $baseEvaluations  = Evaluation::query();

        if (auth()->user()->role !== User::ROLE_ADMIN) {
            // Filter to own events for non-admins (event staff)
            $staffId = auth()->id();
            $baseParticipants->whereHas('event', fn ($q) => $q->where('created_by', $staffId));
            $baseEvaluations->whereHas('participant.event', fn ($q) => $q->where('created_by', $staffId));
        }

        if ($period === 'month') {
            $baseParticipants->whereHas('event', fn ($q) => $q->whereYear('start_date', $year)->whereMonth('start_date', $month));
            $baseEvaluations->whereHas('participant.event', fn ($q) => $q->whereYear('start_date', $year)->whereMonth('start_date', $month));
        }

        if ($period === 'year') {
            $baseParticipants->whereHas('event', fn ($q) => $q->whereYear('start_date', $year));
            $baseEvaluations->whereHas('participant.event', fn ($q) => $q->whereYear('start_date', $year));
        }

        $endedParticipantsQuery = (clone $baseParticipants)
            ->whereHas('event', fn ($q) => $q->whereDate('end_date', '<', now()->toDateString()));

        $endedEvaluationsQuery = (clone $baseEvaluations)
            ->whereHas('participant.event', fn ($q) => $q->whereDate('end_date', '<', now()->toDateString()));

        // Real-time participants count (all events regardless of status)
        $participantsTotal = $baseParticipants->count();
        
        // Attended and evaluations from ended events only (finalized data)
        $attendedTotal     = (clone $endedParticipantsQuery)->where('attended', true)->count();
        $evaluationsTotal  = $endedEvaluationsQuery->count();
        $avgRating         = round((float) (($endedEvaluationsQuery->avg('rating') ?? 0)), 1);

        $responseRate   = $participantsTotal > 0 ? round(($evaluationsTotal / $participantsTotal) * 100, 1) : 0;
        $attendanceRate = $participantsTotal > 0 ? round(($attendedTotal / $participantsTotal) * 100, 1) : 0;

        // Per-event breakdown (real-time data for all events, regardless of status)
        $eventsQuery = Event::query()
            ->withCount([
                'participants as participants_count',
                'participants as attended_count' => fn ($q) => $q->where('attended', true),
                'evaluations as evaluations_count' => fn ($q) => $q->whereHas('participant.event', fn ($q2) => $q2->whereDate('end_date', '<', now()->toDateString())),
            ])
            ->withAvg(['evaluations as avg_rating' => fn ($q) => $q->whereHas('participant.event', fn ($q2) => $q2->whereDate('end_date', '<', now()->toDateString()))], 'rating')
            ->orderBy('start_date', 'asc');

        if (auth()->user()->hasRole('event_staff')) {
            $eventsQuery->where('created_by', auth()->id());
        }

        if ($period === 'month') {
            $eventsQuery->whereYear('start_date', $year)->whereMonth('start_date', $month);
        }

        if ($period === 'year') {
            $eventsQuery->whereYear('start_date', $year);
        }

        $eventsBreakdown = $eventsQuery->get();
        $totalEvents     = $eventsBreakdown->count();

        $payload = [
            'period'                       => $period,
            'month'                        => $month,
            'year'                         => $year,
            'total_events'                 => $totalEvents,
            'total_participants'           => $participantsTotal,
            'attended_total'               => $attendedTotal,
            'total_evaluations'            => $evaluationsTotal,
            'participants_minus_evaluations' => $participantsTotal - $evaluationsTotal,
            'response_rate'                => $responseRate,
            'attendance_rate'              => $attendanceRate,
            'avg_rating'                   => $avgRating,
        ];

        if ($wantsJson) {
            return response()->json(['data' => $payload]);
        }

        return view('admin.event-analytics', [
            'analytics'       => $payload,
            'eventsBreakdown' => $eventsBreakdown,
        ]);
    }

    public function downloadAnalyticsReport(Request $request)
    {
        $period = $request->string('period', 'overall')->toString();
        $month  = $request->integer('month') ?: now()->month;
        $year   = $request->integer('year')  ?: now()->year;
        $eventId = $request->integer('event_id') ?: null;

        // Base queries – scoped to the selected period
        $baseParticipants = Participant::query();
        $baseEvaluations  = Evaluation::query();

        if (auth()->user()->role !== User::ROLE_ADMIN) {
            $staffId = auth()->id();
            $baseParticipants->whereHas('event', fn ($q) => $q->where('created_by', $staffId));
            $baseEvaluations->whereHas('participant.event', fn ($q) => $q->where('created_by', $staffId));
        }

        // Filter by event if provided
        if ($eventId) {
            $baseParticipants->where('event_id', $eventId);
            $baseEvaluations->whereHas('participant', fn ($q) => $q->where('event_id', $eventId));
        }

        if ($period === 'month') {
            $baseParticipants->whereHas('event', fn ($q) => $q->whereYear('start_date', $year)->whereMonth('start_date', $month));
            $baseEvaluations->whereHas('participant.event', fn ($q) => $q->whereYear('start_date', $year)->whereMonth('start_date', $month));
        }

        if ($period === 'year') {
            $baseParticipants->whereHas('event', fn ($q) => $q->whereYear('start_date', $year));
            $baseEvaluations->whereHas('participant.event', fn ($q) => $q->whereYear('start_date', $year));
        }

        $endedParticipantsQuery = (clone $baseParticipants)
            ->whereHas('event', fn ($q) => $q->whereDate('end_date', '<', now()->toDateString()));

        $endedEvaluationsQuery = (clone $baseEvaluations)
            ->whereHas('participant.event', fn ($q) => $q->whereDate('end_date', '<', now()->toDateString()));

        $participantsTotal = $endedParticipantsQuery->count();
        $attendedTotal     = (clone $endedParticipantsQuery)->where('attended', true)->count();
        $evaluationsTotal  = $endedEvaluationsQuery->count();
        $avgRating         = round((float) (($endedEvaluationsQuery->avg('rating') ?? 0)), 1);

        $responseRate   = $participantsTotal > 0 ? round(($evaluationsTotal / $participantsTotal) * 100, 1) : 0;
        $attendanceRate = $participantsTotal > 0 ? round(($attendedTotal / $participantsTotal) * 100, 1) : 0;

        // Per-event breakdown (real-time data for all events, regardless of status)
        $eventsQuery = Event::query()
            ->withCount([
                'participants as participants_count',
                'participants as attended_count' => fn ($q) => $q->where('attended', true),
                'evaluations as evaluations_count' => fn ($q) => $q->whereHas('participant.event', fn ($q2) => $q2->whereDate('end_date', '<', now()->toDateString())),
            ])
            ->withAvg(['evaluations as avg_rating' => fn ($q) => $q->whereHas('participant.event', fn ($q2) => $q2->whereDate('end_date', '<', now()->toDateString()))], 'rating')
            ->orderBy('start_date', 'asc');

        if (auth()->user()->hasRole('event_staff')) {
            $eventsQuery->where('created_by', auth()->id());
        }

        if ($eventId) {
            $eventsQuery->where('id', $eventId);
        }

        if ($period === 'month') {
            $eventsQuery->whereYear('start_date', $year)->whereMonth('start_date', $month);
        }

        if ($period === 'year') {
            $eventsQuery->whereYear('start_date', $year);
        }

        $eventsBreakdown = $eventsQuery->get();

        $participants = collect();
        if ($eventId) {
            $participantsQuery = Participant::query()
                ->where('event_id', $eventId)
                ->orderBy('name');

            if (auth()->user()->role !== User::ROLE_ADMIN) {
                $participantsQuery->whereHas('event', fn ($q) => $q->where('created_by', auth()->id()));
            }

            $participants = $participantsQuery->get();
        }

        $scopeLabel = match($period) {
            'month' => \Carbon\Carbon::create($year, $month)->format('F Y'),
            'year'  => (string) $year,
            default => 'All Time',
        };

        $pdf = Pdf::loadView('admin.analytics-report', [
            'period' => $period,
            'month' => $month,
            'year' => $year,
            'scopeLabel' => $scopeLabel,
            'totalEvents' => $eventsBreakdown->count(),
            'totalParticipants' => $participantsTotal,
            'attendedTotal' => $attendedTotal,
            'attendanceRate' => $attendanceRate,
            'totalEvaluations' => $evaluationsTotal,
            'responseRate' => $responseRate,
            'avgRating' => $avgRating,
            'eventsBreakdown' => $eventsBreakdown,
            'participants' => $participants,
        ]);

        $filename = 'analytics-report-' . now()->format('Y-m-d-His') . '.pdf';
        
        return $pdf->download($filename)
            ->header('Content-Type', 'application/octet-stream')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"; filename*=UTF-8\'\'' . $filename . '')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Mon, 01 Jan 1990 00:00:00 GMT')
            ->header('X-Content-Type-Options', 'nosniff');
    }

    public function downloadEventReport(Request $request, Event $event)
    {
        // Check authorization
        if (auth()->user()->role !== User::ROLE_ADMIN && $event->created_by !== auth()->id()) {
            abort(403);
        }

        $eventParticipants = $event->participants()->get();

        $totalParticipants = $eventParticipants->count();
        $attendedParticipants = $eventParticipants->where('attended', true)->count();
        $attendanceRate = $totalParticipants > 0 ? round(($attendedParticipants / $totalParticipants) * 100, 1) : 0;

        $evaluations = $event->evaluations;
        $totalEvaluations = $evaluations->count();
        $responseRate = $totalParticipants > 0 ? round(($totalEvaluations / $totalParticipants) * 100, 1) : 0;
        $avgRating = $totalEvaluations > 0 ? round($evaluations->avg('rating'), 1) : 0;

        // Get evaluation questions (for reference in PDF)
        $questions = $event->evaluationQuestions()
            ->where('type', '!=', 'text')
            ->get()
            ->map(function ($question) {
                return [
                    'question_text' => $question->question,
                    'avg_rating' => null, // Evaluations don't store per-question ratings
                ];
            });

        // Rating distribution (based on overall evaluation ratings)
        $ratingDistribution = [
            5 => $evaluations->where('rating', 5)->count(),
            4 => $evaluations->where('rating', 4)->count(),
            3 => $evaluations->where('rating', 3)->count(),
            2 => $evaluations->where('rating', 2)->count(),
            1 => $evaluations->where('rating', 1)->count(),
        ];

        // College/department breakdown - STRICTLY FOR EVENT 36 (NU Lipa Research Congress) ONLY
        $collegeBreakdown = null;
        if ($event->id === 36) {
            $collegeOrder = ['SACE', 'SABM', 'SAHS', 'SHS'];
            $collegeBreakdown = [];
            foreach ($collegeOrder as $collegeCode) {
                $collegeParticipants = $eventParticipants->where('college', $collegeCode);
                $collegeTotal = $collegeParticipants->count();
                $collegeAttended = $collegeParticipants->where('attended', true)->count();
                $collegeAttendanceRate = $collegeTotal > 0 ? round(($collegeAttended / $collegeTotal) * 100, 1) : 0;
                $collegeEvaluations = $evaluations->whereIn('participant_id', $collegeParticipants->pluck('id'));
                $collegeEvalTotal = $collegeEvaluations->count();
                $collegeResponseRate = $collegeTotal > 0 ? round(($collegeEvalTotal / $collegeTotal) * 100, 1) : 0;
                $collegeAvgRating = $collegeEvalTotal > 0 ? round($collegeEvaluations->avg('rating'), 1) : 0;

                $collegeBreakdown[] = [
                    'code' => $collegeCode,
                    'total' => $collegeTotal,
                    'attended' => $collegeAttended,
                    'attendance_rate' => $collegeAttendanceRate,
                    'eval_total' => $collegeEvalTotal,
                    'response_rate' => $collegeResponseRate,
                    'avg_rating' => $collegeAvgRating,
                ];
            }
        }

        $participantList = $eventParticipants
            ->sortBy('name')
            ->values()
            ->map(function ($participant) {
                return [
                    'name' => $participant->name,
                    'email' => $participant->email ?: 'N/A',
                    'role' => ucfirst(strtolower($participant->participant_type ?? 'N/A')),
                    'attended' => $participant->attended ? 'Yes' : 'No',
                ];
            });

        $pdf = Pdf::loadView('admin.event-analytics-report', [
            'event' => $event,
            'totalParticipants' => $totalParticipants,
            'attendedParticipants' => $attendedParticipants,
            'attendanceRate' => $attendanceRate,
            'totalEvaluations' => $totalEvaluations,
            'responseRate' => $responseRate,
            'avgRating' => $avgRating,
            'questions' => $questions,
            'ratingDistribution' => $ratingDistribution,
            'collegeBreakdown' => $collegeBreakdown,
            'participantList' => $participantList,
        ]);

        $filename = 'event-report-' . \Illuminate\Support\Str::slug($event->title) . '-' . now()->format('Y-m-d-His') . '.pdf';
        return $pdf->download($filename)
            ->header('Content-Type', 'application/octet-stream')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"; filename*=UTF-8\'\'' . $filename . '')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Mon, 01 Jan 1990 00:00:00 GMT')
            ->header('X-Content-Type-Options', 'nosniff');
    }
}
