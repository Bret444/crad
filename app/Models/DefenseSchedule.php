<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DefenseSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'department',
        'section',
        'group_id',
        'defense_type',
        'original_defense_type',
        'assignment_id',
        'defense_date',
        'start_time',
        'end_time',
        'set_letter',
        'status',
        'panel_data'
    ];

    protected $casts = [
        'panel_data' => 'array'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}