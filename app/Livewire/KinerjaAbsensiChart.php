<?php

namespace App\Livewire;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Support\RawJs;
use Livewire\Attributes\On;
use Filament\Widgets\ChartWidget;

class KinerjaAbsensiChart extends ChartWidget
{
    protected static ?string $heading = 'Kinerja Absensi Chart';
    protected static ?string $description = 'Kategori Kinerja Absensi per Bulan';
    protected static ?string $maxHeight = '300px';

    public Carbon $fromDate;
    public Carbon $toDate;

    #[On('updateFromDate')]
    public function updateFromDate(?string $from): void
    {
        $this->fromDate = Carbon::parse($from);
        $this->updateChartData();
    }

    #[On('updateToDate')]
    public function updateToDate(?string $to): void
    {
        $this->toDate = Carbon::parse($to);
        $this->updateChartData();
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
            {
                scales: {
                    y: {
                      beginAtZero: true,
                      ticks: {
                        stepSize: 1,
                      }
                    },
                },
            }
        JS);
    }

    protected function getData(): array
    {
        $fromDate = $this->fromDate ?? now()->startOfYear();
        $toDate   = $this->toDate ?? now()->endOfYear();

        $users = \App\Models\User::where('is_admin', false)->where('is_hrd', false)->get();

        $periods = CarbonPeriod::create($fromDate, '1 month', $toDate);

        $labels = [];
        $kategoriCounts = [
            'Sangat Disiplin' => [],
            'Disiplin'        => [],
            'Kurang Disiplin' => [],
            'Tidak Disiplin'  => [],
        ];

        foreach ($periods as $period) {
            $labels[] = $period->format('F Y');

            $counts = [
                'Sangat Disiplin' => 0,
                'Disiplin'        => 0,
                'Kurang Disiplin' => 0,
                'Tidak Disiplin'  => 0,
            ];

            foreach ($users as $user) {
                $start = $period->copy()->startOfMonth();
                $end   = $period->copy()->endOfMonth();

                $attendances = \App\Models\Attendance::where('user_id', $user->id)
                    ->whereBetween('created_at', [$start, $end])
                    ->get();

                // === hitung KPI (sama seperti logic kamu) ===
                $realisasiHadir = $attendances->where('status', 'hadir')->count();
                $realisasiIzin = $attendances->where('status', 'izin')->count();
                $realisasiTelat = $attendances->where('status', 'hadir')
                    ->where('absen_datang', '>', '08:00:00')->count();
                $realisasiAlpha = $attendances->where('status', 'tidak hadir')->count();

                $totalWorkingDays = 0;
                foreach (CarbonPeriod::create($start, $end) as $date) {
                    if (! $date->isWeekend()) {
                        $totalWorkingDays++;
                    }
                }

                $maksimumHadir = $totalWorkingDays;
                $maksimumIzin  = 5;
                $maksimumTelat = 3;
                $maksimumAlpha = 0;

                $bobotHadir = 40;
                $bobotIzin  = 15;
                $bobotTelat = 15;
                $bobotAlpha = 30;

                $nilaiHadir = $maksimumHadir > 0 ? ($realisasiHadir / $maksimumHadir) * $bobotHadir : 0;
                $nilaiIzin  = $realisasiIzin <= $maksimumIzin ? (($maksimumIzin - $realisasiIzin) / $maksimumIzin) * $bobotIzin : 0;
                $nilaiTelat = $maksimumTelat > 0 ? max(0, (1 - $realisasiTelat / $maksimumTelat) * $bobotTelat) : 0;
                $nilaiAlpha = $maksimumHadir > 0 ? max(0, (1 - $realisasiAlpha / $maksimumHadir) * $bobotAlpha) : 0;

                $totalNilaiAkhir = $nilaiHadir + $nilaiIzin + $nilaiTelat + $nilaiAlpha;

                $kategori = match (true) {
                    $totalNilaiAkhir >= 95 => 'Sangat Disiplin',
                    $totalNilaiAkhir >= 80 => 'Disiplin',
                    $totalNilaiAkhir >= 60 => 'Kurang Disiplin',
                    default => 'Tidak Disiplin',
                };

                $counts[$kategori]++;
            }

            foreach ($counts as $kategori => $jumlah) {
                $kategoriCounts[$kategori][] = $jumlah;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sangat Disiplin',
                    'data'  => $kategoriCounts['Sangat Disiplin'],
                    'tension' => .3,
                    'borderColor' => 'rgb(34,197,94)',
                ],
                [
                    'label' => 'Disiplin',
                    'data'  => $kategoriCounts['Disiplin'],
                    'tension' => .3,
                    'borderColor' => 'rgb(59,130,246)',
                ],
                [
                    'label' => 'Kurang Disiplin',
                    'data'  => $kategoriCounts['Kurang Disiplin'],
                    'tension' => .3,
                    'borderColor' => 'rgb(234,179,8)',
                ],
                [
                    'label' => 'Tidak Disiplin',
                    'data'  => $kategoriCounts['Tidak Disiplin'],
                    'tension' => .3,
                    'borderColor' => 'rgb(239,68,68)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line'; // cocok untuk kategori
    }
}
