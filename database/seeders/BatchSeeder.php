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
        DB::table('batches')->insert([
            ['user_id' => 2, 'title' => 'Batch 1', 'faculty_id' => 1,'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 2, 'title' => 'Batch 2', 'faculty_id' => 2,'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 2, 'title' => 'Batch 3', 'faculty_id' => 3,'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 2, 'title' => 'Batch 4', 'faculty_id' => 1,'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
