<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Position;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

         \App\Models\User::factory()->create([
             'name' => 'admin',
             'email' => 'admin@gmail.com',
             'document' => '123456789',
             'password' => bcrypt('12345678'),

         ]);

        $this->call([
            PositionSeeder::class,
            RolesAndPermitionSeeder::class,
        ]);
    }
}
