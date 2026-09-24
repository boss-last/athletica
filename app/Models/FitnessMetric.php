<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FitnessMetric extends Model
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

    protected $fillable = ['user_id', 'metric_date', 'vo2max', 'fitness_score', 'fatigue_score', 'form_score', 'recovery_score', 'resting_heart_rate', 'heart_rate_variability'];
    protected $casts = ['metric_date' => 'date'];
    public function user() { return $this->belongsTo(User::class); }
}
