<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'group_id',
        'password',
        'department',
        'cluster', 
        'group_number',
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
        'leader_member'
    ];
}