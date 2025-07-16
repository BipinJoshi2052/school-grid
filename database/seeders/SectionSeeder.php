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
            DB::table('sections')->insert([
                'user_id' => 2,
                'title' => $classId === 1 ? 'A' : 'B', // Adjust titles based on batch_id
                'class_id' => $classId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
