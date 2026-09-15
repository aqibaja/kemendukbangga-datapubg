<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotingSetting extends Model
{
    protected $fillable = [
        'is_popup_active',
        'is_result_visible',
        'popup_inactive_at',
        'result_visible_at',
        'golongan_1_name',
        'golongan_2_name',
        'golongan_3_name',
    ];

    protected function casts(): array
    {
        return [
            'is_popup_active' => 'boolean',
            'is_result_visible' => 'boolean',
            'popup_inactive_at' => 'datetime',
            'result_visible_at' => 'datetime',
        ];
    }
}
