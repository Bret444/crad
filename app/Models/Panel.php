<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Panel extends Model
{
    use SoftDeletes;
    // 1. Remove 'cluster_id' from $fillable.
    protected $fillable = ['department', 'name', 'expertise', 'availability', 'role', 'contact_number'];
    
    protected $casts = [
        'availability' => 'array', 
    ];

    public function assignmentPanels()
    {
        return $this->hasMany(AssignmentPanel::class, 'panel_id');
    }
    
}