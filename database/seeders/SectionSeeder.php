<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Retrieve batch IDs from the database
        $classes = DB::table('classes')->pluck('id');
        foreach ($classes as $classId) {
            DB::table('classes')->insert([
                'user_id' => 2,
                'title' => $classId === 1 ? '1st Semester' : '2nd Semester', // Adjust titles based on batch_id
                'batch_id' => $classId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        DB::table('sections')->insert([
            ['user_id' => 2, 'title' => 'Section 1A', 'class_id' => 1,'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 2, 'title' => 'Section 1B', 'class_id' => 2,'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 2, 'title' => 'Section 2A', 'class_id' => 3,'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 2, 'title' => 'Section 2B', 'class_id' => 4,'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
