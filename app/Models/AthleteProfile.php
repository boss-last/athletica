<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AthleteProfile extends Model
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
        'user_id', 'height', 'weight', 'fitness_level',
        'primary_sport', 'secondary_sports', 'max_heart_rate', 'rest_heart_rate'
    ];

    protected $casts = [
        'secondary_sports' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
