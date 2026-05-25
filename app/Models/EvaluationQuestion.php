<?php

namespace App\Models;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationQuestion extends Model
{
    use HasFactory;

    public const TYPE_RATING = 'rating';
    public const TYPE_TEXT = 'text';
    public const TYPE_TEXTAREA = 'textarea';
    public const TYPE_LIKERT = 'likert';

    public const EVENT_TYPE_SCHOOL = 'school_event';
    public const EVENT_TYPE_CONFERENCE = 'conference';
    public const EVENT_TYPE_ALL = 'all';

    protected $fillable = [
        'question',
        'field_key',
        'type',
        'placeholder',
        'help_text',
        'is_required',
        'is_active',
        'sort_order',
        'event_id',
        'event_type',
        'is_guest_question',
        'section',
        'is_matrix',
        'matrix_items',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'is_guest_question' => 'boolean',
            'is_matrix' => 'boolean',
            'matrix_items' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public static function typeOptions(): array
    {
        return [
            self::TYPE_LIKERT => 'Likert Scale (1-5)',
            self::TYPE_RATING => 'Rating (1-5 stars)',
            self::TYPE_TEXT => 'Short Text',
            self::TYPE_TEXTAREA => 'Long Text',
        ];
    }

    public function isProgramQuestion(): bool
    {
        return str_contains(strtolower(trim($this->question)), 'program');
    }

    public function renderingType(): string
    {
        return $this->isProgramQuestion() ? self::TYPE_TEXT : $this->type;
    }

    public static function eventTypeOptions(): array
    {
        return [
            self::EVENT_TYPE_SCHOOL => 'School Event',
            self::EVENT_TYPE_CONFERENCE => 'Conference',
            self::EVENT_TYPE_ALL => 'Both Event Types',
        ];
    }

    public static function normalizeEventType(string $eventType): string
    {
        return $eventType === 'school_event' ? self::EVENT_TYPE_SCHOOL : $eventType;
    }

    public static function ensureDefaultTemplatesForEventType(string $eventType): void
    {
        $eventType = self::normalizeEventType($eventType);

        $hasExisting = self::query()
            ->active()
            ->forEventType($eventType)
            ->exists();

        if ($hasExisting) {
            return;
        }

        foreach (self::defaultQuestionTemplatesForEventType($eventType) as $sortOrder => $questionData) {
            self::create(array_merge([
                'is_active' => true,
                'sort_order' => $sortOrder,
                'event_type' => $eventType,
                'is_guest_question' => false,
            ], $questionData));
        }
    }

    public static function createDefaultFormForEvent(Event $event): void
    {
        if (self::query()->where('event_id', $event->id)->exists()) {
            return;
        }

        foreach (self::defaultQuestionTemplatesForEventType($event->type) as $sortOrder => $questionData) {
            self::create(array_merge([
                'event_id' => $event->id,
                'event_type' => self::normalizeEventType($event->type),
                'is_active' => true,
                'sort_order' => $sortOrder + 1,
                'is_guest_question' => false,
            ], $questionData));
        }
    }

    public static function defaultQuestionTemplatesForEventType(string $eventType): array
    {
        $eventType = self::normalizeEventType($eventType);

        if ($eventType === self::EVENT_TYPE_CONFERENCE) {
            return [
                // Participant Information Section
                [
                    'question' => 'Name',
                    'field_key' => null,
                    'type' => self::TYPE_TEXT,
                    'placeholder' => 'Enter your full name',
                    'help_text' => null,
                    'is_required' => true,
                    'section' => 'Participant Information',
                    'is_matrix' => false,
                    'matrix_items' => null,
                ],
                [
                    'question' => 'Email',
                    'field_key' => null,
                    'type' => self::TYPE_TEXT,
                    'placeholder' => 'Enter your email address',
                    'help_text' => null,
                    'is_required' => true,
                    'section' => 'Participant Information',
                    'is_matrix' => false,
                    'matrix_items' => null,
                ],
                [
                    'question' => 'Organization/Affiliation',
                    'field_key' => null,
                    'type' => self::TYPE_TEXT,
                    'placeholder' => 'Enter your organization',
                    'help_text' => null,
                    'is_required' => false,
                    'section' => 'Participant Information',
                    'is_matrix' => false,
                    'matrix_items' => null,
                ],
                [
                    'question' => 'Position/Designation',
                    'field_key' => null,
                    'type' => self::TYPE_TEXT,
                    'placeholder' => 'Enter your position',
                    'help_text' => null,
                    'is_required' => false,
                    'section' => 'Participant Information',
                    'is_matrix' => false,
                    'matrix_items' => null,
                ],
                // Event Details Section
                [
                    'question' => 'Date',
                    'field_key' => null,
                    'type' => 'date',
                    'placeholder' => null,
                    'help_text' => null,
                    'is_required' => true,
                    'section' => 'Event Details',
                    'is_matrix' => false,
                    'matrix_items' => null,
                ],
                [
                    'question' => 'Time',
                    'field_key' => null,
                    'type' => 'time',
                    'placeholder' => null,
                    'help_text' => null,
                    'is_required' => true,
                    'section' => 'Event Details',
                    'is_matrix' => false,
                    'matrix_items' => null,
                ],
                [
                    'question' => 'Venue',
                    'field_key' => null,
                    'type' => self::TYPE_TEXT,
                    'placeholder' => 'Enter the venue',
                    'help_text' => null,
                    'is_required' => false,
                    'section' => 'Event Details',
                    'is_matrix' => false,
                    'matrix_items' => null,
                ],
                // Session Feedback Section
                [
                    'question' => 'Conference Quality',
                    'field_key' => null,
                    'type' => self::TYPE_LIKERT,
                    'placeholder' => null,
                    'help_text' => 'Rate the following aspects of the conference',
                    'is_required' => true,
                    'section' => 'Session Feedback',
                    'is_matrix' => true,
                    'matrix_items' => [
                        'Overall conference quality',
                        'Quality of speakers and presentations',
                        'Relevance of content to your interests',
                        'Organization and logistics',
                        'Venue and facilities',
                        'Networking opportunities',
                    ],
                ],
                // Open-ended Section
                [
                    'question' => 'What did you find most valuable about this conference?',
                    'field_key' => 'feedback',
                    'type' => self::TYPE_TEXTAREA,
                    'placeholder' => 'Enter your answer here...',
                    'help_text' => null,
                    'is_required' => false,
                    'section' => 'Open-ended Feedback',
                    'is_matrix' => false,
                    'matrix_items' => null,
                ],
                [
                    'question' => 'What areas could be improved for future conferences?',
                    'field_key' => null,
                    'type' => self::TYPE_TEXTAREA,
                    'placeholder' => 'Enter your suggestions here...',
                    'help_text' => null,
                    'is_required' => false,
                    'section' => 'Open-ended Feedback',
                    'is_matrix' => false,
                    'matrix_items' => null,
                ],
            ];
        }

        // School Event Templates
        return [
            // Participant Information Section
            [
                'question' => 'Name',
                'field_key' => null,
                'type' => self::TYPE_TEXT,
                'placeholder' => 'Last Name, First Name, Middle Name',
                'help_text' => 'Enter your full name only if you wish to be identified.',
                'is_required' => true,
                'section' => 'Participant Information',
                'is_matrix' => false,
                'matrix_items' => null,
            ],
            [
                'question' => 'Program',
                'field_key' => null,
                'type' => self::TYPE_TEXT,
                'placeholder' => 'Enter your program',
                'help_text' => 'Enter your program or course of study',
                'is_required' => true,
                'section' => 'Participant Information',
                'is_matrix' => false,
                'matrix_items' => null,
            ],
            [
                'question' => 'Email',
                'field_key' => null,
                'type' => self::TYPE_TEXT,
                'placeholder' => 'Enter your email address',
                'help_text' => null,
                'is_required' => true,
                'section' => 'Participant Information',
                'is_matrix' => false,
                'matrix_items' => null,
            ],
            [
                'question' => 'Title of Activity',
                'field_key' => null,
                'type' => self::TYPE_TEXT,
                'placeholder' => 'Enter the activity title',
                'help_text' => null,
                'is_required' => true,
                'section' => 'Event Details',
                'is_matrix' => false,
                'matrix_items' => null,
            ],
            [
                'question' => 'Date of Activity',
                'field_key' => null,
                'type' => 'date',
                'placeholder' => null,
                'help_text' => 'Select the date of the activity',
                'is_required' => true,
                'section' => 'Event Details',
                'is_matrix' => false,
                'matrix_items' => null,
            ],
            [
                'question' => 'Time of Activity',
                'field_key' => null,
                'type' => 'time',
                'placeholder' => null,
                'help_text' => 'Select the time range (e.g., 08:00 - 14:30)',
                'is_required' => true,
                'section' => 'Event Details',
                'is_matrix' => false,
                'matrix_items' => null,
            ],
            [
                'question' => 'Organizer',
                'field_key' => null,
                'type' => self::TYPE_TEXT,
                'placeholder' => 'e.g. SACE',
                'help_text' => null,
                'is_required' => true,
                'section' => 'Event Details',
                'is_matrix' => false,
                'matrix_items' => null,
            ],
            // Session Feedback Section
            [
                'question' => 'Activity Content and Delivery',
                'field_key' => null,
                'type' => self::TYPE_LIKERT,
                'placeholder' => null,
                'help_text' => 'Rate the following aspects on a scale from 1 to 5',
                'is_required' => true,
                'section' => 'Session Feedback',
                'is_matrix' => true,
                'matrix_items' => [
                    'Objectives were clear and well-explained',
                    'The activity was engaging and interactive',
                    'The content of the discussion was relevant and appropriate',
                    'Instructions were clear and easy to follow',
                    'Time was managed effectively',
                    'The target audience were well-informed to ensure participation',
                    'The venue equipment and materials were sufficient and appropriate',
                    'The facilitator/s and/or speaker/s was/were effective and has mastery of the subject matter',
                    'The organizer/s were attentive to the needs of the participants',
                    'The objectives of the activity were met',
                ],
            ],
            // Open-ended Section
            [
                'question' => 'What did you find most useful or insightful about the event?',
                'field_key' => 'feedback',
                'type' => self::TYPE_TEXTAREA,
                'placeholder' => 'Enter your answer here...',
                'help_text' => 'Provide specific highlights or insights.',
                'is_required' => false,
                'section' => 'Open-ended Feedback',
                'is_matrix' => false,
                'matrix_items' => null,
            ],
            [
                'question' => 'What challenges did you face?',
                'field_key' => null,
                'type' => self::TYPE_TEXTAREA,
                'placeholder' => 'Enter your answer here...',
                'help_text' => null,
                'is_required' => false,
                'section' => 'Open-ended Feedback',
                'is_matrix' => false,
                'matrix_items' => null,
            ],
            [
                'question' => 'What areas could be improved for future similar activities?',
                'field_key' => null,
                'type' => self::TYPE_TEXTAREA,
                'placeholder' => 'Enter your answer here...',
                'help_text' => null,
                'is_required' => false,
                'section' => 'Open-ended Feedback',
                'is_matrix' => false,
                'matrix_items' => null,
            ],
        ];
    }

    public static function fieldKeyOptions(): array
    {
        return [
            'rating' => 'Average Rating',
            'feedback' => 'Feedback Summary',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function scopeForEvent($query, Event $event)
    {
        return $query->where('event_id', $event->id);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForEventType($query, string $eventType)
    {
        $eventType = self::normalizeEventType($eventType);

        return $query->whereIn('event_type', [$eventType, self::EVENT_TYPE_ALL]);
    }

    public function scopeParticipantQuestions($query)
    {
        return $query->where('is_guest_question', false);
    }

    public function scopeGuestQuestions($query)
    {
        return $query->where('is_guest_question', true);
    }
}