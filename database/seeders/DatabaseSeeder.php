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
        //     'is_hrd' => true,
        //     'email' => 'hrd@mail.com',
        //     'password' => Hash::make(123),

        //     'nik' => fake()->nik(),
        //     'phone' => fake()->phoneNumber(),
        //     'gender' => Arr::random(['Laki - Laki', 'Perempuan']),
        //     'birth_date' => fake()->date(),
        //     'address' => fake()->address(),
        //     'name' => 'Nadin Risyani',

        //     'role' => 'Kepala Divisi',
        //     'division_id' => 1,
        // ]);


        // Division::factory(3)->create();
        // User::factory(10)->create();
        Attendance::factory(20)->create();
    }
}
