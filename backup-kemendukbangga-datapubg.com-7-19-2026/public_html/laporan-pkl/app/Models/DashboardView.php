<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DashboardView extends Model
{
    use HasFactory;
    protected $fillable = [
        'dashboard_id',
        'user_id',
        'ip_address',
        'user_agent'
    ];

    public function dashboard(){
        return $this->belongsTo(DashboardPage::class, 'dashboard_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
