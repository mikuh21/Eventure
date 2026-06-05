<?php

namespace App\Models;

use App\Models\EvaluationQuestion;
use App\Models\Guest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    public const ATTENDANCE_FACE_TO_FACE = 'face_to_face';
    public const ATTENDANCE_VIRTUAL = 'virtual';
    public const ATTENDANCE_BOTH = 'both';

    public const TYPE_SCHOOL = 'school_event';
    public const TYPE_CONFERENCE = 'conference';

    protected $fillable = [
        'type',
        'attendance_type',
        'title',
        'event_title',
        'conference_title',
        'theme',
        'description',
        'department',
        'program',
        'start_date',
        'end_date',
        'start_registration',
        'end_registration',
        'location',
        'meet_link',
        'poster_path',
        'template_file_path',
        'template_file_name',
        'keywords',
        'survey_activated_at',
        'evaluation_form_enabled',
        'evaluation_form_enabled_at',
        'auto_activate_evaluation',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_registration' => 'datetime',
        'end_registration' => 'datetime',
        'keywords' => 'array',
        'survey_activated_at' => 'datetime',
        'evaluation_form_enabled' => 'boolean',
        'evaluation_form_enabled_at' => 'datetime',
        'auto_activate_evaluation' => 'boolean',
        'attendance_type' => 'string',
    ];

    protected static function booted(): void
    {
        static::saving(function (Event $event): void {
            $event->title = $event->resolvedTitle();
        });

        static::created(function (Event $event): void {
            EvaluationQuestion::createDefaultFormForEvent($event);
        });
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    public function evaluationQuestions(): HasMany
    {
        return $this->hasMany(EvaluationQuestion::class, 'event_id');
    }

    public function submissions(): HasManyThrough
    {
        return $this->hasManyThrough(Submission::class, Participant::class);
    }

    public function evaluations(): HasManyThrough
    {
        return $this->hasManyThrough(Evaluation::class, Participant::class);
    }

    public function averageRating(): ?float
    {
        if (! $this->isSurveyActive()) {
            return null;
        }

        $average = $this->evaluations()->avg('rating');

        return $average !== null ? round((float) $average, 2) : null;
    }

    public static function attendanceTypeLabels(): array
    {
        return [
            self::ATTENDANCE_FACE_TO_FACE => 'Face-to-face',
            self::ATTENDANCE_VIRTUAL => 'Virtual',
            self::ATTENDANCE_BOTH => 'Both',
        ];
    }

    public function attendanceTypeLabel(): string
    {
        return self::attendanceTypeLabels()[$this->attendance_type ?? self::ATTENDANCE_FACE_TO_FACE] ?? self::attendanceTypeLabels()[self::ATTENDANCE_FACE_TO_FACE];
    }

    public function attendanceTypeBadgeClass(): string
    {
        return [
            self::ATTENDANCE_FACE_TO_FACE => 'badge-attendance-face-to-face',
            self::ATTENDANCE_VIRTUAL => 'badge-attendance-virtual',
            self::ATTENDANCE_BOTH => 'badge-attendance-both',
        ][ $this->attendance_type ?? self::ATTENDANCE_FACE_TO_FACE ] ?? 'badge-attendance-face-to-face';
    }

    public function getCertificateType(): string
    {
        return match ($this->attendance_type ?? self::ATTENDANCE_FACE_TO_FACE) {
            self::ATTENDANCE_VIRTUAL => 'Certificate of Participation',
            self::ATTENDANCE_BOTH => 'Certificate of Attendance and Participation',
            default => 'Certificate of Attendance',
        };
    }

    public function certificateRouteType(): string
    {
        return match ($this->attendance_type ?? self::ATTENDANCE_FACE_TO_FACE) {
            self::ATTENDANCE_VIRTUAL => 'participation',
            self::ATTENDANCE_BOTH => 'attendance-participation',
            default => 'attendance',
        };
    }

    public function resolvedTitle(): string
    {
        return (string) ($this->type === 'conference' ? $this->conference_title : $this->event_title);
    }

    public function dateRangeLabel(): string
    {
        $startDate = $this->start_date;
        $endDate = $this->end_date ?? $this->start_date;

        if (!$startDate) {
            return 'TBA';
        }

        if (!$endDate || $startDate->isSameDay($endDate)) {
            return $startDate->format('M d, Y');
        }

        // Same month and year: June 15 – 16, 2026
        if ($startDate->format('F Y') === $endDate->format('F Y')) {
            return $startDate->format('M d') . ' – ' . $endDate->format('d, Y');
        }

        // Different months same year: June 30 – July 1, 2026
        if ($startDate->format('Y') === $endDate->format('Y')) {
            return $startDate->format('M d') . ' – ' . $endDate->format('M d, Y');
        }

        // Different years: Dec 31, 2026 – Jan 1, 2027
        return $startDate->format('M d, Y') . ' – ' . $endDate->format('M d, Y');
    }

    public function isRegistrationOpen(): bool
    {
        $now = now();

        return $this->start_registration !== null
            && $this->end_registration !== null
            && $now->gte($this->start_registration)
            && $now->lte($this->end_registration);
    }

    public function scopeRegistrationOpen(Builder $query): Builder
    {
        $now = now('Asia/Manila');

        return $query->where(function (Builder $query) use ($now): void {
            $query->whereNotNull('start_registration')
                ->whereNotNull('end_registration')
                ->where('start_registration', '<=', $now)
                ->where('end_registration', '>=', $now);
        })->orWhere(function (Builder $query) use ($now): void {
            $query->whereNull('start_registration')
                ->orWhereNull('end_registration');

            $query->whereDate('start_date', '>=', $now->toDateString());
        });
    }

    public function hasEnded(): bool
    {
        $endDate = $this->end_date ?? $this->start_date;
        // Treat all events as ending at 12:05AM on the day after their end_date (after forms disable)
        // This gives a 5-minute grace period after midnight for participants to complete surveys
        $endDateTime = $endDate?->copy()->addDay()->setTime(0, 5, 0);
        return $endDateTime?->lte(now('Asia/Manila')) ?? false;
    }

    public function isSurveyActive(): bool
    {
        return $this->evaluation_form_enabled || $this->survey_activated_at !== null;
    }

    public function isEvaluationFormEnabled(): bool
    {
        return $this->evaluation_form_enabled;
    }

    public function getStatus(): string
    {
        if ($this->hasEnded()) {
            return 'Completed';
        }

        $now = now();
        $startDate = $this->start_date?->startOfDay();
        $endDate = $this->end_date?->endOfDay();

        if ($startDate && $endDate && $now->between($startDate, $endDate)) {
            return 'Ongoing';
        }

        if ($this->start_date?->isFuture()) {
            return 'Upcoming';
        }

        return 'Upcoming';
    }

    public function isOngoing(): bool
    {
        if ($this->hasEnded()) {
            return false;
        }

        $now = now('Asia/Manila');
        $startDate = $this->start_date?->startOfDay();
        $endDate = $this->end_date?->endOfDay();

        return $startDate && $endDate && $now->between($startDate, $endDate);
    }

    public function filterSortPriority(): int
    {
        if ($this->hasEnded()) {
            return 3;
        }

        return $this->isOngoing() ? 1 : 2;
    }

    public function enableEvaluationForm(): void
    {
        $this->update([
            'evaluation_form_enabled' => true,
            'evaluation_form_enabled_at' => now(),
        ]);
    }

    public function disableEvaluationForm(): void
    {
        $this->update([
            'evaluation_form_enabled' => false,
        ]);
    }

    public function getActiveEvaluationQuestions()
    {
        return $this->evaluationQuestions()
            ->active()
            ->participantQuestions()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function getActiveGuestEvaluationQuestions()
    {
        return $this->evaluationQuestions()
            ->active()
            ->guestQuestions()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function getAttendedParticipants()
    {
        return $this->participants()->where('attended', true)->get();
    }
}
