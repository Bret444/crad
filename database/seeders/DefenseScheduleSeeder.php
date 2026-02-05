<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DefenseSchedule;
use App\Models\Assignment;

class DefenseScheduleSeeder extends Seeder
{
    public function run()
    {
        DefenseSchedule::truncate();
        
        $assignments = Assignment::all();
        
        foreach ($assignments as $assignment) {
            // Create PRE-ORAL defense schedules (10 groups per assignment)
            for ($i = 1; $i <= 10; $i++) {
                DefenseSchedule::create([
                    'department' => $assignment->department,
                    'section' => $assignment->section,
                    'group_id' => 'A' . $i,
                    'defense_type' => 'PRE-ORAL',
                    'assignment_id' => $assignment->id,
                    'status' => 'Pending',
                    'panel_data' => [
                        'adviser' => $assignment->adviser ? $assignment->adviser->name : 'No Adviser',
                        'chairperson' => $assignment->assignmentPanels->where('role', 'Chairperson')->first()->name ?? 'No Chair',
                        'members' => $assignment->assignmentPanels->where('role', '!=', 'Chairperson')->pluck('name')->implode(', ') ?: 'No Members'
                    ]
                ]);
            }
        }
    }
}