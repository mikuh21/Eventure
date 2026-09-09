<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Report - {{ $event->title }}</title>
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
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .page {
            padding: 30px;
            background: #fff;
            position: relative;
            display: block;
            margin: 0;
            box-sizing: border-box;
        }
        .page:last-child {
            page-break-after: avoid;
        }
        .branding {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
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
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #666;
            font-style: italic;
        }
        .header {
            border-bottom: 3px solid #1b6ca8;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 24px;
            color: #1b6ca8;
            margin-bottom: 3px;
        }
        .header p {
            font-size: 11px;
            color: #666;
        }
        .event-details {
            background: #f0f4f8;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 11px;
            line-height: 1.6;
        }
        .event-details div {
            margin-bottom: 6px;
        }
        .event-details strong {
            color: #1b6ca8;
            display: inline-block;
            width: 110px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }
        .summary-card {
            background: #f8fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
        }
        .summary-card-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #666;
            margin-bottom: 6px;
        }
        .summary-card-value {
            font-size: 20px;
            font-weight: 700;
            color: #1b6ca8;
            margin-bottom: 3px;
        }
        .summary-card-sub {
            font-size: 10px;
            color: #999;
        }
        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #0a2342;
            margin-top: 15px;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            page-break-inside: auto;
        }
        thead {
            display: table-header-group;
        }
        tbody tr {
            page-break-inside: avoid;
        }
        th {
            background: #f0f4f8;
            padding: 8px;
            border-top: 24px solid #ffffff;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #666;
            border-bottom: 2px solid #d1d5db;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .progress-bar-wrap {
            background: #e5e7eb;
            height: 4px;
            border-radius: 3px;
            margin-top: 2px;
            overflow: hidden;
        }
        .progress-bar {
            height: 100%;
            background: #1b6ca8;
            border-radius: 3px;
        }
        .rating-row {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        .rating-label {
            width: 65px;
            font-weight: 600;
            font-size: 11px;
        }
        .star-rating {
            display: inline-block;
            white-space: nowrap;
        }
        .star {
            display: inline-block;
            width: 11px;
            height: 11px;
            margin-right: 2px;
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
            -webkit-clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
        }
        .star-filled {
            background-color: #1b6ca8;
        }
        .star-empty {
            background-color: #d1d5db;
        }
        .rating-count {
            width: 35px;
            text-align: right;
            font-size: 11px;
        }
        .distribution-bar {
            flex: 1;
            margin: 0 8px;
            height: 16px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
        }
        .distribution-fill {
            height: 100%;
            background: #1b6ca8;
        }
        .question-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px;
            margin-bottom: 6px;
            background: #f8fafb;
            border-radius: 6px;
        }
        .question-text {
            flex: 1;
            font-size: 10px;
        }
        .question-rating {
            font-weight: 700;
            color: #1b6ca8;
            margin-left: 8px;
            font-size: 11px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #999;
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
        .text-muted {
            color: #999;
            font-size: 11px;
        }
        .empty-state {
            text-align: center;
            padding: 30px;
            color: #999;
            font-size: 12px;
            background: #f8fafb;
            border-radius: 6px;
        }
        .page-break {
            page-break-before: always;
            padding-top: 50px;
            margin-top: 0;
        }
        .page2-branding {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #1b6ca8;
        }
        .page2-branding-logo {
            width: 40px;
            height: 40px;
            margin-right: 12px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .page2-branding-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .page2-branding-text {
            flex: 1;
        }
        .page2-branding-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #999;
            margin-bottom: 2px;
        }
        .page2-branding-name {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #666;
            font-style: italic;
        }
        .card-footer-text {
            margin-top: 8px;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="branding">
            <div class="branding-logo">
                <img src="{{ public_path('eventure-signinlogo.png') }}" alt="Eventure logo">
            </div>
            <div class="branding-text">
                <div class="branding-name">Eventure</div>
            </div>
        </div>

        <div class="header">
            <h1>{{ \Illuminate\Support\Str::limit($event->title, 50, '…') }}</h1>
            <p>Event Report Summary | Generated on {{ now()->format('F d, Y') }} at {{ now()->format('h:i A') }}</p>
        </div>

        <div class="event-details">
            <div><strong>Event Name:</strong> {{ $event->title }}</div>
            <div><strong>Date:</strong> {{ $event->dateRangeLabel() }}</div>
            <div><strong>Type:</strong> <span class="badge {{ $event->type === 'conference' ? 'badge-conference' : 'badge-school' }}">{{ $event->typeLabel() }}</span></div>
            <div><strong>Location:</strong> {{ $event->location ?? 'N/A' }}</div>
        </div>

        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-card-label">Total Participants</div>
                <div class="summary-card-value">{{ number_format($totalParticipants) }}</div>
                <div class="summary-card-sub">{{ $attendedParticipants }} attended</div>
            </div>

            <div class="summary-card">
                <div class="summary-card-label">Attendance Rate</div>
                <div class="summary-card-value">{{ $attendanceRate }}%</div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar" style="width: {{ $attendanceRate }}%;"></div>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-card-label">Response Rate</div>
                <div class="summary-card-value">{{ $responseRate }}%</div>
                <div class="summary-card-sub">{{ $totalEvaluations }} responses</div>
            </div>

            <div class="summary-card">
                <div class="summary-card-label">Avg. Rating</div>
                <div class="summary-card-value">{{ $avgRating > 0 ? $avgRating : '—' }}</div>
                <div class="summary-card-sub">
                    @if ($avgRating > 0)
                        <span style="font-family: DejaVu Sans, sans-serif; font-size: 11px;">
                            @for ($i = 1; $i <= 5; $i++)
                                <span style="color: {{ $i <= round($avgRating) ? '#1b6ca8' : '#d1d5db' }};">&#9733;</span>
                            @endfor
                        </span>
                    @else
                        No ratings
                    @endif
                </div>
                <div class="card-footer-text">Based on {{ $totalEvaluations }} evaluation{{ $totalEvaluations !== 1 ? 's' : '' }}</div>
            </div>
        </div>

        <div class="footer">
            <p>Eventure | Event Report Summary</p>
            <p>{{ config('app.name') }} © {{ now()->year }}</p>
        </div>

        @if ($totalEvaluations > 0)
            <div class="page-break">
                <div class="page2-branding">
                    <div class="page2-branding-logo">
                        <img src="{{ public_path('eventure-signinlogo.png') }}" alt="Eventure logo">
                    </div>
                    <div class="page2-branding-text">
                        <div class="page2-branding-name">Eventure</div>
                    </div>
                </div>

                <div class="section-title">Rating Distribution</div>
                <div>
                    @php
                        $maxRating = max(array_values($ratingDistribution));
                    @endphp
                    <table style="width:100%; border-collapse:collapse; margin:0;">
                    @for ($rating = 5; $rating >= 1; $rating--)
                        <tr style="margin-bottom:8px;">
                            <td style="width:70px; font-family: DejaVu Sans, sans-serif; font-size:13px; color:#1b6ca8; white-space:nowrap; padding:4px 0;">@for ($i = 1; $i <= $rating; $i++)★@endfor</td>
                            <td style="width:35px; text-align:right; font-size:11px; padding:4px 4px;">{{ $ratingDistribution[$rating] }}</td>
                            <td style="padding:4px 8px;">
                                <div style="background:#e5e7eb; height:16px; border-radius:3px; overflow:hidden;">
                                    @if ($maxRating > 0)
                                        <div style="width:{{ ($ratingDistribution[$rating] / $maxRating) * 100 }}%; height:100%; background:#1b6ca8; border-radius:3px;"></div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endfor
                    </table>
                </div>
            </div>

            @if ($collegeBreakdown)
                <div class="page-break">
                    <div class="page2-branding">
                        <div class="page2-branding-logo">
                            <img src="{{ public_path('eventure-signinlogo.png') }}" alt="Eventure logo">
                        </div>
                        <div class="page2-branding-text">
                            <div class="page2-branding-name">Eventure</div>
                        </div>
                    </div>
                    <div class="section-title">Attendance &amp; Evaluation by College/Department</div>
                    <table style="width:100%; border-collapse:collapse; margin-bottom:10px;">
                        <thead>
                            <tr>
                                <th>College</th>
                                <th>Participants</th>
                                <th>Attended</th>
                                <th>Attendance Rate</th>
                                <th>Responses</th>
                                <th>Response Rate</th>
                                <th>Avg. Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($collegeBreakdown as $college)
                                <tr>
                                    <td style="font-weight:700; color:#0a2342;">{{ $college['code'] }}</td>
                                    <td>{{ $college['total'] }}</td>
                                    <td>{{ $college['attended'] }}</td>
                                    <td>{{ $college['attendance_rate'] }}%</td>
                                    <td>{{ $college['eval_total'] }}</td>
                                    <td>{{ $college['response_rate'] }}%</td>
                                    <td>{{ $college['avg_rating'] > 0 ? $college['avg_rating'] : '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        @else
            <div class="section-title">Evaluation Data</div>
            <div class="empty-state">
                No evaluations submitted for this event yet.
            </div>
        @endif

            <div class="page-break">
                <div class="page2-branding">
                    <div class="page2-branding-logo">
                        <img src="{{ public_path('eventure-signinlogo.png') }}" alt="Eventure logo">
                    </div>
                    <div class="page2-branding-text">
                        <div class="page2-branding-name">Eventure</div>
                    </div>
                </div>
                <div class="section-title">Participant Breakdown</div>
                <table style="width:100%; border-collapse:collapse; margin-bottom:20px;">
                    <tbody>
                        <tr>
                            <td style="width:70%; font-weight:700;">Students</td>
                            <td>{{ $studentCount }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:700;">Coaches</td>
                            <td>{{ $coachCount }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:700;">Faculty</td>
                            <td>{{ $facultyCount }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:700;">Organizers</td>
                            <td>{{ $organizerCount }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:700;">Different Institutions</td>
                            <td>{{ $institutionCount }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="section-title">Participant List</div>
                <table style="width:100%; table-layout:fixed; border-collapse:collapse; margin-bottom:10px;">
                    <thead>
                        <tr>
                            <th style="width:30%;">Name</th>
                            <th style="width:25%;">Email</th>
                            <th style="width:15%;">Role</th>
                            <th style="width:20%;">Institution</th>
                            <th style="width:10%;">Attended</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($participantList as $row)
                            <tr>
                                <td style="overflow-wrap:anywhere;">{{ $row['name'] }}</td>
                                <td style="overflow-wrap:anywhere;">{{ $row['email'] }}</td>
                                <td style="overflow-wrap:anywhere;">{{ $row['role'] }}</td>
                                <td style="overflow-wrap:anywhere;">{{ $row['institution'] }}</td>
                                <td>{{ $row['attended'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">No participants registered for this event.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        <div class="footer">
            <p>Eventure | Event Report Summary</p>
            <p>{{ config('app.name') }} © {{ now()->year }}</p>
        </div>
    </div>
</body>
</html>
