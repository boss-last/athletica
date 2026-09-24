<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TrainingSession extends Model
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
        'plan_id', 'week_number', 'day_number', 'title',
        'description', 'sport_type', 'planned_duration',
        'planned_distance', 'intensity', 'notes'
    ];

    public function plan()
    {
        return $this->belongsTo(TrainingPlan::class, 'plan_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
