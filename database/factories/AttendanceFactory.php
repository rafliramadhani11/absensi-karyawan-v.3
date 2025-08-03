<?php

namespace Database\Factories;

use Carbon\Carbon;
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
        // Ambil ID user aktif yang bukan admin (lebih efisien kalau disiapkan di Seeder)
        $userId = rand(4, 5); // atau sesuaikan berdasarkan data asli kamu

        // Static untuk menyimpan tanggal yang sudah dipakai per user
        static $usedDatesPerUser = [];

        $status = Arr::random(['hadir', 'izin', 'tidak hadir']);

        // Cari tanggal kerja (Senin–Jumat) yang belum digunakan oleh user ini
        $attempt = 0;
        do {
            $date = fake()->dateTimeBetween('-12 weeks', '+3 weeks');
            $carbonDate = Carbon::instance($date)->startOfDay();
            $dateKey = $carbonDate->format('Y-m-d');

            $attempt++;
            if ($attempt > 100) {
                // Hindari infinite loop
                throw new \Exception("Too many attempts to find a unique weekday date for user {$userId}");
            }
        } while (
            $carbonDate->isWeekend() ||
            in_array($dateKey, $usedDatesPerUser[$userId] ?? [])
        );

        // Tandai tanggal sudah digunakan untuk user ini
        $usedDatesPerUser[$userId][] = $dateKey;

        $absenDatang = null;
        if ($status === 'hadir') {
            $jam = fake()->numberBetween(7, 8); // antara jam 07–08
            $menit = fake()->numberBetween(0, 59);
            $detik = fake()->numberBetween(0, 59);
            $absenDatang = $carbonDate->copy()->setTime($jam, $menit, $detik);
        }

        return [
            'created_at' => $carbonDate,
            'absen_datang' => $absenDatang,
            'absen_pulang' => $status === 'hadir' ? $carbonDate->copy()->setTime(17, 0, 0) : null,
            'alasan' => in_array($status, ['izin', 'tidak hadir']) ? fake()->sentence(3) : null,
            'status' => $status,
            'user_id' => $userId,
        ];
    }
}
