@component('mail::message')
# Evaluation Form Now Available

Hello {{ $participant->name }},

The evaluation form for **{{ $event->title }}** is now available! As an attended participant, your feedback is important to us.

Click the button below to open your Digital ID and automatically access the evaluation form:

@component('mail::button', ['url' => $evaluationUrl])
Open Digital ID & Complete Evaluation
@endcomponent

**Event Details:**
- Event: {{ $event->title }}
- Date: {{ $event->dateRangeLabel() }}

Your feedback will help us improve future events. Thank you for your participation!

Best regards,<br>
The Event Team
@endcomponent
