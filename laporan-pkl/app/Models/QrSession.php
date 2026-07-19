<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrSession extends Model
{
    protected $fillable = [
        'title',
        'latitude',
        'longitude',
        'radius_meters',
        'refresh_time_seconds',
        'is_active',
        'end_time',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'end_time' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendances()
    {
        return $this->hasMany(QrAttendance::class);
    }
}
