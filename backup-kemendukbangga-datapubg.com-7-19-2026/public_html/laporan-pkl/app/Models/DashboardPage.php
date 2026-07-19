<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DashboardPage extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_dashboard',
        'slug',
        'platform',
        'embed_link',
        'thumbnail',
        'dibuat_oleh'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function views()
    {
        return $this->hasMany(DashboardView::class, 'dashboard_id');
    }

    protected static function booted()
    {
        static::created(function ($dashboard) {
            $dashboard->slug = $dashboard->id . '-' . Str::slug($dashboard->nama_dashboard);
            $dashboard->save();
        });
    }
}
