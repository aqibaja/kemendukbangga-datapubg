<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresentationLink extends Model
{
    protected $table = 'presentation_links';
    protected $fillable = ['name', 'url', 'key'];
}
