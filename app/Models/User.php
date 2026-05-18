<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_EVENT_STAFF = 'event_staff';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';
    public const APPROVAL_PENDING = 'pending';
    public const APPROVAL_APPROVED = 'approved';
    public const APPROVAL_DISAPPROVED = 'disapproved';

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_EVENT_STAFF => 'Event Staff',
            default => 'User',
        };
    }

    protected $fillable = [
        'name',
        'email',
        'role',
        'approval_status',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted(): void
    {
        static::creating(function (User $user): void {
            if (! $user->role) {
                $user->role = self::ROLE_USER;
            }

            if (! $user->approval_status) {
                $user->approval_status = $user->role === self::ROLE_ADMIN
                    ? self::APPROVAL_APPROVED
                    : ($user->role === self::ROLE_EVENT_STAFF ? self::APPROVAL_PENDING : self::APPROVAL_APPROVED);
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function isEventStaff(): bool
    {
        return $this->role === self::ROLE_EVENT_STAFF;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function isApproved(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isUser()) {
            return true;
        }

        return $this->approval_status === self::APPROVAL_APPROVED;
    }

    public function canAccessBackoffice(): bool
    {
        return $this->isAdmin() || ($this->isEventStaff() && $this->isApproved());
    }

    public function canManageEvaluationQuestions(): bool
    {
        return $this->isAdmin();
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }
}
