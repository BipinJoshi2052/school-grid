<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;
use App\Models\User;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first user with user_type = 2
        $user = User::where('user_type_id', 2)->first();

        if (!$user) {
            $this->command->warn('No user found with user_type = 2. Skipping Position seeding.');
            return;
        }

        $positions = [
            ['user_id' => $user->id, 'title' => 'Principal'],
            ['user_id' => $user->id, 'title' => 'Vice Principal'],
            ['user_id' => $user->id, 'title' => 'Teacher'],
            ['user_id' => $user->id, 'title' => 'Accountant'],
            ['user_id' => $user->id, 'title' => 'Clerk'],
        ];

        foreach ($positions as $pos) {
            Position::create($pos);
        }
    }
}
