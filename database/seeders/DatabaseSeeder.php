<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Attendance;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory()->create([
        //     'name' => 'Admin',
        //     'email' => 'admin@mail.com',
        //     'is_admin' => true
        // ]);
        // User::factory(10)->create();
        Attendance::factory(50)->create();
    }
}
