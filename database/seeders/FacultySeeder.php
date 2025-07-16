<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('faculties')->insert([
            [
                'user_id' => 2,
                'title' => 'CSIT',
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ],
            [
                'user_id' => 2,
                'title' => 'BSW',
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ],
            [
                'user_id' => 2,
                'title' => 'BIM',
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ],
        ]);
    }
}
