<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserType;

class UserTypeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        collect(['superadmin','client','staff','student'])->each(fn($name) =>
         UserType::firstOrCreate(['name'=>$name])
        );
        // \App\Models\User::factory(10)->create();
    }
}
