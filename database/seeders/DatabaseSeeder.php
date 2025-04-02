<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Division;
use App\Models\Attendance;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Division::factory(2)->create();
        // User::factory()->create([
        //     'name' => 'Admin',
        //     'email' => 'admin@mail.com',
        //     'is_admin' => true
        // ]);
        User::factory(100)->create();
        // Attendance::factory(50)->create();
    }
}
