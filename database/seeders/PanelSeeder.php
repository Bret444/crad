<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Panel;

class PanelSeeder extends Seeder
{
    public function run(): void
    {
        Panel::truncate();
        
        Panel::insert([
            [
                'department' => 'BSIT',
                'name' => 'Dr. Elacion',
                'expertise' => 'Professor',
                'availability' => json_encode([
                    ['date' => '2025-10-15', 'time' => '08:00 - 12:00'],
                    ['date' => '2025-10-16', 'time' => '13:00 - 17:00']
                ])
            ],
            [
                'department' => 'BSIT',
                'name' => 'Mr. Baa',
                'expertise' => 'Assistant Professor',
                'availability' => json_encode([
                    ['date' => '2025-10-15', 'time' => '13:00 - 17:00']
                ])
            ],
            [
                'department' => 'BSIT',
                'name' => 'Ms. Indoso',
                'expertise' => 'Instructor',
                'availability' => json_encode([
                    ['date' => '2025-10-16', 'time' => '08:00 - 12:00']
                ])
            ],
            [
                'department' => 'CRIM',
                'name' => 'Dr. Cruz',
                'expertise' => 'Professor',
                'availability' => json_encode([
                    ['date' => '2025-10-16', 'time' => '08:00 - 12:00']
                ])
            ],
            [
                'department' => 'EDUC',
                'name' => 'Prof. Garcia',
                'expertise' => 'Associate Professor',
                'availability' => json_encode([
                    ['date' => '2025-10-17', 'time' => '09:00 - 13:00']
                ])
            ],
            [
                'department' => 'BSBA',
                'name' => 'Dr. Martinez',
                'expertise' => 'Professor',
                'availability' => json_encode([
                    ['date' => '2025-10-18', 'time' => '10:00 - 14:00'],
                    ['date' => '2025-10-19', 'time' => '08:00 - 12:00']
                ])
            ],
        ]);
    }
}
