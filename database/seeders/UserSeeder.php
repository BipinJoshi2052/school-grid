<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserType;
use App\Models\InstitutionDetail;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $saType = UserType::where('name','superadmin')->first();
        User::firstOrCreate(
            ['email'=>'admin@seatplanpro.com'],
            [
                'name'=>'Super Admin',
                'password'=>Hash::make('secret'),
                'user_type_id'=>$saType->id
            ]
        );
        $scType = UserType::where('name','client')->first();
        $school = User::firstOrCreate(
            ['email'=>'school@seatplanpro.com'],
            [
                'name'=>'Example School',
                'password'=>Hash::make('secret'),
                'user_type_id'=>$scType->id
            ]
        );
        InstitutionDetail::firstOrCreate(
            ['user_id'=> $school->id],
            [
                'user_id'=> $school->id,
                'client_id' => 'SPP-1',
                'institution_name' => 'Example School'
            ]
        );
        // User::firstOrCreate(
        //     ['email'=>'test-school@example.com'],
        //     [
        //         'name'=>'Test School',
        //         'password'=>Hash::make('secret'),
        //         'user_type_id'=> 2
        //     ]
        // );
    }
}
