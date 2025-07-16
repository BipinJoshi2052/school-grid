<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            FacultySeeder::class,
            BatchSeeder::class,
            ClassSeeder::class,
            SectionSeeder::class,
            // UserTypeSeeder::class,
            // UserSeeder::class,

        ]);
        // \App\Models\User::factory(10)->create();
    }
}
