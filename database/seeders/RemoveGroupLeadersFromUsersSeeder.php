<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class RemoveGroupLeadersFromUsersSeeder extends Seeder
{
    public function run(): void
    {
        User::whereIn('id', [5, 7, 8, 9])
            ->where('role', 'student')
            ->whereIn('group_id', ['IT001', 'IT002', 'IT011', 'IT003'])
            ->delete();
        
        $this->command->info('Group leader users removed from users table');
    }
}
