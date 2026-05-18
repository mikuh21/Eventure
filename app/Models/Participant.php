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
        'email',
        'event_id',
        'digital_id_token',
        'attended',
    ];

    protected $casts = [
        'attended' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'digital_id_token';
    }

    protected static function booted(): void
    {
        static::creating(function (Participant $participant): void {
            if (! $participant->digital_id_token) {
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
}
