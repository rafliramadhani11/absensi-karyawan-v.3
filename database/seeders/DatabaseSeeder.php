<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Division;
use App\Models\Attendance;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $name = fake()->jobTitle();
        // Division::create([
        //     'name' => $name,
        //     'slug' => Str::slug($name),
        // ]);

        // User::create([
        //     'is_admin' => true,
        //     'name' => 'Admin',
        //     'email' => 'admin@mail.com',
        //     'password' => Hash::make(123),
        // ]);

        // User::create([
        //     'is_hrd' => true,
        //     'name' => 'Human Resources Development',
        //     'email' => 'hrd@mail.com',
        //     'password' => Hash::make(123),
        // ]);


        // Division::factory(3)->create();
        // User::factory(1)->create();
        Attendance::factory(200)->create();
    }
}
