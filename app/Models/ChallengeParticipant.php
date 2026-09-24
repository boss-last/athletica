<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChallengeParticipant extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'challenge_participants';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    protected $fillable = ['challenge_id', 'user_id', 'current_progress'];
    protected $casts = ['joined_at' => 'datetime'];

    public function challenge() { return $this->belongsTo(Challenge::class); }
    public function user() { return $this->belongsTo(User::class); }
}
