<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'participant_type',
        'institution',
        'college',
        'email',
        'event_id',
        'digital_id_token',
        'digital_id_verified_at',
        'status',
        'attended',
    ];

    protected $casts = [
        'attended' => 'boolean',
        'digital_id_verified_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'digital_id_token';
    }

    public function getRouteKey(): string
    {
        if (! $this->digital_id_token) {
            $this->digital_id_token = Str::uuid()->toString();
            $this->save();
        }

        return $this->{$this->getRouteKeyName()};
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?: $this->getRouteKeyName();

        $participant = $this->where($field, $value)->first();

        if (! $participant && $field === $this->getRouteKeyName() && is_numeric($value)) {
            $participant = $this->where($this->getKeyName(), $value)->first();
        }

        return $participant;
    }

    protected static function booted(): void
    {
        static::creating(function (Participant $participant): void {
            // Only generate a digital id token automatically when the participant
            // is being created with an approved status (admin/staff-created).
            if (($participant->status ?? null) === 'approved' && ! $participant->digital_id_token) {
                $participant->digital_id_token = Str::uuid()->toString();
            }
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function hasSubmittedSurvey(): bool
    {
        return $this->evaluations()->exists();
    }
}
