<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Retrieve faculty IDs from the database
        $facultyIds = DB::table('faculties')->pluck('id');

        // Prepare data by looping over the faculty IDs and inserting batches
        foreach ($facultyIds as $facultyId) {
            DB::table('batches')->insert([
                'user_id' => 2,
                'title' => '2080',
                'faculty_id' => $facultyId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
