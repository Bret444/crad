<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Panel;
use App\Models\Adviser;

class PanelAdviserSeeder extends Seeder
{
    public function run()
    {
        Panel::truncate();
        Adviser::truncate();

        // Add sample panels from reference HTML
        Panel::create([
            'department' => 'BSIT',
            'name' => 'Dr. Elacion',
            'expertise' => 'Professor',
            'availability' => ['2025-10-15 (08:00 - 12:00)', '2025-10-16 (13:00 - 17:00)']
        ]);

        Panel::create([
            'department' => 'BSIT',
            'name' => 'Mr. Baa',
            'expertise' => 'Assistant Professor',
            'availability' => ['2025-10-15 (13:00 - 17:00)']
        ]);

        Panel::create([
            'department' => 'BSIT',
            'name' => 'Ms. Indoso',
            'expertise' => 'Instructor',
            'availability' => ['2025-10-16 (08:00 - 12:00)']
        ]);

        Panel::create([
            'department' => 'CRIM',
            'name' => 'Dr. Cruz',
            'expertise' => 'Professor',
            'availability' => ['2025-10-16 (08:00 - 12:00)']
        ]);

        // Add sample advisers from reference HTML
        Adviser::create([
            'department' => 'BSIT',
            'name' => 'Dr. Elacion',
            'expertise' => 'Professor',
            'sections' => ['4107', '4108']
        ]);

        Adviser::create([
            'department' => 'BSIT',
            'name' => 'Prof. Santos',
            'expertise' => 'Assistant Professor',
            'sections' => ['4109', '4101']
        ]);

        Adviser::create([
            'department' => 'CRIM',
            'name' => 'Dr. Reyes',
            'expertise' => 'Doctoral',
            'sections' => ['4101', '4102']
        ]);

        // Add more sample data for other departments
        Panel::create([
            'department' => 'EDUC',
            'name' => 'Prof. Garcia',
            'expertise' => 'Associate Professor',
            'availability' => ['2025-10-17 (09:00 - 13:00)']
        ]);

        Adviser::create([
            'department' => 'EDUC',
            'name' => 'Prof. Garcia',
            'expertise' => 'Associate Professor',
            'sections' => ['4103', '4104']
        ]);

        Panel::create([
            'department' => 'BSBA',
            'name' => 'Dr. Martinez',
            'expertise' => 'Professor',
            'availability' => ['2025-10-18 (10:00 - 14:00)', '2025-10-19 (08:00 - 12:00)']
        ]);

        Adviser::create([
            'department' => 'BSBA',
            'name' => 'Dr. Martinez',
            'expertise' => 'Professor',
            'sections' => ['4105', '4106']
        ]);
    }
}