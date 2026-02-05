<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Adviser;

class AdviserSeeder extends Seeder
{
    public function run(): void
    {
        Adviser::truncate();
        
        Adviser::insert([
            [
                'department' => 'BSIT',
                'name' => 'Dr. Elacion',
                'expertise' => 'Professor',
                'sections' => json_encode([4101, 4102]),
            ],
            [
                'department' => 'BSIT',
                'name' => 'Mr. Baa',
                'expertise' => 'Assistant Professor',
                'sections' => json_encode([4103]),
            ],
            [
                'department' => 'BSIT',
                'name' => 'Ms. Indoso',
                'expertise' => 'Instructor',
                'sections' => json_encode([4104, 4105]),
            ],
            [
                'department' => 'CRIM',
                'name' => 'Dr. Cruz',
                'expertise' => 'Professor',
                'sections' => json_encode([4101]),
            ],
            [
                'department' => 'EDUC',
                'name' => 'Prof. Garcia',
                'expertise' => 'Associate Professor',
                'sections' => json_encode([4102]),
            ],
            [
                'department' => 'BSBA',
                'name' => 'Dr. Martinez',
                'expertise' => 'Professor',
                'sections' => json_encode([4103, 4104]),
            ],
        ]);
    }
}
