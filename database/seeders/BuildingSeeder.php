<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = User::where('user_type_id', 2)->first();
        DB::table('buildings')->insert([
            [
                'user_id' => $user->id,
                'name' => 'Building 1',
                'rooms' => json_encode([
                    ['title' => 'Room 1', 'benches' => 2, 'seats' => 3],
                    ['title' => 'Room 2', 'benches' => 3, 'seats' => 3],
                    ['title' => 'Room 3', 'benches' => 4, 'seats' => 2]
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'user_id' => $user->id,
                'name' => 'Building 2',
                'rooms' => json_encode([
                    ['title' => 'Room 1', 'benches' => 2, 'seats' => 3],
                    ['title' => 'Room 2', 'benches' => 3, 'seats' => 4]
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
