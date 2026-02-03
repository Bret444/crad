<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = ['department', 'section', 'group_number', 'adviser_id'];

    protected $casts = [
        'group_number' => 'array',
    ];

    public function panels()
    {
        return $this->hasMany(AssignmentPanel::class);
    }

    public function adviser()
    {
        return $this->belongsTo(Adviser::class)->withTrashed();
    }

    public function assignmentPanels()
    {
        return $this->hasMany(AssignmentPanel::class);
    }

}

