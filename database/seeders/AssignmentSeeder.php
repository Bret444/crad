<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Adviser;
use App\Models\AssignmentPanel;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    public function run()
    {
        AssignmentPanel::truncate();
        Assignment::truncate();
        
        // Create advisers first
        $advisers = [
            ['name' => 'Dr. Smith', 'email' => 'dr.smith@example.com'],
            ['name' => 'Prof. Johnson', 'email' => 'prof.johnson@example.com'],
            ['name' => 'Dr. Brown', 'email' => 'dr.brown@example.com'],
        ];

        foreach ($advisers as $adviserData) {
            Adviser::firstOrCreate(['email' => $adviserData['email']], $adviserData);
        }

        // Create assignments
        $assignments = [
            ['department' => 'BSIT', 'section' => '4101', 'adviser_id' => 1],
            ['department' => 'BSIT', 'section' => '4102', 'adviser_id' => 2],
            ['department' => 'CRIM', 'section' => '4101', 'adviser_id' => 3],
        ];

        foreach ($assignments as $assignmentData) {
            $assignment = Assignment::firstOrCreate($assignmentData);
            
            // Create panel members for each assignment
            $panelMembers = [
                ['assignment_id' => $assignment->id, 'name' => 'Dr. Elacion', 'role' => 'Chairperson'],
                ['assignment_id' => $assignment->id, 'name' => 'Mr. Constantino', 'role' => 'Member'],
                ['assignment_id' => $assignment->id, 'name' => 'Ms. Garcia', 'role' => 'Member'],
            ];

            foreach ($panelMembers as $panelData) {
                AssignmentPanel::firstOrCreate($panelData);
            }
        }
    }
}