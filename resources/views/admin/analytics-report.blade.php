<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Analytics Report</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .page {
            page-break-after: always;
            padding: 40px;
            background: #fff;
            position: relative;
        }
        .page:last-child {
            page-break-after: avoid;
        }
        .page-1 {
            page-break-after: always;
        }
        .page-2 {
            margin-top: 0;
            page-break-before: always;
        }
        .branding {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #1b6ca8;
        }
        .branding-logo {
            width: 50px;
            height: 50px;
            margin-right: 15px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .branding-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .branding-text {
            flex: 1;
        }
        .branding-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #999;
            margin-bottom: 3px;
        }
        .branding-name {
            font-size: 24px;
            font-weight: 700;
            color: #1b6ca8;
            letter-spacing: 0.02em;
        }
        .header {
            border-bottom: 3px solid #1b6ca8;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 28px;
            color: #1b6ca8;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12px;
            color: #666;
        }
        .scope-info {
            background: #f0f4f8;
            padding: 12px;
            border-left: 4px solid #1b6ca8;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .scope-info strong {
            color: #1b6ca8;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .summary-card {
            background: #f8fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        .summary-card-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #666;
            margin-bottom: 8px;
        }
        .summary-card-value {
            font-size: 24px;
            font-weight: 700;
            color: #1b6ca8;
            margin-bottom: 5px;
        }
        .summary-card-sub {
            font-size: 11px;
            color: #999;
        }
        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #0a2342;
            margin-top: 30px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        tbody tr {
            page-break-inside: avoid;
        }
        th {
            background: #f0f4f8;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #666;
            border-bottom: 2px solid #d1d5db;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
        }
        .badge-conference {
            background: rgba(27, 108, 168, 0.1);
            color: #1b6ca8;
        }
        .badge-school {
            background: rgba(10, 35, 66, 0.1);
            color: #0a2342;
        }
        .rate-high {
            background: #ecfdf5;
            color: #047857;
        }
        .rate-mid {
            background: #fffbeb;
            color: #b45309;
        }
        .rate-low {
            background: #fef2f2;
            color: #b91c1c;
        }
        .rate-na {
            background: #f3f4f6;
            color: #6b7280;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
        .progress-bar-wrap {
            background: #e5e7eb;
            height: 6px;
            border-radius: 3px;
            margin-top: 3px;
            overflow: hidden;
        }
        .progress-bar {
            height: 100%;
            background: #1b6ca8;
            border-radius: 3px;
        }
        .highlight {
            font-weight: 600;
            color: #1b6ca8;
        }
        .text-center {
            text-align: center;
        }
        .text-muted {
            color: #999;
        }
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="page page-1">
        <div class="branding">
            <div class="branding-logo">
                <img src="{{ public_path('eventurelogo.png') }}" alt="Eventure logo">
            </div>
            <div class="branding-text">
                <div class="branding-name">Eventure</div>
            </div>
        </div>

        <div class="header">
            <h1>Analytics Summary</h1>
            <p>Generated on {{ now()->format('F d, Y') }} at {{ now()->format('h:i A') }}</p>
        </div>

        <div class="scope-info">
            <strong>Scope:</strong> {{ $scopeLabel }}
        </div>

        @if ($totalEvents > 0)
            <div class="summary-grid">
                <div class="summary-card">
                    <div class="summary-card-label">Total Events</div>
                    <div class="summary-card-value">{{ $totalEvents }}</div>
                    <div class="summary-card-sub">In selected scope</div>
                </div>

                <div class="summary-card">
                    <div class="summary-card-label">Total Participants</div>
                    <div class="summary-card-value">{{ number_format($totalParticipants) }}</div>
                    <div class="summary-card-sub">{{ $attendedTotal }} attended</div>
                </div>

                <div class="summary-card">
                    <div class="summary-card-label">Attendance Rate</div>
                    <div class="summary-card-value">{{ $attendanceRate }}%</div>
                    <div class="progress-bar-wrap">
                        <div class="progress-bar" style="width: {{ $attendanceRate }}%;"></div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-card-label">Avg. Rating</div>
                    <div class="summary-card-value">{{ $avgRating > 0 ? $avgRating : '—' }}</div>
                    <div class="summary-card-sub">{{ $responseRate }}% response rate</div>
                </div>
            </div>
        @else
            <div class="empty-state">
                No events found for the selected scope.
            </div>
        @endif

        <div class="footer">
            <p>Eventure | Event Analytics Report</p>
            <p>{{ config('app.name') }} © {{ now()->year }}</p>
        </div>
    </div>

    @if ($totalEvents > 0)
    <div class="page page-2">
        <div class="branding">
            <div class="branding-logo">
                <img src="{{ public_path('eventurelogo.png') }}" alt="Eventure logo">
            </div>
            <div class="branding-text">
                <div class="branding-name">Eventure</div>
            </div>
        </div>

        <div class="section-title">Event Breakdown</div>
        <table>
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Participants</th>
                    <th>Attended</th>
                    <th>Evaluations</th>
                    <th>Response Rate</th>
                    <th>Avg Rating</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($eventsBreakdown as $event)
                    @php
                        $evRate = $event->participants_count > 0
                            ? round(($event->evaluations_count / $event->participants_count) * 100)
                            : null;
                        $evAttRate = $event->participants_count > 0
                            ? round(($event->attended_count / $event->participants_count) * 100)
                            : null;
                        $isEnded = $event->hasEnded();
                        $rateClass = is_null($evRate) || !$isEnded ? 'rate-na' : ($evRate >= 70 ? 'rate-high' : ($evRate >= 40 ? 'rate-mid' : 'rate-low'));
                        $evAvgRating = $event->avg_rating && $isEnded
                            ? round((float) $event->avg_rating, 1)
                            : null;
                    @endphp
                    <tr>
                        <td><strong>{{ \Illuminate\Support\Str::limit($event->title, 30, '…') }}</strong></td>
                        <td>{{ $event->dateRangeLabel() }}</td>
                        <td>
                            <span class="badge {{ $event->type === 'conference' ? 'badge-conference' : 'badge-school' }}">
                                {{ $event->type === 'conference' ? 'Conference' : 'School' }}
                            </span>
                        </td>
                        <td>{{ number_format($event->participants_count) }}</td>
                        <td>{{ number_format($event->attended_count) }} @if (!is_null($evAttRate))<span class="text-muted">({{ $evAttRate }}%)</span>@endif</td>
                        <td>{{ $isEnded ? number_format($event->evaluations_count) : '—' }}</td>
                        <td>
                            <span class="badge {{ $rateClass }}">
                                {{ !$isEnded ? '—' : (is_null($evRate) ? '—' : $evRate . '%') }}
                            </span>
                        </td>
                        <td>{{ $evAvgRating ? $evAvgRating : '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-state">No events found for this scope.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <p>Eventure | Event Analytics Report</p>
            <p>{{ config('app.name') }} © {{ now()->year }}</p>
        </div>
    </div>
    @endif
</body>
</html>
