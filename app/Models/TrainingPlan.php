<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TrainingPlan extends Model
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
        'coach_id', 'name', 'description', 'sport_type',
        'difficulty_level', 'duration_weeks', 'is_public', 'price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function sessions()
    {
        return $this->hasMany(TrainingSession::class, 'plan_id');
    }

    public function getIsPublicAttribute($value)
    {
        return $value == 1;
    }
}
