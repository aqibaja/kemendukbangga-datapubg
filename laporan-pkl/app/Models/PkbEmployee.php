<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PkbEmployee extends Model
{
    protected $fillable = [
        'nama',
        'nip',
        'unsur',
    ];

    public function votes()
    {
        return $this->hasMany(VotingVote::class, 'voter_pkb_id');
    }
}
