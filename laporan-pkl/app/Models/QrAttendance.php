<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrAttendance extends Model
{
    protected $fillable = [
        'qr_session_id',
        'employee_id',
        'status',
        'device_cookie_id',
        'ip_address',
        'user_agent',
    ];

    public function session()
    {
        return $this->belongsTo(QrSession::class, 'qr_session_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
