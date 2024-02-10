<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Dandy',
            'email' => 'dandy@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin'
        ]);

        $this -> call([
            CategorySeeder::class,
            ProductSeeder::class
        ]);
    }
}
