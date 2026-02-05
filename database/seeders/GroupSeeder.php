<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    public function run()
    {
        Group::truncate();
        $groups = [
            [
                'group_id' => 'A1',
                'password' => 'group123',
                'department' => 'BSIT',
                'cluster' => '4101',
                'group_number' => 1,
                'member1_name' => 'John Doe',
                'member1_student_id' => '2021-001',
                'member2_name' => 'Jane Smith',
                'member2_student_id' => '2021-002',
                'member3_name' => 'Bob Johnson',
                'member3_student_id' => '2021-003',
                'leader_member' => 1
            ],
            [
                'group_id' => 'A2',
                'password' => 'group123',
                'department' => 'BSIT',
                'cluster' => '4101',
                'group_number' => 2,
                'member1_name' => 'Alice Brown',
                'member1_student_id' => '2021-004',
                'member2_name' => 'Charlie Wilson',
                'member2_student_id' => '2021-005',
                'member3_name' => 'Diana Davis',
                'member3_student_id' => '2021-006',
                'leader_member' => 2
            ],
            [
                'group_id' => 'B1',
                'password' => 'group123',
                'department' => 'BSIT',
                'cluster' => '4101',
                'group_number' => 6,
                'member1_name' => 'Eva Martinez',
                'member1_student_id' => '2021-007',
                'member2_name' => 'Frank Garcia',
                'member2_student_id' => '2021-008',
                'member3_name' => 'Grace Lee',
                'member3_student_id' => '2021-009',
                'leader_member' => 1
            ]
        ];

        foreach ($groups as $group) {
            Group::create($group);
        }
    }
}