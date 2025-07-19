<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $user = User::where('user_type_id', 2)->first(); // Assuming user_type_id = 2 is for schools
        // $class = ClassModel::where('user_id', $user->id)->where('batch_id', '')->first();
        // $section = Section::where('class_id', $class->id)->first();

        // // Create Staff User
        // $student_user_1 = User::create(
        //     [
        //         'name' => 'Ram Kumar', // Sample name
        //         'email' => 'ramkumar@gmail.com', // You can dynamically generate the email address using the name
        //         'password' => bcrypt('secret'), // Create password 'secret' hashed
        //         'avatar' => null,
        //         'user_type_id' => 4,
        //         'phone' => null,
        //         'parent_id' => $user->id,
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ]
        // );

        // // Create student
        // Student::create(
        //     [
        //         'school_id' => $user->id,
        //         'user_id' => $user->id,
        //         'name' => 'Ram Kumar',
        //         'gender' => 1,
        //         'address' => '456 Main St',
        //         'class_id' => $class->id,
        //         'section_id' => $section->id,
        //     ],
        //     [
        //         'school_id' => $user->id,
        //         'user_id' => $user->id,
        //         'name' => 'Sita Sharma',
        //         'gender' => 1,
        //         'address' => '456 Main St',
        //         'class_id' => $class->id,
        //         'section_id' => $section->id,
        //     ],
        //     [
        //         'school_id' => $user->id,
        //         'user_id' => $user->id,
        //         'name' => 'Hari Bahadur',
        //         'gender' => 1,
        //         'address' => '456 Main St',
        //         'class_id' => $class->id,
        //         'section_id' => $section->id,
        //     ]
        // );
    }
}
