<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Goal extends Model
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
        'user_id', 'title', 'description', 'goal_type',
        'target_value', 'current_value', 'sport_type',
        'start_date', 'end_date', 'is_completed'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'completed_at' => 'datetime',
        'target_value' => 'decimal:2',
        'current_value' => 'decimal:2',
    ];

    public function user() { return $this->belongsTo(User::class); }

    public function getProgressAttribute()
    {
        return $this->target_value > 0 ? round(($this->current_value / $this->target_value) * 100, 1) : 0;
    }
}
