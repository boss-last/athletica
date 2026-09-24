<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Challenge extends Model
{
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
        'creator_id', 'title', 'description', 'challenge_type',
        'target_value', 'sport_type', 'start_date', 'end_date',
        'max_participants', 'is_public', 'prize_description'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
    public function getIsPublicAttribute($value)
{
    return $value == 1;
}

    public function creator() { return $this->belongsTo(User::class, 'creator_id'); }
    public function participants() { return $this->belongsToMany(User::class, 'challenge_participants')->withPivot('current_progress', 'joined_at'); }
}
