<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Retrieve batch IDs from the database
        $batchIds = DB::table('batches')->pluck('id');

        // Prepare data for the 'classes' table by looping over the batch IDs
        foreach ($batchIds as $batchId) {
            DB::table('classes')->insert([
                'user_id' => 2,
                'title' => $batchId === 1 ? '1st Semester' : '2nd Semester', // Adjust titles based on batch_id
                'batch_id' => $batchId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Add two more records with null batch_id
        DB::table('classes')->insert([
            ['user_id' => 2, 'title' => '1', 'batch_id' => null, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 2, 'title' => '2', 'batch_id' => null, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
