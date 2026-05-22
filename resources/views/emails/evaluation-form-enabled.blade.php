<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Evaluation Form Now Available</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            margin: 20px 0;
            background: #2563eb;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
        }
        .content {
            max-width: 600px;
            margin: 0 auto;
        }
        .footer {
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Evaluation Form Now Available</h1>

        <p>Hello {{ $participant->name }},</p>

        <p>The evaluation form for <strong>{{ $event->title }}</strong> is now available! As an attended participant, your feedback is important to us.</p>

        <p>
            <a href="{{ $evaluationUrl }}" class="button">Open Digital ID &amp; Complete Evaluation</a>
        </p>

        <p><strong>Event Details:</strong></p>
        <ul>
            <li>Event: {{ $event->title }}</li>
            <li>Date: {{ $event->dateRangeLabel() }}</li>
        </ul>

        <p>Your feedback will help us improve future events. Thank you for your participation!</p>

        <p class="footer">
            Best regards,<br>
            The Event Team
        </p>
    </div>
</body>
</html>
