<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityGpsPoint extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'activity_id', 'timestamp', 'latitude', 'longitude',
        'altitude', 'heart_rate', 'speed', 'power', 'cadence', 'temperature'
    ];

    protected $casts = ['timestamp' => 'datetime'];

    public function activity() { return $this->belongsTo(Activity::class); }
}
