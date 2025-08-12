<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserType;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // $saType = UserType::where('name','superadmin')->first();
        // User::firstOrCreate(
        //     ['email'=>'nepal@example.com'],
        //     [
        //         'name'=>'Super Admin',
        //         'password'=>Hash::make('secret'),
        //         'user_type_id'=>$saType->id
        //     ]
        // );
        User::firstOrCreate(
            ['email'=>'nepalmav@example.com'],
            [
                'name'=>'Nepal Ma V.',
                'password'=>Hash::make('secret'),
                'user_type_id'=> 2
            ]
        );
    }
}
