<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $user = User::where('user_type_id', 2)->first();
        // $department = Department::where('user_id', 2)->first();
        // $position = Position::where('user_id', 2)->first();

        // //Create User
        // $staff_user = User::create([
        //     'name' => 'John Doe', // Sample name
        //     'email' => small name + @gmail.com
        //     'password' => create password 'secret'
        //     'avatar' => null,  // Male
        //     'user_type_id' =>3
        //     'phone' => null,
        //     'parent_id' => $user->id,
        //     created_at and updated_at  = now
        // ]);

        // // Create staff
        // Staff::create([
        //     'school_id' => $user->id, // Assigning the school_id from first user row with user_type_id = 2
        //     'user_id' => $staff_user->id,  // Assuming user_id is the same as school_id for the first staff
        //     'name' => 'John Doe', // Sample name
        //     'department_id' => $department->id,  // Assuming nullable for now
        //     'position_id' => $position->id,  // Assuming nullable for now
        //     'gender' => 0,  // Male
        //     'joined_date' => now(),
        //     'address' => '123 Main St',
        // ]);
    }
}
