<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Evaluation;
use App\Models\Participant;
use App\Models\User;
use App\Services\SurveyActivationService;
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

        // Get upcoming/ongoing events first (ordered by start_date ascending)
        $upcomingRecentEvents = (clone $eventsQuery)
            ->withCount('participants')
            ->whereDate('start_date', '>=', $today)
            ->orderBy('start_date')
            ->limit(8)
            ->get();

        // Get completed events (ordered by end_date descending - most recent first)
        $completedRecentEvents = (clone $eventsQuery)
            ->withCount('participants')
            ->whereDate('end_date', '<', $today)
            ->orderByDesc('end_date')
            ->limit(8 - $upcomingRecentEvents->count())
            ->get();

        // Combine upcoming and completed events
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

        $participantsTotal = $endedParticipantsQuery->count();
        $attendedTotal     = (clone $endedParticipantsQuery)->where('attended', true)->count();
        $evaluationsTotal  = $endedEvaluationsQuery->count();
        $avgRating         = round((float) (($endedEvaluationsQuery->avg('rating') ?? 0)), 1);

        $responseRate   = $participantsTotal > 0 ? round(($evaluationsTotal / $participantsTotal) * 100, 1) : 0;
        $attendanceRate = $participantsTotal > 0 ? round(($attendedTotal / $participantsTotal) * 100, 1) : 0;

        // Per-event breakdown
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
}
