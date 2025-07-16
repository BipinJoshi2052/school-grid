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
        DB::table('sections')->insert([
            ['user_id' => 2, 'title' => 'Section 1A', 'class_id' => 1,'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 2, 'title' => 'Section 1B', 'class_id' => 2,'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 2, 'title' => 'Section 2A', 'class_id' => 3,'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 2, 'title' => 'Section 2B', 'class_id' => 4,'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
