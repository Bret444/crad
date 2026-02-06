<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Adviser.php
class Adviser extends Model
{
    use SoftDeletes;
    protected $fillable = ['department', 'name', 'contact', 'expertise', 'sections', 'availability']; 
    
    protected $casts = [
        'sections' => 'array',     // CRITICAL: This enables PHP to read '[4101,4102]' correctly.
        'availability' => 'array', 
    ];

 
}