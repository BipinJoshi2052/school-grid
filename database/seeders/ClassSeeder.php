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
        DB::table('classes')->insert([
            ['user_id' => 2, 'title' => 'Class A', 'batch_id' => 1,'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 2, 'title' => 'Class B', 'batch_id' => 2,'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 2, 'title' => 'Class C', 'batch_id' => null,'created_at' => Carbon::now(), 'updated_at' => Carbon::now()], // Batch is nullable
            ['user_id' => 2, 'title' => 'Class D', 'batch_id' => null,'created_at' => Carbon::now(), 'updated_at' => Carbon::now()], // Batch is nullable
        ]);
    }
}
