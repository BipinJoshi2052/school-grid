<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\User;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first user with user_type = 2
        $user = User::where('user_type_id', 2)->first();

        if (!$user) {
            $this->command->warn('No user found with user_type = 2. Skipping Department seeding.');
            return;
        }

        $departments = [
            ['user_id' => $user->id, 'title' => 'Science'],
            ['user_id' => $user->id, 'title' => 'Mathematics'],
            ['user_id' => $user->id, 'title' => 'Arts'],
            ['user_id' => $user->id, 'title' => 'Sports'],
            ['user_id' => $user->id, 'title' => 'Computer Science'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}
