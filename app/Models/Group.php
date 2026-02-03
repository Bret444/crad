<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'group_id',
        'group_number',
        'password',
        'department',
        'leader_member',
        'member1_name',
        'member1_student_id',
        'member2_name',
        'member2_student_id',
        'member3_name',
        'member3_student_id',
        'member4_name',
        'member4_student_id',
        'member5_name',
        'member5_student_id',
    ];

    protected $hidden = [
        'password',
    ];

    public function getClusterOrSectionAttribute()
    {
        if ($this->department === 'BSIT') {
            $clusterBase = 4101 + floor(($this->group_number - 1) / 10);
            return (string) $clusterBase;
        }
        return 'Section ' . ceil($this->group_number / 10);
    }

    public function updateClusterAfterPreOral()
    {
        if ($this->department === 'BSIT') {
            $currentCluster = (int) $this->getClusterOrSectionAttribute();
            return (string) ($currentCluster + 100);
        }
        return $this->getClusterOrSectionAttribute();
    }
}
