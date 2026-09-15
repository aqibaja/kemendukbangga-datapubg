<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotingCandidate extends Model
{
    protected $fillable = [
        'nama',
        'golongan',
        'foto',
        'unsur',
        'urutan',
    ];

    public function votes1()
    {
        return $this->hasMany(VotingVote::class, 'candidate_golongan_1');
    }

    public function votes2()
    {
        return $this->hasMany(VotingVote::class, 'candidate_golongan_2');
    }

    public function votes3()
    {
        return $this->hasMany(VotingVote::class, 'candidate_golongan_3');
    }
}
