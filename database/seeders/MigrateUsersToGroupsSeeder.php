<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Group;

class MigrateUsersToGroupsSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('role', 'student')
            ->whereNotNull('cluster')
            ->whereNotNull('group_no')
            ->whereNotNull('department')
            ->orderBy('cluster')
            ->orderBy('group_no')
            ->get();

        $groupedStudents = $students->groupBy(function ($student) {
            return $student->department . '_' . $student->cluster . '_' . $student->group_no;
        });

        foreach ($groupedStudents as $key => $members) {
            $firstMember = $members->first();
            
            $existingGroup = Group::where('department', $firstMember->department)
                ->where('cluster', $firstMember->cluster)
                ->where('group_number', $firstMember->group_no)
                ->first();

            if ($existingGroup) {
                continue;
            }

            $groupData = [
                'department' => $firstMember->department,
                'cluster' => $firstMember->cluster,
                'group_number' => $firstMember->group_no,
                'group_id' => $firstMember->group_id ?? null,
                'password' => null,
                'leader_member' => 1,
            ];

            foreach ($members as $index => $member) {
                $memberNum = $index + 1;
                if ($memberNum <= 5) {
                    $groupData["member{$memberNum}_name"] = $member->name;
                    $groupData["member{$memberNum}_student_id"] = $member->email;
                }
            }

            Group::create($groupData);
        }
    }
}
