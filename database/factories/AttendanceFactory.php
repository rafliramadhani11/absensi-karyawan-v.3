<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::withoutAdmin()->get();
        $status = Arr::random(['hadir', 'izin', 'tidak hadir']);

        return [
            'created_at' => fake()->dateTimeBetween('-5 week', '+3 week'),
            'absen_datang' => $status === 'hadir' ? fake()->dateTimeBetween('-8 hours', '-4 hours') : null,
            'absen_pulang' => $status === 'hadir' ? fake()->dateTimeBetween('-4 hours') : null,
            'alasan' => in_array($status, ['izin', 'tidak hadir']) ? fake()->sentence(3) : null,

            'status' => $status,
            // 'user_id' => $users->random()->id,
            'user_id' => 4,
        ];
    }
}
