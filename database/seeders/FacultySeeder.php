<?php

namespace Database\Seeders;

use App\Models\User;
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
        $user = User::where('user_type_id', 2)->first();
        DB::table('faculties')->insert([
            [
                'user_id' => $user->id,
                'title' => 'CSIT',
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ],
            [
                'user_id' => $user->id,
                'title' => 'BSW',
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ],
            [
                'user_id' => $user->id,
                'title' => 'BIM',
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ],
        ]);
    }
}
