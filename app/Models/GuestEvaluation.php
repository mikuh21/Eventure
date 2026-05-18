<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'rating',
        'feedback',
        'answers',
    ];

    protected function casts(): array
    {
        return [
            'answers' => 'array',
        ];
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }
}
