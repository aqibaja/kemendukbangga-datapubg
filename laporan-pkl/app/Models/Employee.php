<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'nama',
        'unsur',
        'kabupaten_kota',
    ];

    public function qrAttendances()
    {
        return $this->hasMany(QrAttendance::class);
    }
}
