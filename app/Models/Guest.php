<?php

namespace App\Models;

use App\Models\GuestEvaluation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'email',
        'role',
        'bio',
        'digital_token',
        'status',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(GuestEvaluation::class);
    }

    public function averageRating(): ?float
    {
        $average = $this->evaluations()->avg('rating');

        return $average !== null ? round((float) $average, 2) : null;
    }
}
