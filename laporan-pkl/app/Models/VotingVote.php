<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotingVote extends Model
{
    protected $fillable = [
        'voter_name',
        'voter_type',
        'voter_employee_id',
        'voter_pkb_id',
        'candidate_golongan_1',
        'candidate_golongan_2',
        'candidate_golongan_3',
        'ip_address',
        'user_agent',
        'device_cookie_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'voter_employee_id');
    }

    public function pkbEmployee()
    {
        return $this->belongsTo(PkbEmployee::class, 'voter_pkb_id');
    }

    public function candidate1()
    {
        return $this->belongsTo(VotingCandidate::class, 'candidate_golongan_1');
    }

    public function candidate2()
    {
        return $this->belongsTo(VotingCandidate::class, 'candidate_golongan_2');
    }

    public function candidate3()
    {
        return $this->belongsTo(VotingCandidate::class, 'candidate_golongan_3');
    }
}
