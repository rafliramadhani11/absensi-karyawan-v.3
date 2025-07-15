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
            'created_at' => fake()->dateTimeBetween('-12 week', '+3 week'),
            'absen_datang' => $status === 'hadir' ? fake()->dateTimeBetween('today 07:00:00', 'today 09:00:00') : null,
            'absen_pulang' => $status === 'hadir' ? '17:00:00' : null,
            'alasan' => in_array($status, ['izin', 'tidak hadir']) ? fake()->sentence(3) : null,
            'status' => $status,
            'user_id' => rand(4, 5),
        ];
    }
}
