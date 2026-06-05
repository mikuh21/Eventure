<?php

namespace App\Http\Controllers;

use App\Models\Event;

class LandingController extends Controller
{
    public function __invoke()
    {
        $now = now('Asia/Manila');
        $today = $now->copy()->startOfDay();
        $todayEnd = $today->copy()->endOfDay();

        $ongoingEvents = Event::query()
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->withCount('participants')
            ->orderBy('start_date', 'asc')
            ->get();

        $upcomingEvents = Event::query()
            ->whereDate('start_date', '>', $today)
            ->withCount('participants')
            ->orderBy('start_date')
            ->take(6)
            ->get();

        $recentEvents = Event::query()
            ->whereDate('end_date', '<', $today)
            ->withCount('participants')
            ->latest('end_date')
            ->take(6)
            ->get();

        $announcements = Event::query()
            ->whereDate('start_date', '>=', $today->copy()->subDays(7))
            ->orderBy('start_date')
            ->get();

        $stats = [
            ['value' => Event::whereDate('end_date', '<', $today)->count(), 'label' => 'Successful Events'],
        ];

        return view('landing', compact(
            'now',
            'ongoingEvents',
            'upcomingEvents',
            'recentEvents',
            'announcements',
            'stats',
        ));
    }
}
