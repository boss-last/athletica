<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    protected $fillable = [
        'email',
        'username',
        'full_name',
        'password',
        'avatar_url',
        'bio',
        'date_of_birth',
        'gender',
        'role',
        'is_active',
        'is_premium',
        'premium_expires_at',
        'verification_token',
        'reset_token',
        'reset_token_expires_at',
        'login_code',
        'login_code_expires_at',
    ];
    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'is_premium' => 'boolean',
         'email_verified_at' => 'datetime',
        'reset_token_expires_at' => 'datetime',
        'login_code_expires_at' => 'datetime',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'is_active' => 'boolean',
            'is_premium' => 'boolean',
            'premium_expires_at' => 'datetime',
        ];
    }

    // ==========================================
    // RELATIONS
    // ==========================================

    public function athleteProfile()
    {
        return $this->hasOne(AthleteProfile::class);
    }

    public function platformConnections()
    {
        return $this->hasMany(PlatformConnection::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }

    public function stats()
    {
        return $this->hasMany(UserStat::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withTimestamps();
    }

    public function userBadges()
    {
        return $this->hasMany(UserBadge::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
    }

    public function comments()
    {
        return $this->hasMany(ActivityComment::class);
    }

    public function likes()
    {
        return $this->hasMany(ActivityLike::class);
    }

    public function fitnessMetrics()
    {
        return $this->hasMany(FitnessMetric::class);
    }

    public function createdChallenges()
    {
        return $this->hasMany(Challenge::class, 'creator_id');
    }

    public function joinedChallenges()
    {
        return $this->belongsToMany(Challenge::class, 'challenge_participants')
            ->withPivot('current_progress', 'joined_at');
    }

    public function coachingPlans()
    {
        return $this->hasMany(TrainingPlan::class, 'coach_id');
    }

    // Notifications
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ==========================================
    // ABONNEMENT / PREMIUM
    // ==========================================

    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Un utilisateur est premium s'il a le flag is_premium ET que l'abonnement
     * n'a pas expiré (premium_expires_at dans le futur, ou null = illimité).
     */
    public function hasPremiumAccess(): bool
    {
        if (!$this->is_premium) {
            return false;
        }

        return $this->premium_expires_at === null
            || $this->premium_expires_at->isFuture();
    }

    public function currentPlan(): string
    {
        return optional($this->subscription)->plan_type ?? 'free';
    }
}
