<?php

namespace App\Http\Controllers;

use App\Models\Event;

class LandingController extends Controller
{
    public function __invoke()
    {
        $today = now()->startOfDay();

        $ongoingEvents = Event::query()
            ->where('start_date', '<=', now()->endOfDay())
            ->where('end_date', '>=', now()->startOfDay())
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
            'ongoingEvents',
            'upcomingEvents',
            'recentEvents',
            'announcements',
            'stats',
        ));
    }
}
