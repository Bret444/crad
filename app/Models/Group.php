<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Group extends Authenticatable
{
    use SoftDeletes;
    
    protected $fillable = [
        'group_id',
        'email',
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
    
    protected $hidden = ['password', 'remember_token'];
    
    public function getAuthIdentifierName()
    {
        return 'email';
    }
    
    public function getAuthPassword()
    {
        return $this->password;
    }
}