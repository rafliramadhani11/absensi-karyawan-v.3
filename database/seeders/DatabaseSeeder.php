<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Division;
use App\Models\Attendance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Division::factory(2)->create();
        User::factory(20)->create();
        Attendance::factory(50)->create();
        User::factory()->create([
            'is_admin' => true,
            'email' => 'admin@mail.com',

            'password' => Hash::make(123),
            'nik' => null,
            'name' => 'Admin',
            'phone' => null,
            'gender' => null,
            'birth_date' => null,
            'address' => null,

            'role' => null,
            'division_id' => null,
        ]);
    }
}
