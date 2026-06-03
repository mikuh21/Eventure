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
            background: linear-gradient(135deg, #1b6ca8 0%, #0a2342 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 20px;
            margin-right: 15px;
            flex-shrink: 0;
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
            font-size: 18px;
            font-weight: 700;
            color: #1b6ca8;
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
        .event-details {
            background: #f0f4f8;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 12px;
            line-height: 1.8;
        }
        .event-details div {
            margin-bottom: 8px;
        }
        .event-details strong {
            color: #1b6ca8;
            display: inline-block;
            width: 120px;
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
        .rating-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .rating-label {
            width: 60px;
            font-weight: 600;
        }
        .rating-count {
            width: 40px;
            text-align: right;
        }
        .distribution-bar {
            flex: 1;
            margin: 0 10px;
            height: 20px;
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
            padding: 10px;
            margin-bottom: 8px;
            background: #f8fafb;
            border-radius: 6px;
        }
        .question-text {
            flex: 1;
            font-size: 11px;
        }
        .question-rating {
            font-weight: 700;
            color: #1b6ca8;
            margin-left: 10px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
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
    </style>
</head>
<body>
    <div class="page">
        <div class="branding">
            <div class="branding-logo">✦</div>
            <div class="branding-text">
                <div class="branding-label">Eventure</div>
                <div class="branding-name">Event Report</div>
            </div>
        </div>

        <div class="header">
            <h1>{{ \Illuminate\Support\Str::limit($event->title, 50, '…') }}</h1>
            <p>Event Report | Generated on {{ now()->format('F d, Y') }} at {{ now()->format('h:i A') }}</p>
        </div>

        <div class="event-details">
            <div><strong>Event Name:</strong> {{ $event->title }}</div>
            <div><strong>Date:</strong> {{ $event->dateRangeLabel() }}</div>
            <div><strong>Type:</strong> <span class="badge {{ $event->type === 'conference' ? 'badge-conference' : 'badge-school' }}">{{ $event->type === 'conference' ? 'Conference' : 'School Event' }}</span></div>
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
                        @for ($s = 1; $s <= 5; $s++)
                            {{ $s <= round($avgRating) ? '★' : '☆' }}
                        @endfor
                    @else
                        No ratings
                    @endif
                </div>
            </div>
        </div>

        @if ($totalEvaluations > 0)
            <div class="section-title">Rating Distribution</div>
            <div>
                @php
                    $maxRating = max(array_values($ratingDistribution));
                @endphp
                @for ($rating = 5; $rating >= 1; $rating--)
                    <div class="rating-row">
                        <div class="rating-label">{{ str_repeat('★', $rating) }}</div>
                        <div class="rating-count">{{ $ratingDistribution[$rating] }}</div>
                        <div class="distribution-bar">
                            @if ($maxRating > 0)
                                <div class="distribution-fill" style="width: {{ ($ratingDistribution[$rating] / $maxRating) * 100 }}%;"></div>
                            @endif
                        </div>
                    </div>
                @endfor
            </div>

            @if ($questions->count() > 0)
                <div class="section-title">Evaluation Questions</div>
                <div>
                    @foreach ($questions as $question)
                        <div class="question-row">
                            <div class="question-text">{{ $question['question_text'] }}</div>
                        </div>
                    @endforeach
                    <div style="margin-top: 10px; font-size: 11px; color: #999;">
                        Individual question ratings are tracked in the evaluation form responses.
                    </div>
                </div>
            @endif
        @else
            <div class="section-title">Evaluation Data</div>
            <div class="empty-state">
                No evaluations submitted for this event yet.
            </div>
        @endif

        <div class="footer">
            <p>Eventure | Event Report</p>
            <p>{{ config('app.name') }} © {{ now()->year }}</p>
        </div>
    </div>
</body>
</html>
