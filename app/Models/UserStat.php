<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserStat extends Model
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
        'user_id', 'period', 'period_date',
        'total_activities', 'total_distance_meters', 'total_duration_seconds',
        'total_calories', 'total_elevation_gain',
        'avg_heart_rate', 'avg_speed', 'sports_breakdown'
    ];

    protected $casts = ['period_date' => 'date', 'sports_breakdown' => 'array'];

    public function user() { return $this->belongsTo(User::class); }
}
