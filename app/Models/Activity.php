<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activity extends Model
{
    use HasFactory;
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
        'user_id', 'platform_connection_id', 'external_id', 'name',
        'sport_type', 'description', 'start_time', 'end_time',
        'duration_seconds', 'distance_meters', 'calories_burned',
        'avg_heart_rate', 'max_heart_rate', 'avg_speed', 'max_speed',
        'elevation_gain', 'device_type', 'is_manual', 'is_private',
        'notes', 'raw_data', 'weather_data', 'perceived_effort'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'raw_data' => 'array',
        'weather_data' => 'array',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function platformConnection()
    {
        return $this->belongsTo(PlatformConnection::class);
    }

    public function gpsPoints()
    {
        return $this->hasMany(ActivityGpsPoint::class);
    }

    public function comments()
    {
        return $this->hasMany(ActivityComment::class);
    }

    public function likes()
    {
        return $this->hasMany(ActivityLike::class);
    }

    public function trainingSessions()
    {
        return $this->hasMany(TrainingSession::class);
    }

    // Accesseurs
    public function getDistanceKmAttribute()
    {
        return round($this->distance_meters / 1000, 2);
    }

    public function getPaceAttribute()
    {
        if (!$this->duration_seconds || !$this->distance_meters) return null;
        $pace = $this->duration_seconds / ($this->distance_meters / 1000);
        return gmdate('i:s', (int)$pace);
    }

    public function getFormattedDurationAttribute()
    {
        return gmdate('H:i:s', $this->duration_seconds ?? 0);
    }
}
